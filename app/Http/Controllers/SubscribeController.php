<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Subscribe;

class SubscribeController extends Controller
{
    public function createSubscribe(Request $request){
        try{
            $user_data =Subscribe::where('user_email', '=', $request->user_email)->first();
            if($user_data){
                $response['status'] = 'error';
                $response['message'] = 'Email alreay Used!';
                return response()->json($response, 403);
            }else{
                $subscribe = Subscribe::create([
                    'user_email' => $request->user_email,
                ]);
                return response()->json([
                    'success'=>true,
                    'response'=> $subscribe
                ],200);
            }
        }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
        } 
    }
}
