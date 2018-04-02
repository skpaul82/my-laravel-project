<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Card;
use Stripe\Account;
use App\User;
use Auth;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['rows'] = '';
        return view($this->url, $data)
            ->with('page_title', $this->page_title);
    }

    public function checkout(Request $request)
    {
        // dd($request->all());

        /*$this->validate($request, [
            'amount' => 'required|minvalue:11',
        ]);*/

        // ALTER TABLE `users` ADD `stripe_id` VARCHAR(255) NOT NULL COMMENT 'stripe customer id' AFTER `updated_at`;
        
        if($request->amount <=10 )
            return redirect()->back()->withErrors("Amount must be at least $11.");

        $user = Auth::user();

        // Set secret key
        Stripe::setApiKey(config('services.stripe.secret'));



        // Create customer
        $customer = Customer::create([
            'source' => $request->stripe_token,
            'email' => $user->email,
        ]);

        if ($request->paymentType == 'card') {
            
            // Charge the customer instead of the card
            $charge = Charge::create([
                'amount' => $request->amount*100,
                'currency' => 'usd',
                'customer' => $customer->id,
            ]);

        }

        $inputs['stripe_id'] = $customer->id;
        User::find($user->id)->update($inputs);

        dd('payment successful', $customer, $charge);

        // Store customer id amountnd other info. in a database for later


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
