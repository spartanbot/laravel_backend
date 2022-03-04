<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function stripe()
    {
        return view('stripe');
    }

    public function stripePost(Request $request){
       $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
       $payment_id =  $request->json('payment_method_id');
       $intent = null;

       try {
         if (isset($payment_id)) {
           # Create the PaymentIntent
           $paymentIntentsData = $stripe->paymentIntents->create([
             'payment_method' => $payment_id,
             'amount' => 1099,
             'currency' => 'usd',
             'confirmation_method' => 'manual',
             'confirm' => true,
           ]);
         }
         if (isset($payment_id)) {
           $intent = $stripe->paymentIntents->retrieve(
            $payment_id,
            []
           );
           $intent->confirm();
         }
         generateResponse($intent);
       } catch (\Stripe\Exception\ApiErrorException $e) {
         # Display error on client
         echo json_encode([
           'error' => $e->getMessage()
         ]);
       }
    }

    function generateResponse($intent) {
        # Note that if your API version is before 2019-02-11, 'requires_action'
        # appears as 'requires_source_action'.
        if ($intent->status == 'requires_action' &&
            $intent->next_action->type == 'use_stripe_sdk') {
          # Tell the client to handle the action
          echo json_encode([
            'requires_action' => true,
            'payment_intent_client_secret' => $intent->client_secret
          ]);
        } else if ($intent->status == 'succeeded') {
          # The payment didn’t need any additional actions and completed!
          # Handle post-payment fulfillment
          echo json_encode([
            "success" => true
          ]);
        } else {
          # Invalid status
          http_response_code(500);
          echo json_encode(['error' => 'Invalid PaymentIntent status']);
        }
    }
  
}