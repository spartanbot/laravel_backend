<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Models\UserBasicinfo;
use App\Models\User;
use App\Models\OrderItems;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserDashboardController extends Controller
{
  private $user;
      
   public function __construct(){
         $this->user = JWTAuth::parseToken()->authenticate();
   }

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

    public function buyerProfile(){
        try{
          if($this->user['role'] = 'user'){
            $user = User::where('id','=',$this->user['id'])
            ->get();
            if(sizeof($user)){
              return response()->json([
                'success'=>true,
                'response'=>$user
              ],200);
            }else{
                  $response['status'] = 'error';
                  $response['message'] = 'User does not exist';
                  return response()->json($response, 403);
            }
          }
        }catch(Exception $e){
                  $error = $e->getMessage();
                  $response['status'] = 'error';
                  $response['message'] = $error;
                  return response()->json($response, 403);
                }
      }

        public function updateProfile(Request $request){
          try{
            if($this->user['role'] = 'user'){
              $update_info =  User::where('user_email',$this->user['user_email'])->update([
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'phone' => $request->phone
              ]);
            }
            if($update_info){
              $data['status']= 'success';
              $data['result']='Successfull update info';
              return response()->json($data,200);
            }
           }catch(Exception $e){
                    $error = $e->getMessage();
                    $response['status'] = 'error';
                    $response['message'] = $error;
                    return response()->json($response, 403);
                  }
        }
  
        public function changePassword(Request $request){
          try{
            if($this->user['role'] = 'user'){
              $response=[];
    
            if($request['current_password'] ==''){
                $response['current_password']= 'Current password field is required';
            }
    
            if($request['password'] ==''){
                $response['password']= 'password field is required';
            }
    
            if($request['confirm_password'] ==''){
              $response['confirm_password']= 'confirm password field is required';
            }
             if(count($response)){
                $data['status']= 'error';
                $data['error']= 403;
                $data['result']=$response;
                return response()->json($data);
             }else{
              $credentials = $request->only($this->user['user_email'], $request->password);
              $user_data =User::where('user_email','=',$this->user['user_email'])->first();
               if($user_data){
                $pass = Hash::check($request->current_password, $user_data->password);
                if ($pass) {
                    $update_password =  User::where('user_email',$this->user['user_email'])->update([
                        'password' => Hash::make($request->password),
                        'updated_at' => Carbon::now()
                      ]);
                      if($update_password){
                              $data['status']= 'success';
                              $data['result']='Successfull change Password, Please Login with new password';
                            return response()->json($data,200);
                      }else{
                        $data['status']= 'error';
                        $data['error']= 400;
                        $data['result']='Password is incorrect';
                        return response()->json($data);
                      }
                  } else {
                    $data['status']= 'error';
                    $data['error']= 400;
                    $data['result']='Password is incorrect';
                    return response()->json($data);
                  }
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
