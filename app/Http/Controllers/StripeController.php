<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\StripeKeys;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class StripeController extends Controller
{

  public function form()
    {
        return view('form');
    }

  public function stripeCharges($token,$totalAmount){
      //stripe api key
      $stripe_key = new StripeController();
      $skey = $stripe_key->fetchStripeSecretKeys();

      $stripe = new \Stripe\StripeClient($skey);
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
    //stripe api key
    $stripe_key = new StripeController();
    $skey = $stripe_key->fetchStripeSecretKeys();

    \Stripe\Stripe::setApiKey($skey);
    $transfer = \Stripe\Transfer::create([
      "amount" => $amount*100,
      "currency" => "usd",
      "source_transaction" => $charge_id,
      "destination" => $account_id,
    ]);

  }


  public function fetchStripePublishKeys(){
    try{
      $Pkey = StripeKeys::select('publishable_key')->get()->toArray();
      return response()->json([
        'success'=>true,
        'response'=> $Pkey
      ],200);
    }catch(Exception $e){
      $error = $e->getMessage();
      $response['status'] = 'error';
      $response['message'] = $error;
      return response()->json($response, 403);
    }
  }

  public function fetchStripeSecretKeys(){
    try{
      $Pkey = StripeKeys::select('secret_key')->get();
      return $Pkey[0]['secret_key'];
    }catch(Exception $e){
      $error = $e->getMessage();
      $response['status'] = 'error';
      $response['message'] = $error;
      return response()->json($response, 403);
    }
  }

}