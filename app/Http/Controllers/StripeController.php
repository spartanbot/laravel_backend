<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
   
  public function form()
    {
        return view('form');
    }

  public function stripeCharges($token,$totalAmount){
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    $payDetails = $stripe->charges->create([
        "amount" => $totalAmount*100,
        "currency" => "usd",
        "source" => $token
      ]);
      return $payDetails;
  }
}