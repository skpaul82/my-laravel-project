<div class="row">
    <form action="{{url('checkout')}}" method="post" class="form-horizental col-xs-12 col-md-7 col-md-offset-3 payment-from">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <p>&nbsp;</p>
        <div class="form-group">
            <label for="" class="col-xs-6 col-md-5 paytype-label">PAYMENT TYPE</label>
            <div class="input-group col-xs-6 col-md-7">
                <div id="radioBtn" class="btn-group">
                    <a class="btn btn-success btn-sm text-bold active" data-toggle="paymentType" data-title="card" tabindex="1">Card</a>
                    <a class="btn btn-success btn-sm text-bold notActive" data-toggle="paymentType" data-title="ach">ACH</a>
                </div>
                <input type="hidden" name="paymentType" id="paymentType" value="card">
            </div>
        </div>

        <!-- Payment Card Option -->
        <div class="payby payby-card">
            <div class="">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="cardNumber">CARD NUMBER</label>
                        <div class="input-group">
                            <input 
                            type="tel"
                            data-stripe="number"
                            class="form-control"
                            name="cardNumber"
                            placeholder="Valid Card Number (4242424242424242)"
                            autocomplete="cc-number"
                            required autofocus 
                            />
                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        </div>
                    </div>                            
                </div>
            </div>
            <div class="">
                <div class="col-xs-7 col-md-7">
                    <div class="form-group">
                        <label for="cardExpiry"><span class="hidden-xs">EXPIRATION</span><span class="visible-xs-inline">EXP</span> DATE</label>
                        <div class="input-group">
                            <input 
                            type="tel"
                            data-stripe="exp" 
                            class="form-control" 
                            name="cardExpiry"
                            placeholder="MM / YY"
                            autocomplete="cc-exp"
                            required 
                            />
                            <span class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-5 col-md-5 pull-right">
                    <div class="form-group">
                        <label for="cardCVC">CV CODE</label>
                        <div class="input-group">
                            <input 
                            type="tel"
                            data-stripe="cvc"  
                            class="form-control"
                            name="cardCVC"
                            placeholder="CVC"
                            autocomplete="cc-csc"
                            required
                            />
                            <span class="input-group-addon"><i class="fa fa-cc"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment ACH Option -->
        <div class="payby payby-ach hidden">
            <div class="">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="Country">COUNTRY</label>
                        <div class="input-group">
                            <input 
                            type="text"
                            data-stripe="country"
                            class="form-control"
                            name="Country"
                            placeholder="Country"
                            autocomplete="country"  
                            value="US"
                            />
                            <span class="input-group-addon"><i class="fa fa-bars"></i></span>
                        </div>
                    </div>                            
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="Currency">CURRENCY</label>
                        <div class="input-group">
                            <input 
                            type="text"
                            data-stripe="currency"
                            class="form-control"
                            name="Currency"
                            placeholder="Currency"
                            autocomplete="currency"  
                            value="usd"
                            />
                            <span class="input-group-addon"><i class="fa fa-bars"></i></span>
                        </div>
                    </div>                            
                </div>
            </div>

            <div class="">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="AccountType">ACCOUNT NAME</label>
                        <div class="input-group">
                            <input 
                            type="text"
                            data-stripe="account_holder_name"
                            class="form-control"
                            name="accountHolderName"
                            placeholder="Routing number"
                            autocomplete="routing-number"  
                            value="{{ Auth::user()->firstName .' '. Auth::user()->lastName }}" 
                            />
                            <span class="input-group-addon"><i class="fa fa-bars"></i></span>
                        </div>
                    </div>                            
                </div>
            </div>

            <div class="">
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <label for="routingNumber">ROUTING NUMBER</label>
                        <div class="input-group">
                            <input 
                            type="tel"
                            data-stripe="routing_number"
                            class="form-control"
                            name="routingNumber"
                            placeholder="Routing number"
                            autocomplete="routing-number"  
                            />
                            <span class="input-group-addon"><i class="fa fa-forward"></i></span>
                        </div>
                    </div>                            
                </div>

                <div class="col-xs-12 col-md-7">
                    <div class="form-group">
                        <label for="accountNumber">ACCOUNT NUMBER</label>
                        <div class="input-group">
                            <input 
                            type="tel"
                            data-stripe="account_number"
                            class="form-control"
                            name="accountNumber"
                            placeholder="Account number"
                            autocomplete="account-number"  
                            />
                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        </div>
                    </div>                            
                </div>
            </div>

            <div class="">
            </div>

            <div class="">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="AccountType">ACCOUNT TYPE</label>
                        <div class="input-group">
                            <select data-stripe="account_holder_type" class="form-control" name="AccountType" placeholder="Account Type">
                                <option value="individual">Individual</option>
                                <option value="company">Company</option>
                            </select>
                            <span class="input-group-addon"><i class="fa fa-bars"></i></span>
                        </div>
                    </div>                            
                </div>
            </div>
        </div>

        <!-- Amount -->
        <div class="">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="couponCode">AMOUNT</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                        <input 
                            type="number" 
                            data-stripe="amount" 
                            class="form-control" 
                            name="amount" 
                            id="amount" 
                            step="any" 
                            min="11" 
                            minlength="2" 
                        />
                        <!-- <input type="text" class="form-control" name="amount" /> -->
                    </div>
                </div>
            </div>                        
        </div>

        <!-- Error -->
        <div class="">
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="payment-errors text-danger">
                        {{ (count($errors) > 0)?  $errors : '' }}
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="">
            <div class="col-xs-12">
                <button class=" btn btn-success btn-lg btn-block" type="submit">Pay</button>
            </div>
        </div>

    </form>
</div>