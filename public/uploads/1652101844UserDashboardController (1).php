<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Models\UserBasicinfo;
use App\Models\User;
use App\Models\Product;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Enrollment;
use JWTAuth;
use DB; 
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

    public function allOrders(){
        try{
            if($this->user['role'] == 'user'){
                $fetchallOrder = DB::table('order')
                ->join('order_item', 'order_item.order_id', '=', 'order.id')
                ->join('course','course.id','=','order_item.course_id')
                ->join('users','users.id','=','order_item.seller_id')
                ->select('order.id','order.transaction_id','order.status','order.created_at','order.total','course.course_title','course.course_description','course.course_banner','users.full_name')
                ->get();
                if($fetchallOrder){
                    return response()->json([
                        'success'=>true,
                        'response'=>$fetchallOrder
                    ],200);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'somthing went wrong';
                    return response()->json($response, 403);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only User can use Fetch All Order';
                return response()->json($response, 403);
            }
        }catch(Exception $e) {
            return $e;
        }
    }


    public function AllMyProduct(){
        try{
            if($this->user['role'] == 'user'){
                $fetchallcourse = DB::table('enrollments')->where('user_id','=',$this->user['id'])
                ->join('course', 'course.id', '=', 'enrollments.course_id')
                ->join('users','users.id','=','course.seller_id')
                ->select('course.course_title','course.course_banner','course.course_description','course.course_fee','course.created_at','enrollments.course_id','users.full_name')
                ->get();
                
                if($fetchallcourse){
                    return response()->json([
                        'success'=>true,
                        'response'=>$fetchallcourse
                    ],200);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'somthing went wrong';
                    return response()->json($response, 403);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only User can use Fetch All Order';
                return response()->json($response, 403);
            }
        }catch(Exception $e) {
            return $e;
        }
    }



    // public function GetProduct(){
    //     $alldata = [];
    //     try{
    //     if($this->user['role'] == 'user'){
    //         $orderItems = [];
    //      $fetchallOrder = Enrollment::where('user_id','=',$this->user['id'])->select('course_id')->get();
    //     //   print_r($fetchallOrder);
    //     //   die();
    //       foreach($fetchallOrder as $course){
    //        $course = Course::where('id','=',$course['course_id'])
    //         ->select('course_title','course_banner','course_description','course_fee','created_at')                
    //         ->get();
    //         foreach($course as $coursedata)
    //         {
    //             $orderItems['course_title'] =  $coursedata['course_title'];
    //             $orderItems['course_banner'] =  $coursedata['course_banner'];
    //             $orderItems['course_description'] =  $coursedata['course_description'];
    //             $orderItems['course_fee'] =  $coursedata['course_fee'];
    //             $orderItems['created_at'] =  $coursedata['created_at'];
    //         }
    //         array_push($alldata,$orderItems);
    //       }
    //             return response()->json([
    //                 'success'=>true,
    //                 'response'=>$alldata
    //             ],200);
    //         }else{
    //             $response['status'] = 'error';
    //             $response['message'] = 'Only User can use Fetch All Order';
    //             return response()->json($response, 403);
    //         }
    //     }
    //     catch(Exception $e){
    //         return $e;
    //     }
    // }

    //   public function GetAllOrder(){
    //     $allOrder= [];
    //     try{
    //     if($this->user['role'] == 'user'){
    //     $orderItems = [];
    //     $fetchallOrder = Order::where('user_id','=',$this->user['id'])->select('transaction_id','status','id')->get();
    //     foreach($fetchallOrder as $order)
    //      {
    //         $Orderdata = DB::table('order_item')->where('order_id','=',$order->id)
    //         ->orderBy('id','asc')->select('course_id','created_at','course_fee')->get()->toArray();
    //         $orderItems['id'] = $order['id'];
    //         $orderItems['status'] =  $order['status'];
    //         $orderItems['transaction_id'] =$order['transaction_id'];
    //         foreach($Orderdata as $course)
    //         {
    //             $fetchCourse = Course::where('id','=',$course->course_id)
    //             ->select('course_banner','course_title','course_description','course_fee','seller_id')                
    //             ->get();
    //             foreach($fetchCourse as $dataItem)
    //             {
    //             $orderItems['course_banner'] = $dataItem['course_banner'];
    //             $orderItems['course_title'] = $dataItem['course_title'];
    //             $orderItems['course_description'] = $dataItem['course_description'];
    //             $AllUser = User::where('id','=',$dataItem['seller_id'])
    //             ->select('full_name')
    //             ->get();
    //             foreach($AllUser as $UserData)
    //             {
    //               $orderItems['full_name'] = $UserData['full_name'];
    //             } 
    //         }
    //         }
    //         array_push($allOrder,$orderItems);
    //      }
    //         return response()->json([
    //                 'success'=>true,
    //                 'response'=>$allOrder
    //             ],200);
    //         }else{
    //             $response['status'] = 'error';
    //             $response['message'] = 'Only User can use Fetch All Order';
    //             return response()->json($response, 403);
    //         }
    //     }
    //     catch(Exception $e) {
    //         return $e;
    //     }
    // }

}
