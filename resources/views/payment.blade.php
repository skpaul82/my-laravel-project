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
                                <a href="#recent-payments" class="nav-link" data-toggle="tab" role="tab" onclick="getTabContent('payment-history', 'recent-payments')">History</a>
                            </li>
                            <li class="nav-item">
                                <a href="#payment-settings" class="nav-link" data-toggle="tab" role="tab" onclick="getTabContent('payment-setting', 'payment-settings')">Settings</a>
                            </li>
                            <li class="nav-item disabled">
                                <a href="#" class="nav-link" data-toggle="tab" role="tab" onclick="getTabContent(path, loader)">Disabled</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="make-a-payment" role="tabpanel" aria-labelledby="make-a-payment-tab">

                                @include('payment.make-payment')
                            </div>

                            <div class="tab-pane" id="recent-payments" role="tabpanel" aria-labelledby="recent-payments-tab">

                                <div class="magnifier preloader hidden"></div>
                                {{-- @include('payment.charges') --}}
                            </div>
                            
                            <div class="tab-pane" id="payment-settings" role="tabpanel" aria-labelledby="payment-settings-tab">
                                
                                <div class="magnifier preloader hidden"></div>
                                {{-- @include('payment.setting') --}}
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
    
    $(function (){
        // load payment form on page load
        // getTabContent('make-payment', 'make-a-payment');  
    });

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

        // Get the form object.
        $form = $(this);
        // Disable the submit button to prevent repeated clicks
        $form.find('button').prop('disabled', true);

        var amount = $form.find('#amount').val();
        
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

</script>
@endsection