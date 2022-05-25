<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\Navigation;
use Request;


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
}
