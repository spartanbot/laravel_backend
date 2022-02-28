<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\SendMailreset;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller{

    public function forgot_password(Request $request)
    {
        if(!$this->validateEmail($request->user_email)) {
            return $this->failedResponse();
        }

        $this->send($request->user_email);
        return $this->successResponse();
    }


    public function send($email)
    {
        $token = $this->createToken($email);
        Mail::to($email)->send(new SendMailreset($token, $email));
    }


    public function createToken($email)
    {

        $oldToken = User::where('user_email', $email)->first();
        $token = Str::random(50);

        if ($oldToken) {
            $this->saveToken($token,$email);
            return $token;

        }       
        
    }

    public function saveToken($token, $email){
        User::where('user_email',$email)->update([
            'token' => $token,
            'createdDate' => Carbon::now()
        ]);

    }


    
    public function validateEmail($email)
    {
        return !!User::where('user_email', $email)->first();
    }


    public function failedResponse()
    {
        return response()->json([
            'error' => "Email was not found in the Database"
        ], 404);
    }


    public function successResponse()
    {
        return response()->json([
            'data' => "Reset email link sent successfully, please check your inbox"
        ], 200);
    }

    public function reset_assword(Request $request,$token){

        $response=[];

        if($request['password'] ==''){
            $response['password']= 'password field is required';
        }

        if($request['c_password'] ==''){
            $response['c_password']= 'confirm field is required';
        }

        if($request['password'] !==$request['c_password']){
            $response['c_password']= 'password  and confirm password do not match';
        }

        if(count($response)){
            $response['error']= true;
            return response()->json($response);
          }else{

            try{

        $user_data=User::where('token','=',$token)->first();

        if($user_data){

        $users_update = User::where('user_email',$user_data->user_email)
        ->update([
        'password' => Hash::make($request->password)
        ]);

        return response()->json(['error'=>false,'msg'=>"password reset successfully!"]);

        }else{
         return response()->json(['error'=>true,'msg'=>"some thing went wrong!"]);
         }
         }catch(Exception $e){
            return "error";
        }

     }
    }
}

