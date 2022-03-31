<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use DB;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Course;

class AdminController extends Controller
{
    private $user;
      
    public function __construct(){
          $this->user = JWTAuth::parseToken()->authenticate();
    }
    

    public function fetchAllOrders(){
        $allOrder = [];
        try{
            if($this->user['role'] = 'admin'){
                $fetchallOrder = DB::table('order')
                ->orderBy('id','asc')->get()->toArray();
                $orderdata =[];
                foreach($fetchallOrder as $order){
                    $orderdata['order_id'] = $order->id;
                    $orderdata['user_id'] = $order->user_id;
                    $orderdata['status'] = $order->status;
                    $orderdata['total'] = $order->total;
                    $orderdata['fullname'] = $order->fullname;
                    $orderdata['created_at'] = $order->created_at;
                    $orderdata['total'];
                    // fetch orderItems 
                    $orderdata['orderItems']=[];
                    $fetchOrderItem = DB::table('order_item')
                    ->where('order_id','=',$order->id)
                    ->orderBy('id','asc')->get()->toArray();
                    $orderItems = [];
                    $total= 0;
                    foreach($fetchOrderItem as $orderitem){
                        $orderItems['course_id'] = $orderitem->course_id;
                        $orderItems['course_name'] = $orderitem->course_name;
                        $orderItems['course_fee'] = $orderitem->course_fee;
                        $total += $orderitem->course_fee;
                        $orderdata['total'] = $total;
                        array_push($orderdata['orderItems'],$orderItems);
                    }
                    array_push($allOrder,$orderdata);
                }
                return response()->json([
                    'success'=>true,
                    'response'=>$allOrder
                ],200);

            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can use Fetch All Order';
                return response()->json($response, 403);
            }
            
        }catch(Exception $e) {
            return $e;
        }
    }

    public function fetchAllproduct(){
        $allproducts = [];
        try{
            if($this->user['role'] = 'admin'){
                $fetchallcourse = DB::table('course')
                ->join('users', 'users.id', '=', 'course.seller_id')
                ->select('course.*', 'users.full_name')
                ->get();
                $products = [];
                foreach($fetchallcourse as $product){
                    $products['product_name']=$product->course_title;
                    $products['product_price'] = $product->course_fee;
                    $products['seller_name'] = $product->full_name;

                    $enrolment_count = DB::table('enrollments')
                     ->select('course_id', DB::raw('count(*) as total'))
                     ->groupBy('course_id')
                     ->get();
                        foreach($enrolment_count as $enroled){
                            if($product->id == $enroled->course_id){
                                $products['total_sale'] = $enroled->total;
                                $total_revenue = $product->course_fee * $enroled->total;
                                $products['total_revenue'] = $total_revenue;
                            }
                        }
                    array_push($allproducts,$products);
                }
                return response()->json([
                    'success'=>true,
                    'response'=>$allproducts
                ],200);
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can use Fetch All Order';
                return response()->json($response, 403);
            }
            
        }catch(Exception $e) {
            return $e;
        }
    }

    public function fetchAllResourse(){
        $allproducts = [];
        
    }

    public function singleProductPage(Request $request){
        try{
            if($this->user['role'] = 'admin'){
            $single_course = DB::table('course')                 
                             ->select('id','course_title','course_description','course_fee','course_banner','course_content')
                             ->where('id','=',$request->id)
                             ->get();
                if($single_course){
                    return response()->json([
                        'success'=>true,
                        'response'=>$single_course
                    ],200);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can open this course';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function fetchBuyers(){
        try{

        }catch(Exception $e){
            return $e;
        }
    }

    

}
