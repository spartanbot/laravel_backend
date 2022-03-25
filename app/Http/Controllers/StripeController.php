<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use JWTAuth;

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

  public function transaction($uID,$courseId,$email,$transaction_id,$created_at,$updated_at){
      $TransactionDetails  =  Transaction::create([
        'user_id' => $uID,
        'course_id' => $courseId,
        'email' => $email,
        'transaction_id' => $transaction_id,
        'created_at'=> $created_at,
        'updated_at'=> $updated_at
    ]);
    return $TransactionDetails;
  }


  public function transferToSeller($amount,$account_id,$charge_id){
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    $transfer = \Stripe\Transfer::create([
      "amount" => $amount*100,
      "currency" => "usd",
      "source_transaction" => $charge_id,
      "destination" => $account_id,
    ]);

  }

}