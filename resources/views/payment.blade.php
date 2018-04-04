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
                
                        <ul class="nav nav-tabs justify-content-center">
                            <li class="nav-item">
                                <a href="#make-a-payment" class="nav-link active" data-toggle="tab" role="tab">Payment</a>
                            </li>
                            <li class="nav-item">
                                <a href="#recent-payments" class="nav-link" data-toggle="tab" role="tab">History</a>
                            </li>
                            <li class="nav-item">
                                <a href="#payment-settings" class="nav-link" data-toggle="tab" role="tab">Settings</a>
                            </li>
                            <li class="nav-item disabled">
                                <a href="#" class="nav-link" data-toggle="tab" role="tab">Disabled</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="make-a-payment" role="tabpanel" aria-labelledby="make-a-payment-tab">
                                @include('payment.make')

                            </div>

                            <div class="tab-pane" id="recent-payments" role="tabpanel" aria-labelledby="recent-payments-tab">
                                @include('payment.recent')
                                
                            </div>
                            
                            <div class="tab-pane" id="payment-settings" role="tabpanel" aria-labelledby="payment-settings-tab">
                                @include('payment.setting')
                                
                            </div>
                            
                            <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab">...</div>
                        </div>


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

    // show/hide personala and business option
    $('#radioBtn a').click(function(event) {
        var payment_type = $(this).data('title');
        $('.payby').addClass('hidden').fadeOut('fade', 0.5);
        $('.payby').find('input, select').removeAttr('required');

        $('.payby-'+ payment_type).removeClass('hidden').fadeIn('fade', 0.5);
        $('.payby-'+ payment_type).find('input, select').attr('required', true);
    });


    // Stripe payment integration
    Stripe.setPublishableKey('{{ config("services.stripe.key") }}');

    $(".payment-from").submit(function(e) {
        e.preventDefault();


        $form = $(this);
        $form.find('button').prop('disabled', true);

        var amount = $form.find('#amount').val();
        // console.log(amount);
        if (amount <= 10) {

            $form.find('.payment-errors').text("Amount must be at least $11.");
            // $form.find('button').prop('disabled', true);
            // return false;
        }/*else{
            $form.find('.payment-errors').text("");
            $form.find('button').prop('disabled', ture);
            // return false;
        }*/

        if($form.find('#paymentType').val() == 'card'){

            // async - Create token for Card
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
        
        }else {

            /*Stripe.bankAccount.createToken({
                country: $('#Country').val(),
                currency: $('#Currency').val(),
                routing_number: $('#RoutingNumber').val(),
                account_number: $('#AccountNumber').val(),
                account_holder_name: $('#Name').val(),
                account_holder_type: $('#AccountHolderType').val()
            }, stripeResponseHandler);*/

            // async - Create token for ACH/Bank Account
            Stripe.bankAccount.createToken($form, function(status, response){
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
        }
        return false;
    });

    //https://stripe.com/docs/stripe-js/v2#collecting-bank-account-details
    //https://jsfiddle.net/ywain/L2cefvtp/
    /*Stripe.bankAccount.createToken({
        country: $('.country').val(''),
        currency: $('.currency').val(),
        routing_number: $('.routing-number').val(),
        account_number: $('.account-number').val(),
        account_holder_name: $('.name').val(),
        account_holder_type: $('.account_holder_type').val()
    }, stripeResponseHandler);*/

    // ach - https://jsfiddle.net/ywain/L2cefvtp/
    /*var stripe = Stripe('pk_test_6pRNASCoBOKtIshFeQd4XMUh');

    function setOutcome(result) {
        var successElement = document.querySelector('.success');
        var errorElement = document.querySelector('.error');
        successElement.classList.remove('visible');
        errorElement.classList.remove('visible');

        if (result.token) {
            // In this example, we're simply displaying the token
            successElement.querySelector('.token').textContent = result.token.id;
            successElement.classList.add('visible');

            // In a real integration, you'd submit the form with the token to your backend server
            //var form = document.querySelector('form');
            //form.querySelector('input[name="token"]').setAttribute('value', result.token.id);
            //form.submit();
        } else {
            errorElement.textContent = result.error.message;
            errorElement.classList.add('visible');
        }
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        var bankAccountParams = {
            country: document.getElementById('country').value,
            currency: document.getElementById('currency').value,
            account_number: document.getElementById('account-number').value,
            account_holder_name: document.getElementById('account-holder-name').value,
            account_holder_type: document.getElementById('account-holder-type').value,
        }
        if (document.getElementById('routing-number').value != '') {
            bankAccountParams['routing_number'] = document.getElementById('routing-number').value;
        }

        stripe.createToken('bank_account', bankAccountParams).then(setOutcome);
    });*/



    
</script>
@endsection