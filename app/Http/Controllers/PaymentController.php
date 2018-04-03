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
    function __construct(Request $request)
    {
        parent::__construct($request);

        // Set secret key
        Stripe::setApiKey(config('services.stripe.secret'));

        
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // user info. from user table
        $user = Auth::user();

        // check if the stripe customer id is empty or not
        // if stripe id is null then need to create a customer on stripe and 
        // save customer id as stripe_id in user table
        // but here we will not create any stripe custome for this user as he is not initiated
        // any payment activity in the system till now
        if(!empty($user->stripe_id)){

            try {
                // Retrieve Card info 
                $data['cards'] = Customer::retrieve("$user->stripe_id")
                    ->sources->all(array('limit'=>3, 'object' => 'card'));
                // dd($data['cards']);
                
            } catch (Exception $e) {
                dd($e);
            }
        }

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

        /**
         * Check if stripe_id is null or not
         * if stripe_id/customer id is existis in our database 
         * then we will pull the customer data from stripe,
         * if stripe id is null then we need to create stripe customer
         * and store customer id from stripe as stripe_id in application's user table
         */
        if(!empty($user->stripe_id)) {

            // Retrieve customer from stripe by customer stripe id
            $customer = Customer::retrieve($user->stripe_id);
            dd($customer);

        }else {

            // Create customer if stripe_id is null
            $customer = Customer::create([
                'source' => $request->stripe_token,
                'email' => $user->email,
                'description' => 'Testing 1 2 3..',
            ]);

            // Storing customer (stripe) id and other info. in application database for later uses
            $inputs['stripe_id'] = $customer->id;
            User::find($user->id)->update($inputs);

        }

        // Checking payment type
        if ($request->paymentType == 'card') {
            
            try {
                
                // Charge the customer instead of the card
                $charge = Charge::create([
                    'amount' => $request->amount*100,
                    'currency' => 'usd',
                    'customer' => $customer->id,
                    // send payment receipt only for sucessfull payment
                    'receipt_email' => $user->email, 
                ]);

            } catch (Exception $e) {
                dd($e);
            }

        }else{

            // Create a Bank Account Token
            /*\Stripe\Token::create(array(
                "bank_account" => array(
                    "country" => "US",
                    "currency" => "usd",
                    "account_holder_name" => $request->accountHolderName,
                    "account_holder_type" => $request->AccountType,
                    "routing_number" => $request->routingNumber,
                    "account_number" => $request->accountNumber
                )
            ));*/
            // Charge the customer by ACH
            $customer = Customer::retrieve("customer->id");
            $customer->sources->create(array("source" => "$request->stripe_token"));
            
        }

        

        dd('payment successful', $customer, $charge);

        $message = array(
            'body' => 'Thank You. Your payment has been successfully completed. Please check your email for details.',
            'type' => 'success'
        );

        return redirect('thank-you')
            ->withMessage($message);


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
