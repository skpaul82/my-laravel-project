<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Invoice;
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

        // checking user is login/session out or not
        $this->middleware('auth');
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
                // Note: Retrive customer all info in a SINGLE API request and manage all data as needed.
                
                // Retrive customer all info
                /*
                $customer = Customer::retrieve($user->stripe_id);
                $data['default_source'] = $customer->default_source;
                $data['cards'] = $customer->sources->all(['object' => 'card']);
                $data['bank_accounts'] = $customer->sources->all(['object' => 'bank_account']);
                */

                // Note: OR send multiple request to stripe for gatting same data individually

                // Retrieve Card info 
                /*$data['cards'] = Customer::retrieve($user->stripe_id)
                    ->sources->all(array('limit'=>3, 'object' => 'card'));
                    */
                // dd($data['cards']);
                
                // Retrieve Bank Account info
                /*$data['bank_accounts'] = Customer::retrieve($user->stripe_id)
                    ->sources->all(array('limit'=>3, 'object' => "bank_account"));*/
                // dd($data['bank_accounts'][0]);
                
                
                // Retrieve list of Changing data by customer id
                // $data['charges'] = Charge::all(['customer' => $user->stripe_id]);
                
                // Retrieve list of all charges for customer (limited to 5)
                /*$data['charges'] = Charge::all(array(
                    'limit'=>3, 
                    'customer' => $user->stripe_id,
                ));*/
                
                // Retirve all customers changing data.
                // $data['charges'] = Charge::all();
                // dd($data['charges']);

                // to get all the invoices, invoices are available only for subscriptions
                // $data['invoices'] = Invoice::all();
                // dd($data['invoices']);
                
            } catch (Exception $e) {
                dd($e->getMessage());
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
         * ---- CHECK OR CREATE CUSTOMER ----
         * Check if stripe_id is null or not
         * if stripe_id/customer id is existis in our database 
         * then we will pull the customer data from stripe,
         * if stripe id is null then we need to create stripe customer
         * and store customer id from stripe as stripe_id in application's user table
         */
        if(!empty($user->stripe_id)) {

            // Retrieve customer from stripe by customer stripe id
            $customer = Customer::retrieve($user->stripe_id);
            // dd(1, $customer);

        }else {

            // Create customer if stripe_id is null
            $customer = Customer::create([
                'source' => $request->stripe_token,
                'email' => $user->email,
                'name' => $user->firstName .' '. $user->lastName,
                'description' => 'Testing 1 2 3..',
            ]);

            // Storing customer (stripe) id and other info. in application database for later uses
            $inputs['stripe_id'] = $customer->id;
            User::find($user->id)->update($inputs);

        }


        // Checking payment type
        if ($request->paymentType == 'card') {
            
            // Create/add Card
            
            
            /*try {

                // Charge the customer
                $charge = Charge::create([
                    'amount' => $request->amount*100,
                    'currency' => 'usd',
                    'customer' => $customer->id,
                    // send payment receipt only for sucessfull payment
                    'receipt_email' => $user->email, 
                ]);

            } catch (Exception $e) {
                dd($e->getMessage());
            }*/

        }else{


            try {
                
                // Create/add Bank Account
                // $customer = Customer::retrieve($customer->id);
                $customer->sources->create(array(
                    "source" => $request->stripe_token, 
                    'metadata' => ["customer"=> $customer->id]
                ));

            } catch (Exception $e) {
                // dd($e->getMessage());
                 throw $e;
            }

            // Charge the customer
            /*$charge = Charge::create([
                'amount' => $request->amount*100,
                'currency' => 'usd',
                'customer' => $customer->id,
                // send payment receipt only for sucessfull payment
                'receipt_email' => $user->email, 
            ]);*/

            /*// get the existing bank account
            $customer = \Stripe\Customer::retrieve("cus_AFGbOSiITuJVDs");
            $bank_account = $customer->sources->retrieve("ba_17SHwa2eZvKYlo2CUx7nphbZ");

            // verify the account
            $bank_account->verify(array('amounts' => array(32, 45)));
            */

        }

        // Charge the customer
        Charge::create(array(
            "amount" => $request->amount*100,
            "currency" => "usd",
            "source" => $request->stripe_token, // obtained with Stripe.js
            "description" => "Charge for $user->firstName $user->lastName ($customer->id)",
            'receipt_email' => $user->email, 
        ));

        

        // dd('payment successful', $customer, $charge);

        /*$message = array(
            'body' => 'Thank You. Your payment has been successfully completed. Please check your email for details.',
            'type' => 'success'
        );*/

        return redirect('thank-you');
            // ->withMessage($message);
    }

    /**
     * [paymentSuccess show paymant successful message]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentSuccess(Request $request)
    {
        $message = array(
            'body' => 'Thank You. Your payment has been successfully completed. Please check your email for details.',
            'type' => 'success'
        );
        return view($this->url)
            ->withMessage($message)
            ->with('page_title', $this->page_title);
    }

    /**
     * [makePayment show payment form]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
   /* public function makePayment(Request $request)
    {
        return view('payment.'.$this->url);
    }*/

    /**
     * [paymentHistory show payment history this method is for only generating templete for changing]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentHistory(Request $request)
    {
        $user = Auth::user();

        if(!empty($user->stripe_id)){
            
            // Retrieve list of all charges for customer (limited to 50)
            $data['charges'] = Charge::all(array(
                'limit'=>50, 
                'customer' => $user->stripe_id,
            ));

        } else {

            $data['charges'] = [];
        }

        
        return view('payment.charges', $data);
    }

    /**
     * [paymentSetting show card and back account info.]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentSetting(Request $request)
    {
        $user = Auth::user();

        if(!empty($user->stripe_id)){
            
            // Retrive customer all info
            $customer = Customer::retrieve($user->stripe_id);
            $data['default_source'] = $customer->default_source;
            $data['cards'] = $customer->sources->all(['object' => 'card']);
            $data['bank_accounts'] = $customer->sources->all(['object' => 'bank_account']);

        } else {

            $data['cards'] = [];
            $data['bank_accounts'] = [];
        }

        return view('payment.setting', $data);
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
