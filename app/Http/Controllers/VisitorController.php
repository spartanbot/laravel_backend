<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
class VisitorController extends Controller
{
   public function createVisitor(Request $request){
      try{
        $get_result = Visitor::where('ip', '=', $request->ip)->first();
        if(!$get_result){
            $visitData = Visitor::create([
                'ip' => $request->ip,
                'browser_info' => serialize($request->browser_info)
            ]);
            if($visitData){
                return response()->json([
                    'status' => true,
                    'responce' => $visitData
                ], 200);
            }
        }
      }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
   }

   public function getTodayVisitor(Request $request){
    try{
        if($request->user['role'] == 'admin'){
           $visitorcount = [];
           $todayvisitor = Visitor::whereDate('created_at', '=', date('Y-m-d'))->get();
           $visitorcount['today_visitor'] = $todayvisitor->count();
            return response()->json([
                'success'=>true,
                'response'=> $visitorcount
            ],200);
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Only Admin can access!';
            return response()->json($response, 403);
        }
    }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
          }
   }
}
