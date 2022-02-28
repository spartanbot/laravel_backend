<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Models\UserBasicinfo;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserDashboardController extends Controller
{

public function createUserinfo(Request $request){

        $response=[];

        if($request['first_name'] ==''){
            $response['first_name']= 'Please enter first name';
        }

        if($request['last_name'] ==''){
            $response['last_name']= 'Please enter last name';
        }

        if($request['user_type'] ==''){
            $response['user_type']= 'Please enter user type';
        }
        if($request['change_password'] ==''){
            $response['change_password']= 'Please enter password';
        }
         
        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{
            try {
            $userbasicinfo = new User;
            $userbasicinfo->first_name= $request->first_name;
            $userbasicinfo->last_name= $request->last_name;
            $userbasicinfo->user_type= $request->user_type;
            $userbasicinfo->change_password= Hash::make($request->change_password);
            $userbasicinfo->save();
             return response()->json([
                'status' => true,
                'message' => 'User Basic Information created successfully!'
              
             ],201);
            } catch (Exception $e) {
                return "error";
            }

         }
}

public function updateUserinfo(Request $request){

        $response=[];

        if($request['full_name'] ==''){
            $response['full_name']= 'Please enter full name';
        }

        if($request['user_name'] ==''){
            $response['user_name']= 'Please enter user name';
        }

        if($request['description'] ==''){
            $response['description']= 'Please enter description';
        }
        if($request['password'] ==''){
            $response['password']= 'Please enter password';
        }
         
        if(count($response)){
            $response['error']= true;
            return response()->json($response,403);
         }else{
            try {
                $userinfo_update = User::where('id', $request->id)->update([
                'full_name' => $request->full_name,
                'user_name'=> $request->user_name,
                'description'=> $request->description,
                'password'=> Hash::make($request->password),
              ]);
            return response()->json([
            'status'=>true,
            'success'=>"User Basic Information updated successfully!"
           ],200);
            } catch (Exception $e) {
                return "error";
            }
            
         }

}

public function editUserinfo(Request $request,$id){
     try{
         $userinfo= User::where('id','=',$id)->where('user_status',1)->first();

         return response()->json([
            'success'=>true,
            'response'=>$userinfo
        ],200);

        }catch(Exception $e){
           return "error";
        }

} 


public function deleteUserinfo(Request $request,$id){

     try{
          $info_delete = User::where('id',$id)->update([
            'status' =>'0'
          ]);
         return response()->json([
            'error'=>false,
            'msg'=>"Delete recard successfully!"]);
        }catch(Exception $e){
         return "error";
        }

}

public function getAllUserinfo(Request $request){

     $userinfo=User::where('status','=','1')->orderBy('created_at','desc')->get()->toArray();
         if($userinfo){
            return response()->json(['error'=>false, 'response'=>$userinfo]);
         }else{
            return response()->json(['error'=>true, 'response'=>$userinfo]);
         }

    }
}
