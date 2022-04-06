<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use DB;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
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
            if($this->user['role'] = 'admin'){
            $allBuyer =  User::where('role','=','user')
            ->select('id','full_name','user_email')
            ->get();
            if($allBuyer){
                return response()->json([
                    'success'=>true,
                    'response'=>$allBuyer
                ],200);
            }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can fetch all buyers';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function viewUserProfile(Request $request){
        try{
            if($this->user['role'] = 'admin'){
            $profile = User::where('id','=',$request->id)->get();
                if($profile){
                    return response()->json([
                        'success'=>true,
                        'response'=>$profile
                    ],200);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'User does not exist';
                    return response()->json($response, 403);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can see user profile';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function userOrderHistory(Request $request){
        try{
            if($this->user['role'] = 'admin'){
                $response = [];
            $basic_info = User::where('id','=',$request->id)
            ->select('full_name','user_name','user_email','i_am_a')
            ->get();
        
            $order = Order::where('user_id','=',$request->id)
            ->select('id','created_at','status','total')
            ->get();
            if($basic_info && $order){
                $response['basic_info'] = $basic_info;
                $response['orderHistory'] = $order;
                return response()->json([
                    'success'=>true,
                    'response'=>$response
                ],200);
            }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can see user profile';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
                return $e;
        }
    }

    public function userOrderItem(Request $request){
      try{
        $all_order_items =[];
        $total_price =0;
        $orderItems = [];
            $Items = OrderItems::where('order_id','=',$request->order_id)
            ->select('course_id','created_at','course_fee')
            ->get()->toArray();
            foreach($Items as $item){
                $order = Order::where('id','=',$request->order_id)
                ->select('status','transaction_id')
                ->get()->toArray();
                $orderItems['course_fee'] = $item['course_fee'];
                $orderItems['created_at'] = $item['created_at'];
                $orderItems['status'] = $order[0]['status'];
                $orderItems['transaction_id'] = $order[0]['transaction_id'];
                 $fetch_course_data = Course::where('id','=',$item['course_id'])
                 ->select('course_banner','course_title','course_description')
                 ->get()->toArray();
                 $orderItems['course_banner'] = $fetch_course_data[0]['course_banner'];
                 $orderItems['course_title'] = $fetch_course_data[0]['course_title'];
                 $orderItems['course_description'] = $fetch_course_data[0]['course_description'];
                 $total_price += $item['course_fee'];
                 $all_order_items['total_price'] = $total_price;
                 array_push($all_order_items,$orderItems);
            }
            return response()->json([
                'success'=>true,
                'response'=>$all_order_items
            ],200);
      }catch(Exception $e){
                return $e;
        }
    }

    //seller
    public function fetchSellers(){
        try{
            if($this->user['role'] = 'admin'){
            $allsellers =  User::where('role','=','seller')
            ->select('id','full_name','user_email')
            ->get();
            if($allsellers){
                return response()->json([
                    'success'=>true,
                    'response'=>$allsellers
                ],200);
            }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can fetch all buyers';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            return $e;


        }
    }

    public function viewSellerProfile(Request $request){
        try{
            if($this->user['role'] = 'admin'){
            $profile = User::where('id','=',$request->id)
            ->where('role','=','seller')
            ->get();
                if(sizeof($profile)){
                    return response()->json([
                        'success'=>true,
                        'response'=>$profile
                    ],200);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Seller does not exist';
                    return response()->json($response, 403);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can see seller profile';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function sellerProducts(Request $request){
        try{
            $all_seller_prod = [];
            if($this->user['role'] = 'admin'){
            $fetch_products = Course::where('seller_id','=',$request->id)
            ->select('id','course_title','course_fee')
            ->get();
            $products = [];
            foreach($fetch_products as $product){
                $products['id'] = $product['id'];
                $products['course_title'] = $product['course_title'];
                $products['course_fee'] = $product['course_fee'];
                $enrolment_count = DB::table('enrollments')
                     ->select('course_id', DB::raw('count(*) as total'))
                     ->groupBy('course_id')
                     ->get();
                foreach($enrolment_count as $enroled){
                        if($product['id'] == $enroled->course_id){
                            $products['total_sale'] = $enroled->total;
                            $total_revenue = $product['course_fee'] * $enroled->total;
                            $products['total_revenue'] = $total_revenue;
                        }
                    }
                    array_push($all_seller_prod,$products);
            }
                if(sizeof($all_seller_prod)){
                    return response()->json([
                        'success'=>true,
                        'response'=>$all_seller_prod
                    ],200);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Products does not exist';
                    return response()->json($response, 403);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can see seller products';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function all_users(){
        try{
            if($this->user['role'] = 'admin'){
                $all_user = User::all();
                if(sizeof($all_user)){
                    return response()->json([
                        'success'=>true,
                        'response'=>$all_user
                    ],200);
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only Admin can see all users';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            return $e;
        }
    }
    
}
