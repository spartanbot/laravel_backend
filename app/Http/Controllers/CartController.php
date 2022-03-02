<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
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
            if($this->user['role'] == 'seller'){
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
                    $response['message'] = 'Only seller can use cart';
                    return response()->json($response, 403);
            }
        }catch (Exception $e) {
            return $e;
        }
    }

    public function cartItem(Request $request){
        try{
            if($this->user['role'] == 'seller'){
                $cartItem = Cart::where('user_id','=',$this->user['id'])->orderBy('id','desc')->get()->toArray();
            if($cartItem){
                return response()->json([
                    'success'=>true,
                    'response'=>$cartItem
                ],200);
            }
            }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Only seller can use fetch cart item';
                    return response()->json($response, 403);
            }
        }catch (Exception $e) {
            return $e;
        }
    }

    public function cartDelete(Request $request){
        try{
            if($this->user['role'] == 'seller'){
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
                    $response['message'] = 'Only seller can use fetch cart item';
                    return response()->json($response, 403);
            }
        }catch (Exception $e) {
            return $e;
        }
    }
}