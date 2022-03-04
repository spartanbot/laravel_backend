<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 - Stripe Payment Gateway Integration Example - ItSolutionStuff.com</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style type="text/css">
        /* .panel-title {
        display: inline;
        font-weight: bold;
        }
        .display-table {
            display: table;
        }
        .display-tr {
            display: table-row;
        }
        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        } */

        /**
* Shows how you can use CSS to style your Element's container.
* These classes are added to your Stripe Element by default.
* You can override these classNames by using the options passed
* to the `card` elemenent.
* https://stripe.com/docs/js/elements_object/create_element?type=card#elements_create-options-classes
*/

.StripeElement {
  height: 40px;
  padding: 10px 12px;
  width: 100%;
  color: #32325d;
  background-color: white;
  border: 1px solid transparent;
  border-radius: 4px;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
    </style>
</head>
<body>
  
<div class="container">
  
    <h1>Laravel 8 - Stripe Payment Gateway Integration Example <br/> ItSolutionStuff.com</h1>
  
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <div class="row display-tr" >
                        <h3 class="panel-title display-td" >Payment Details</h3>
                        <div class="display-td" >                            
                            <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                        </div>
                    </div>                    
                </div>
                <div class="panel-body">
  
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
  
                    <form 
                            role="form" 
                            action="{{ route('stripe.post') }}" 
                            method="post" 
                            class="require-validation"
                            data-cc-on-file="false"
                            data-stripe-publishable-key="{{ env('STRIPE_PUBLIC') }}"
                            id="payment-form">
                        @csrf
                        
                        <!-- <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name on Card</label> <input
                                    class='form-control' size='4' type='text'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group card required'>
                                <label class='control-label'>Card Number</label> <input
                                    autocomplete='off' class='form-control card-number' size='20'
                                    type='text'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                <label class='control-label'>CVC</label> <input autocomplete='off'
                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Month</label> <input
                                    class='form-control card-expiry-month' placeholder='MM' size='2'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Year</label> <input
                                    class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                    type='text'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-md-12 error form-group hide'>
                                <div class='alert-danger alert'>Please correct the errors and try
                                    again.</div>
                            </div>
                        </div>
  
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now ($100)</button>
                            </div>
                        </div> -->
                        <label>
                            Card details
                            <!-- placeholder for Elements -->
                            <div id="card-element"></div>
                        </label>
                        <button type="submit">Submit Payment</button>
                    </form>
                </div>
            </div>        
        </div>
    </div>
      
</div>
  
</body>
  
<script src="https://js.stripe.com/v3/"></script>
  
<script type="text/javascript">
$(function() {
    const csrfToken = document.head.querySelector("[name~=csrf-token][content]").content;
   var stripe = Stripe('pk_test_51KZ7TgSBpjF5m6ySU9CS17525h0XbalifngZ1n5pvC6qOwkKKy1Kk2NlkEy6stcFLtdYVdJBVUeCnxusMOxmDYi800s4YEfHJ4');
   var elements = stripe.elements();
    
    // Set up Stripe.js and Elements to use in checkout form
    var style = {
    base: {
        color: "#32325d",
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
        color: "#aab7c4"
        }
    },
    invalid: {
        color: "#fa755a",
        iconColor: "#fa755a"
    },
    };

   var cardElement = elements.create('card', {style: style});
   cardElement.mount('#card-element');

   var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
    // We don't want to let default form submission happen here,
    // which would refresh the page.
    event.preventDefault();

    stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
        billing_details: {
        // Include any additional collected billing details.
        name: 'Jenny Rosen',
        },
    }).then(stripePaymentMethodHandler);
    });

    function stripePaymentMethodHandler(result) {
        if (result.error) {
            console.log(result.error);
            // Show error in payment form
        } else {
            // Otherwise send paymentMethod.id to your server (see Step 4)
            fetch('/stripe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json',"X-CSRF-Token": csrfToken },
            body: JSON.stringify({
                payment_method_id: result.paymentMethod.id,
            })
            }).then(function(result) {
                console.log(result)
            // Handle server response (see Step 4)
            // result.json().then(function(json) {
            //     handleServerResponse(json);
            // })
            });
        }
    }
  
  });
</script>
</html>