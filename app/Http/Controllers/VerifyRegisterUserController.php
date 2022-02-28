<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BasicInformation;
use Illuminate\Support\Facades\Hash;
use App\Models\VerifyUser;
use App\Http\Controllers\DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class VerifyRegisterUserController extends Controller{

	public function verifyUser($token){
		try{
		 $verifyUser = VerifyUser::where('token', $token)->first();

		 $link_expire =Date("H:i:s", strtotime("15 minutes", strtotime($verifyUser->createdDate)));
        if(strtotime($link_expire) < strtotime(date('H:i:s'))){
        	return response()->json([
              'url' => 'link is Expired']);

        }else{
          if(isset($verifyUser) ){
           $user = $verifyUser->user;
           if(!$user->verified) {
           $verifyUser->user->verified = 1;
           $verifyUser->user->save();
           $status = "";
           return response()->json([
              'message' => 'Your e-mail is verified. You can now login.',
              'user' => $user,],201);
          } else {
          return response()->json([
              'message' => 'Your e-mail is already verified. You can now login.',
              'user' => $user,],201);
          }

		 }else{
		 	return response()->json([
              'message' => 'Sorry your email cannot be identified.',
              ],201);
		 }
        }
           
	  }catch(Exception $e){
	  	return "error";
	  }		 
  }

}