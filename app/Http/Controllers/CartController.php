<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\PaymentDetail;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Exception;

class CartController extends Controller
{
    private $user;
    
    public function __construct(){
         $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function cartAdd(Request $request){
        try{
            if($this->user['role'] == 'seller' || 'user'){
                $cartItem  =  Cart::create([
                    'user_id' => $this->user['id'],
                    'course_id' => $request->course_id,
                    'course_name' => $request->course_name,
                    'course_fee' => $request->course_fee
            ]);
            if($cartItem){
                return response()->json([
                    'status' => true,
                    'message' => 'Cart added successfully!'
                ], 200);
            }
            }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Only seller, User can use cart';
                    return response()->json($response, 403);
            }
        }catch (Exception $e) {
            return $e;
        }
    }

    public function cartItem(Request $request){
        try{
            if($this->user['role'] == 'seller' || 'user'){
                $cartItem = Cart::where('user_id','=',$this->user['id'])->orderBy('id','desc')->get()->toArray();
            if($cartItem){
                return response()->json([
                    'success'=>true,
                    'response'=>$cartItem
                ],200);
            }
            }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Only seller, User can use fetch cart item';
                    return response()->json($response, 403);
            }
        }catch (Exception $e) {
            return $e;
        }
    }

    public function cartDelete(Request $request){
        try{
            if($this->user['role'] == 'seller' || 'user'){
                $deleteCart = Cart::where('user_id','=',$this->user['id'])
                ->where('course_id','=',$request->course_id)
                ->delete();
            if($deleteCart){
                return response()->json([
                    'success'=>true,
                    'message'=>'Item delete successfully!'
                ],200);
            }
            }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Only seller, User can use fetch cart item';
                    return response()->json($response, 403);
            }
        }catch (Exception $e) {
            return $e;
        }
    }

    public function checkoutOrder(Request $request){
        try{
            if($this->user['role'] == 'seller' || 'user'){
                // $paymetDetails  =  PaymentDetail::create([
                //     'email' => $this->user['user_email'],
                //     'user_id' => $this->user['id'],
                //     'card_number' => $request->card_number,
                //     'expiration' => $request->expiration,
                //     'cart_holder_name' => $request->cart_holder_name,
                // ]);
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $token = $stripe->tokens->create([
                    'card' => [
                      'number' => '4242424242424242',
                      'exp_month' => 3,
                      'exp_year' => 2023,
                      'cvc' => '314',
                    ],
                  ]);
                  $customer = $stripe->customers->create(array(
                    'name' => $this->user['full_name'],
                    'description' => 'test description',
                    'email' => $this->user['user_email'],
                    'source'  => $token->id,
                    //"address" => ["city" => $customerCity, "country" => $customerCountry, "line1" => $customerAddress, "line2" => "", "postal_code" => $customerZipcode, "state" => $customerState]
                    )); 
                  if($customer){
                    $payDetails = $stripe->charges->create([
                        'customer' => $customer->id,
                        'amount' => 2000,
                        'currency' => 'usd',
                        //'source' => $token->id,
                        "metadata" => ["order_id" => "6735"],
                        'description' => 'My First Test Charge (created for API docs)',
                      ]);
                  }
                  print_r($payDetails);
                  die();
                //$stripe = Stripe::setApiKey(env('STRIPE_SECRET'));

                // $token = $stripe->tokens()->create([
                //     'card' => [
                //     'number' => $request->card_number,
                //     'exp_month' => $request->exp_mohth,
                //     'exp_year' => $request->exp_year,
                //     'cvc' => $request->cvv,
                //     ],
                //     ]);
                // print_r($token);
                // die();

                $totalAmount = 0;
                $cartItems = Cart::where('user_id','=',$this->user['id'])->orderBy('id','desc')->get()->toArray();
               foreach($cartItems as $itme){
                $totalAmount += $itme['course_fee'];
                    $Order  =  Order::create([
                            'user_id' => $this->user['id'],
                            'status' => 1,
                            'subtotal' => $totalAmount,
                            'total' => $totalAmount,
                            'promo'=> 'NA',
                            'discount'=> 0,
                            'grandtotal'=> $totalAmount,
                            'fullname'=> $this->user['full_name'],
                            'email'=> $this->user['user_email'] 
                    ]);
               }
                echo $totalAmount;
                die();
            
            // if($cartItem){
            //     return response()->json([
            //         'status' => true,
            //         'message' => 'Cart added successfully!'
            //     ], 200);
            // }
            }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Only seller, User can use cart';
                    return response()->json($response, 403);
            }
        }catch (Exception $e) {
            return $e;
        }
    }
}