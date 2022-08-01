<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Navigation;
use JWTAuth;

class NavigationController extends Controller
{
    public function createNotification($uid,$seller_id,$message,$type){
       try{
        $notification  =  Navigation::create([
            'user_id' => $uid,
            'seller_id' => $seller_id,
            'message' => $message,
            'type'=> $type,
        ]);
       }catch (Exception $e) {
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
        }
    }

    public function seller_notification(Request $request){
       try{
        if($request->user['role'] == 'seller' || $request->user['role'] == 'user'){
            $get_notifi = Navigation::whereBetween('created_at',array($request->start_date,$request->end_date))
            ->where('seller_id','=',$request->user['id'])
            ->where('type','=','Enrol')
            ->select('message','created_at')
            ->orderBy('created_at','desc')->get();
            if($get_notifi){
                return response()->json([
                    'status' => true,
                    'response' => $get_notifi
                ], 200);
            }
        }
       }catch (Exception $e) {
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
        }
    }

    public function get_user_notification(Request $request){
        try{
            if($request->user['role'] == 'user' || $request->user['role'] == 'seller'){
                $get_notifi = Navigation::where('type','=','ProductCreated')
                ->select('message','created_at')
                ->orderBy('created_at','desc')->get();
                if($get_notifi){
                    return response()->json([
                        'status' => true,
                        'response' => $get_notifi
                    ], 200);
                }
            }
        }catch (Exception $e) {
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
        }
    }
}
