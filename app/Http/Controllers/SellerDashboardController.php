<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\SellerAccounts;
use JWTAuth;
use DB;

class SellerDashboardController extends Controller
{

   private $user;
      
   public function __construct(){
         $this->user = JWTAuth::parseToken()->authenticate();
   }

   public function addBankAccount(Request $request){
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
      $response=[];

      if($request['account_holder_name'] ==''){
          $response['account_holder_name']= 'Please Enter account holder name';
      }

      if($request['routing_number'] ==''){
        $response['routing_number']= 'Please Enter routing number';
      }

      if($request['account_number'] ==''){
        $response['account_number']= 'Please Enter account number';
      }

      if($request['line1'] ==''){
        $response['line1']= 'Please Enter address line1';
      }

      if($request['city'] ==''){
        $response['city']= 'Please Enter city';
      }

      if($request['state'] ==''){
        $response['state']= 'Please Enter state';
      }

      if($request['postal_code'] ==''){
        $response['postal_code']= 'Please Enter postal code';
      }

      if($request['dob'] ==''){
        $response['dob']= 'Please Enter Date of birth(day/month/year)';
      }

      if($request['first_name'] ==''){
        $response['first_name']= 'Please Enter First name';
      }

      if($request['last_name'] ==''){
        $response['last_name']= 'Please Enter Last name';
      }

      if($request['gender'] ==''){
        $response['gender']= 'Please Enter gender';
      }

      if($request['phone'] ==''){
        $response['phone']= 'Please Enter phone number';
      }

      if($request['ssn_last_4'] ==''){
        $response['ssn_last_4']= 'Please Enter social security number last 4 digit';
      }
      if(count($response)){
        $response['error']= true;
        return response()->json($response);
     }else{
            try{
              $selleraccount = SellerAccounts::where('user_id','=',$this->user['id'])->get();
              if(sizeof($selleraccount)){
                $response['status'] = 'error';
                $response['message'] = 'You Have already account on stripe. Please contact your Admin ';
                return response()->json($response, 403);

              }else{

              $dob = $request->dob;
              $date = explode('/', $dob);

              // first create bank token
              $bankToken =  \Stripe\Token::create([
                'bank_account' => [
                    'country' => 'US',
                    'currency' => 'usd',
                    'account_holder_name' => $request->account_holder_name,
                    'account_holder_type' => 'individual',
                    'routing_number' => $request->routing_number,
                    'account_number' => $request->account_number
                ]
              ]); 

              // second create stripe account
              $stripeAccount = \Stripe\Account::create([
                "type" => "custom",
                "country" => "US",
                "email" => $this->user['user_email'],   
                "business_type" => "individual",
                'capabilities' => [
                  'card_payments' => ['requested' => true],
                  'transfers' => ['requested' => true],
                ],
                "individual" => [
                    'address' => [

                        'city' => $request->city,
                        'line1' => $request->line1,
                        'state'=> $request->state,
                        'postal_code' => $request->postal_code,            
                    ],
                    'dob'=>[
                        "day" => $date[0],
                        "month" => $date[1],
                        "year" => $date[2]
                    ],
                    "email" => $this->user['user_email'],
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                    "gender" => $request->gender,
                    "phone"=> $request->phone,
                    "ssn_last_4"=> $request->ssn_last_4,
                ]     
              ]);
              // third link the bank account with the stripe account
              $bankAccount = \Stripe\Account::createExternalAccount(
              $stripeAccount->id,['external_account' => $bankToken->id]
              );
              // Fourth stripe account update for tos acceptance
              \Stripe\Account::update(
                $stripeAccount->id,[
                'tos_acceptance' => [
                      'date' => time(),
                      'ip' => $_SERVER['REMOTE_ADDR'] // Assumes you're not using a proxy
                    ],
                ]
              );
              $response = ["bankToken"=>$bankToken->id,"stripeAccount"=>$stripeAccount->id,"bankAccount"=>$bankAccount->id];
              if($bankToken->id && $stripeAccount->id && $bankAccount->id){
                  $accountDetails  =  SellerAccounts::create([
                    'user_id' => $this->user['id'],
                    'bankToken' => $bankToken->id ,
                    'stripeAccount' => $stripeAccount->id ,
                    'bankAccount' => $bankAccount->id ,
                    //'status',
                ]);
                if($accountDetails){
                  return response()->json([
                    'success'=>true,
                    'responce'=>$response
                  ],200);
                }
              }
            }
        }catch(Exception $e){
          $error = $e->getMessage();
          $response['status'] = 'error';
          $response['message'] = $error;
          return response()->json($response, 403);
        }
     }
   }
   

}
