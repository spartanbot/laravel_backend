<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentDetail;

class PaymentController extends Controller
{


    public function createPayment(Request $request){

        $response=[];

        if($request['email'] ==''){
            $response['email']= 'Please enter email';
        }

        if($request['cart_details'] ==''){
            $response['cart_details']= 'Please enter cart details';
        }

        if($request['cart_holder_name'] ==''){
            $response['cart_holder_name']= 'Please enter cart holder name';
        }

        if($request['billing_address'] ==''){
            $response['billing_address']= 'Please enter billing address';
        }
        if($request['state'] ==''){
            $response['state']= 'Please enter state';
        }
        if($request['zip'] ==''){
            $response['zip']= 'Please enter zip code';
        }

        if($request['vat_number'] ==''){
            $response['vat_number']= 'Please enter vat number';
        }
        if($request['discount_code'] ==''){
            $response['discount_code']= 'Please enter discount code';
        }

        if($request['amount'] ==''){
            $response['amount']= 'Please enter amount';
        }



        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{

            $paymentdetail = new PaymentDetail;
            $paymentdetail->email = $request->email;
            $paymentdetail->cart_details = $request->cart_details;
            $paymentdetail->cart_holder_name = $request->cart_holder_name;
            $paymentdetail->billing_address = $request->billing_address;
            $paymentdetail->state = $request->state;
            $paymentdetail->zip = $request->zip;
            $paymentdetail->vat_number = $request->vat_number;
            $paymentdetail->discount_code = $request->discount_code;
            $paymentdetail->amount = $request->amount;
            $paymentdetail->save();
            return response()->json([
                'status' => true,
                'message' => 'payment created successfully!'
              
             ],201);
         }

    }

    public function updatePayment(Request $request){

        $response=[];

        if($request['email'] ==''){
            $response['email']= 'Please enter email';
        }

        if($request['cart_details'] ==''){
            $response['cart_details']= 'Please enter cart details';
        }

        if($request['cart_holder_name'] ==''){
            $response['cart_holder_name']= 'Please enter cart holder name';
        }

        if($request['billing_address'] ==''){
            $response['billing_address']= 'Please enter billing address';
        }
        if($request['state'] ==''){
            $response['state']= 'Please enter state';
        }
        if($request['zip'] ==''){
            $response['zip']= 'Please enter zip code';
        }

        if($request['vat_number'] ==''){
            $response['vat_number']= 'Please enter vat number';
        }
        if($request['discount_code'] ==''){
            $response['discount_code']= 'Please enter discount code';
        }

        if($request['amount'] ==''){
            $response['amount']= 'Please enter amount';
        }



        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{

            $payment_update = PaymentDetail::where('id', $request->id)->update([
                'email' => $request->email,
                'cart_details'=> $request->cart_details,
                'cart_holder_name'=> $request->cart_holder_name,
                'billing_address'=> $request->billing_address,
                'state'=> $request->state,
                'zip'=> $request->zip,
                'vat_number'=> $request->vat_number,
                'discount_code'=> $request->discount_code,
                'amount'=> $request->amount
              ]);
            return response()->json([
            'error'=>false,
            'msg'=>"Payment updated successfully!"
           ]);

         }

    }

    public function editPayment(Request $request,$id){

        try{
         $paymentdetails= PaymentDetail::where('id','=',$id)->where('status',1)->first();

         return response()->json([
            'error'=>false,
            'response'=>$paymentdetails
        ]);

        }catch(Exception $e){
           return "error";
        }

    }

    public function deletePayment(Request $request,$id){

        try{
          $payment_delete = PaymentDetail::where('id',$id)->update([
            'status' =>'0'
          ]);
         return response()->json([
            'error'=>false,
            'msg'=>"Delete recard successfully!"]);
        }catch(Exception $e){
         return "error";
        }

    }

    public function allPaymentDetails(Request $request){

     $paymentdetails=PaymentDetail::where('status','=','1')->orderBy('created_at','desc')->get()->toArray();
         if($paymentdetails){
            return response()->json(['error'=>false, 'response'=>$paymentdetails]);
         }else{
            return response()->json(['error'=>true, 'response'=>$paymentdetails]);
         }

    }
       
}
