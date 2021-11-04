<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\UserMetaData;
class UserAuth extends Controller
{
    public function _construct(){
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    public function register(Request $request){
        $user_meta_data = array();
       $user = User::Create(
           [
            'user_login' => $request->user_login,
            'user_email' => $request->user_email,
            'password' => Hash::make($request->password),
            'display_name'=> $request->display_name,
            'user_status'=> $request->user_status,
           ]
       );
       if($user){
        UserMetaData::add_meta($user['id'],'role',$request->role);
        $user_role = UserMetaData::get_meta_data($user['id'],'role');
        $user_meta_data['role'] = $user_role;
       }
       return response()->json(
        ['message' => 'User successfully register ',
        'user' => $user,
        'user_meta' => $user_meta_data
        ]
        ,201);
    }
    public function login(Request $request){
        $credentials = $request->only(['user_email', 'password']);

         $token = auth()->attempt($credentials);
         if($token){
            return response()->json([
                         'access_token' => $token,
                         'token_type' => 'bearer',
                         'expires_in' => auth()->factory()->getTTL() * 60
                     ]);
         }else{
            return response()->json(['error' => 'Unauthorized'], 401);
         }
    }
    public function logout(Request $request){
        try {
            Auth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

