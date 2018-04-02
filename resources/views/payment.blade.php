@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">{{ $page_title }}</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <form action="{{url('checkout')}}" method="post" class="form-horizental col-xs-12 col-md-7 col-md-offset-3 payment-from">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            
                            <div class="form-group">
                                <label for="" class="col-xs-12 col-md-3">Type</label>
                                <div class="input-group col-xs-12 col-md-8">
                                    <div id="radioBtn" class="btn-group col-xs-12 col-md-7">
                                        <a class="btn btn-success btn-sm text-bold active" data-toggle="paymentType" data-title="card" tabindex="1">Card</a>
                                        <a class="btn btn-success btn-sm text-bold notActive" data-toggle="paymentType" data-title="ach">ACH</a>
                                    </div>
                                    <input type="hidden" name="paymentType" id="paymentType" value="card">
                                </div>
                            </div>

                            <!-- Payment Card Option -->
                            <div class="payby payby-card">
                                <div class="row">
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
                                <div class="row">
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
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div class="form-group">
                                            <label for="routingNumber">ROUTING NUMBER</label>
                                            <div class="input-group">
                                                <input 
                                                type="tel"
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

                                <div class="row">

                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="AccountType">ACCOUNT TYPE</label>
                                            <div class="input-group">
                                                <select class="form-control" name="AccountType" placeholder="Account Type">
                                                    <option value="checking">Checking</option>
                                                    <option value="savings">Savings</option>
                                                    <option value="current">Current</option>
                                                </select>
                                                <span class="input-group-addon"><i class="fa fa-bars"></i></span>
                                            </div>
                                        </div>                            
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="couponCode">AMOUNT</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="number" stripe-data="amount" class="form-control" name="amount" id="amount" step="any" min="11" minlength="2" />
                                            <!-- <input type="text" class="form-control" name="amount" /> -->
                                        </div>
                                    </div>
                                </div>                        
                            </div>

                            <!-- Error -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="payment-errors text-danger">
                                            {{ (count($errors) > 0)?  $errors : '' }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <button class=" btn btn-success btn-lg btn-block" type="submit">Pay</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsscript')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">

    Stripe.setPublishableKey('{{ config("services.stripe.key") }}');

    $(".payment-from").submit(function(e) {
        e.preventDefault();


        $form = $(this);
        $form.find('button').prop('disabled', true);

        var amount = $form.find('#amount').val();
        // console.log(amount);
        if (amount <= 10) {

            $form.find('.payment-errors').text("Amount must be at least $11.");
            $form.find('button').prop('disabled', true);
            return false;
        }/*else{
            $form.find('.payment-errors').text("");
            $form.find('button').prop('disabled', ture);
            return false;
        }*/

        // async
        Stripe.card.createToken($form, function(status, response){
            console.log(status);
            console.log(response);

            if (response.error) {
                $form.find('.payment-errors').text(response.error.message);
                $form.find('button').prop('disabled', false);
            } else {
                var token = response.id;

                //append the token and submit
                $form.append($('<input type="hidden" name="stripe_token" />').val(token));

                $form.get(0).submit();
            }
        } );

        return false;
    });


    // show/hide personala and business option
    $('#radioBtn a').click(function(event) {
        var payment_type = $(this).data('title');
        $('.payby').addClass('hidden').fadeOut('fade', 0.5);
        $('.payby-'+ payment_type).removeClass('hidden').fadeIn('fade', 0.5);
    });
</script>
@endsection