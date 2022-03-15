<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\PaymentDetail;
use App\Models\Enrollment;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Exception;

class CartController extends Controller
{
    private $user;
    public $transaction_id;
    
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

    public function clearCartAfterCheckout(){
        try{
            if($this->user['role'] == 'seller' || 'user'){
                $clearCart = Cart::where('user_id','=',$this->user['id'])
                ->delete();
            if($clearCart){
                return response()->json([
                    'success'=>true,
                    'message'=>'Cart clear successfully!'
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
                $totalAmount = 20;
                $cartItems = Cart::where('user_id','=',$this->user['id'])->orderBy('id','desc')->get()->toArray();
                if($cartItems){
                    $stripeObj = new StripeController;
                    $payDetails = $stripeObj->stripeCharges($request->token,$totalAmount);
                    if($payDetails){
                            $Order = $this->createOrder($totalAmount);
                            if($Order){
                                // $paidAmount = $payDetails->amount_captured;
                                $this->transaction_id = $payDetails->balance_transaction;
                                if($Order->id){
                                    $Orderitems = $this->orderItemCreate($cartItems,$Order->id);
                                    if($Orderitems){
                                        $this->clearCartAfterCheckout();
                                        return response()->json([
                                            'success'=>true,
                                            'message'=>'Successfully enroll in this course!'
                                        ],200);
                                    }
                                }
                            }
                    }else{
                        $response['status'] = 'error';
                        $response['message'] = 'Something went wrong in payment.';
                        return response()->json($response, 403);
                    }
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Your Cart is currently empty.';
                    return response()->json($response, 403);
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

    public function createOrder($totalAmount){
        $OrderDetails  =  Order::create([
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
        return $OrderDetails;
    }

    public function orderItemCreate($allItem,$orderId){
        $transactionObj = new StripeController;
        $ItemData = array();
        foreach($allItem as $item){
            if($item['id'] && $item['course_name'] && $item['course_fee']){
                $OrderItem  =  OrderItems::create([
                    'user_id' => $this->user['id'],
                    'course_id' => $item['course_id'],
                    'order_id' => $orderId,
                    'course_name' => $item['course_name'],
                    'course_fee'=> $item['course_fee'],
                    'discount'=> 0
               ]);
               $ItemData['OrderItems'] = $OrderItem;
               $enrollments = $this->createEnrollment($item['course_id']);
                    if($this->transaction_id){
                            $transactionSave = $transactionObj->transaction($this->user['id'],$item['course_id'],$this->user['user_email'],$this->transaction_id,date('Y-m-d H:i:s'),date('Y-m-d H:i:s'));
                            if($transactionSave){
                                $ItemData['transaction'] = $transactionSave;
                            }else{
                                $response['status'] = 'error';
                                $response['message'] = "Can't insert transections items";
                                return response()->json($response, 403);
                            }
                    }else{
                        $response['status'] = 'error';
                        $response['message'] = "transaction id is not avilable";
                        return response()->json($response, 403);
                    }
                if($enrollments){
                    $ItemData['enrolments'] = $enrollments;
                }else{
                    $response['status'] = 'error';
                    $response['message'] = "Can't enrole on this item";
                    return response()->json($response, 403);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = "Can't created order items";
                return response()->json($response, 403);
            }
        }
        return $ItemData;
    }

    public function createEnrollment($course_id)
    {
        try{
            $enrollment = Enrollment::create([
                'user_id'=>$this->user['id'],
                'course_id'=>$course_id,
            ]);
            return $enrollment;
            }catch (Exception $e) {
                return $e;
            }
    }
}