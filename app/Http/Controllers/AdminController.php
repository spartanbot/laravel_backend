<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use DB;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Testimonal;
use App\Models\StripeKeys;
use App\Models\Subscribe;
use Carbon\Carbon;

class AdminController extends Controller
{
    private $user;
      
    // public function __construct(){
    //       $this->user = JWTAuth::parseToken()->authenticate();
    // }

    public function addStripeKey(Request $request){
        try{
            if($request->user['role'] == 'admin'){
               $stripeKeys = StripeKeys::create([
                    'publishable_key' => $request->publishable_key,
                    'secret_key' => $request->secret_key,
                ]);
                return response()->json([
                    'success'=>true,
                    'response'=> $stripeKeys
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

    public function editStripeKey(Request $request){
     try{
        if($request->user['role'] == 'admin'){
           $edit = StripeKeys::where('id',1)->select('id','publishable_key','secret_key')->get();
           return response()->json([
            'success'=>true,
            'response'=> $edit
           ],200);
        }
     }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
              }
    }

    public function updateStripeKeys(Request $request){
        try{
            if($request->user['role'] == 'admin'){
               $stripeKeys = StripeKeys::where('id', $request->id)->update([
                'publishable_key' => $request->publishable_key,
                'secret_key' => $request->secret_key,
            ]);
                return response()->json([
                    'success'=>true,
                    'response'=> $stripeKeys
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
    

    public function fetchAllOrders(Request $request){
        $allOrder = [];
        try{
            if($request->user['role'] == 'admin'){
                $fetchallOrder = DB::table('order')->whereBetween('created_at',array($request->start_date,$request->end_date))
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

    public function fetchAllproduct(Request $request){
        $allproducts = [];
        try{
            if($request->user['role'] == 'admin'){
                $fetchallcourse = DB::table('course')
                ->join('users', 'users.id', '=', 'course.seller_id')
                ->where('course.verify','=',1)
                ->select('course.*', 'users.full_name')
                ->get();
                $products = [];
                foreach($fetchallcourse as $product){
                    $products['product_name']=$product->course_title;
                    $products['product_price'] = $product->course_fee;
                    $products['created_at'] = $product->created_at;
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
            if($request->user['role'] == 'admin' || $request->user['role'] == 'seller' || $request->user['role'] == 'user'){
                $images = [];
            $single_course = DB::table('course')                 
                             ->select('id','course_title','course_description','course_fee','course_banner','course_content')
                             ->where('id','=',$request->id)
                             ->get();
                             foreach($single_course as $course){
                                $images = explode(",",$course->course_banner);
                                $course->course_banner = asset('/uploads/course_banner/'.$images[0]);
                                $course->course_content = asset('/uploads/'.$course->course_content);
                             }
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

    public function getSingleProductPage(Request $request){
        try{
            $images = [];
            $banners = [];
            $finalBannerImages = [];
            $single_course = DB::table('course')                 
                            ->where('course.id','=',$request->id)
                            ->join('users', 'users.id', '=', 'course.seller_id')
                            ->select('course.*', 'users.full_name as seller_name')
                            ->get();
                    foreach($single_course as $course){
                                $images = explode(",",$course->course_banner);
                                array_push($banners,$images);
                                $course->subject =  unserialize($course->subject);
                                $course->course_content = asset('/uploads/'.$course->course_content);
                             }
                    foreach($banners as $image){
                        foreach($image as $key => $img){
                            $img = asset('/uploads/course_banner/'.$img);
                            array_push($finalBannerImages,$img);
                        }
                    }
                  $single_course[0]->course_banner = $finalBannerImages;
                if($single_course){
                    return response()->json([
                        'success'=>true,
                        'response'=>$single_course
                    ],200);
                }
        }catch(Exception $e){
            return $e;
        }
    }

    public function fetchBuyers(Request $request){
        try{
            if($request->user['role'] == 'admin'){
            $allBuyer =  User::where('role','=','user')
            ->where('verified','=',1)
            ->select('id','full_name','user_email','created_at')
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
            if($request->user['role'] == 'admin'){
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
            if($request->user['role'] == 'admin'){
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
        $images = [];
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
                 $images = explode(",",$fetch_course_data[0]['course_banner']);
                 $orderItems['course_banner'] = asset('/uploads/course_banner/'.$images[0]);
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
    public function fetchSellers(Request $request){
      
        try{
            if($request->user['role'] == 'admin'){
            $allsellers =  User::where('role','=','seller')
            ->where('verified','=',1)
            ->select('id','full_name','user_email','created_at','user_profile','phone')
            ->get();
            foreach($allsellers as $seller){
                $seller->user_profile = asset('/uploads/'.$seller->user_profile);
            }
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
            if($request->user['role'] == 'admin'){
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
            //if($request->user['role'] == 'admin'){
            $fetch_products = Course::where('seller_id','=',$request->id)
            ->where('verify','=',1)
            ->join('users', 'users.id', '=', 'course.seller_id')
            ->select('course.id','course.course_title','course.course_fee','course.created_at','course.course_banner','users.full_name as seller_name')
            ->get();
            $products = [];
            foreach($fetch_products as $product){
                $products['id'] = $product['id'];
                $products['course_title'] = $product['course_title'];
                $products['course_fee'] = $product['course_fee'];
                $products['created_at'] = $product['created_at'];
                $products['seller_name'] = $product['seller_name'];
                $images = explode(",",$product['course_banner']);
                $products['course_banner'] = asset('/uploads/course_banner/'.$images[0]);
                $enrolment_count = DB::table('enrollments')
                     ->select('course_id', DB::raw('count(*) as total'))
                     ->groupBy('course_id')
                     ->get();
                foreach($enrolment_count as $enroled){
                    $getpoprating = DB::table('ratereview')->where('course_id',$product['id'])->get()->avg('rating');
                      if($getpoprating){
                        $products['product_rating'] = $getpoprating;
                       }else{
                        $products['product_rating'] = 0;
                       } 
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
            // }else{
            //     $response['status'] = 'error';
            //     $response['message'] = 'Only Admin can see seller products';
            //     return response()->json($response, 403);
            // }
        }catch(Exception $e){
            return $e;
        }
    }

    public function all_users(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $all_user = User::where('verified','=',1)->get();
                foreach($all_user as $user){
                    $user->user_profile = asset('/uploads/'.$user->user_profile);
                }
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

    public function unapproved_sellers(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $all_unapproved_user = User::where('role','=','seller')
                ->where('verified','=',1)
                ->where('approved_by_admin','=',0)
                ->select('id','full_name','user_name','user_email','created_at','user_profile','phone')
                ->get();
                foreach($all_unapproved_user as $user){
                    $user->user_profile = asset('/uploads/'.$user->user_profile);
                }
                if(sizeof($all_unapproved_user)){
                    return response()->json([
                        'success'=>true,
                        'response'=>$all_unapproved_user
                    ],200);
                }
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function approvedRequest(Request $request)
    {
        try{
            if($request->user['role'] == 'admin'){
                $Update_approved_qry = User::where('id', $request->id)->update([
                    'approved_by_admin' => 1,
                ]);
                if($Update_approved_qry){
                    return response()->json([
                        'success'=>true,
                        'response'=>"Seller approved successfully"
                    ],200);
                }
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function fetchApprovedSeller(Request $request){
         try{
            if($request->user['role'] == 'admin'){
                $all_approved_user = User::where('role','=','seller')
                ->where('verified','=',1)
                ->where('approved_by_admin','=',1)
                ->select('id','full_name','user_name','user_email','created_at','phone','user_profile')
                ->get();
                foreach($all_approved_user as $user){
                    $user->user_profile = asset('/uploads/'.$user->user_profile);
                }
                if(sizeof($all_approved_user)){
                    return response()->json([
                        'success'=>true,
                        'response'=>$all_approved_user
                    ],200);
                }
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function unapprovedRequest(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $Update_unapproved_qry = User::where('id', $request->id)->update([
                    'approved_by_admin' => 0,
                ]);
                if($Update_unapproved_qry){
                    return response()->json([
                        'success'=>true,
                        'response'=>"Seller is unapproved successfully"
                    ],200);
                }
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function allPayments(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $all_data = [];
                $all_products =  DB::table('course')->whereBetween('course.created_at',array($request->start_date,$request->end_date))
                ->where('verify','=',1)
                ->join('users', 'users.id', '=', 'course.seller_id')
                ->select('course.*', 'users.full_name')
                ->get();
                $products = [];
                foreach($all_products as $product){
                    $products['course_id']=$product->id;
                    $products['product_name']=$product->course_title;
                    $products['product_price'] = $product->course_fee;
                    $products['seller_name'] = $product->full_name;
                    $images = explode(",",$product->course_banner);
                    $products['course_banner'] = asset('/uploads/course_banner/'.$images[0]);
                    $enrolment_count = DB::table('enrollments')
                     ->select('course_id', DB::raw('count(*) as total'))
                     ->groupBy('course_id')
                     ->get();
                        foreach($enrolment_count as $enroled){
                            if($product->id == $enroled->course_id){
                                $products['qty'] = $enroled->total;
                                $total_revenue = $product->course_fee * $enroled->total;
                                //admin commision
                                $percentage = 30;
                                $totalamount = $total_revenue;
                                $transferAmount = ($percentage / 100) * $totalamount;
                                 //seller commision
                                 $percentageseller = 70;
                                 $transferAmountToSeller = ($percentageseller / 100) * $totalamount;
                                $products['total_revenue'] = $total_revenue;
                                $products['seller_commission'] = $transferAmountToSeller;
                                $products['total_commission'] = $transferAmount;
     
                            }
                        }
                    array_push($all_data,$products);
                }
                return response()->json([
                    'success'=>true,
                    'response'=>$all_data
                ],200);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function topSeller(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $all_data = [];
                $filtredData = [];
                $all_topSELL = OrderItems::select('seller_id', DB::raw('COUNT(seller_id) as count'))
                ->groupBy('seller_id')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get();
                $top_seller = [];
                foreach($all_topSELL as $top_sell){
                    $fetch_course_user = DB::table('users')->whereBetween('created_at',array($request->start_date,$request->end_date))
                    ->where('id','=',$top_sell['seller_id'])
                    ->where('verified','=',1)
                    ->select('full_name','user_email','id','created_at','phone','user_profile')
                    ->get();
                    foreach($fetch_course_user as $user){
                        $top_seller['id'] = $user->id;
                        $top_seller['user_email'] = $user->user_email;
                        $top_seller['full_name'] = $user->full_name;
                        $top_seller['user_profile'] = asset('/uploads/'.$user->user_profile);
                        $top_seller['phone'] = $user->phone;
                        $top_seller['created_at'] = $user->created_at;
                    }
                    array_push($all_data,$top_seller);
                }
                foreach($all_data as $key => $final_data){
                    
                    if(empty($final_data)){
                       unset($all_data[$key]);
                    }else{
                       array_push($filtredData,$final_data);
                    }  
                 }
                return response()->json([
                    'success'=>true,
                    'response'=>$filtredData
                ],200);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function oldtopSeller(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $all_data = [];
                $all_topSELL = OrderItems::select('course_id', DB::raw('COUNT(course_id) as count'))
                ->groupBy('course_id')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get();
                $top_seller = [];
                foreach($all_topSELL as $top_sell){
                    $top_seller['course_id'] = $top_sell['course_id'];
                    $fetch_course_user = DB::table('course')
                    ->join('users', 'users.id', '=', 'course.seller_id')
                    ->where('course.id','=',$top_sell['course_id'])
                    ->select('users.full_name','users.user_email','users.id','users.created_at')
                    ->get();
                    foreach($fetch_course_user as $user){
                        $top_seller['id'] = $user->id;
                        $top_seller['user_email'] = $user->user_email;
                        $top_seller['full_name'] = $user->full_name;
                        $top_seller['created_at'] = $user->created_at;
                    }
                    array_push($all_data,$top_seller);
                }
                return response()->json([
                    'success'=>true,
                    'response'=>$all_data
                ],200);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function topProducts(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $all_data = [];
                $filtredData = [];
                $all_topSELL = OrderItems::select('course_id', DB::raw('COUNT(course_id) as count'))
                ->groupBy('course_id')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get();
                $top_sell_product = [];
                foreach($all_topSELL as $top_sell){
                    $fetch_course = DB::table('course')->whereBetween('course.created_at',array($request->start_date,$request->end_date))
                    ->join('users', 'users.id', '=', 'course.seller_id')
                    ->where('course.id','=',$top_sell['course_id'])
                    ->where('verify','=',1)
                    ->select('course_title','course_fee','course_banner','course.created_at','users.full_name')
                    ->get();
                    foreach($fetch_course as $key1 =>  $course){
                        $getpoprating = DB::table('ratereview')->where('course_id',$top_sell['course_id'])->get()->avg('rating');
                      if($getpoprating){
                        $top_sell_product['product_rating'] = $getpoprating;
                       }else{
                        $top_sell_product['product_rating'] = 0;
                       } 
                       $images = explode(",",$course->course_banner);
                       $top_sell_product['course_banner'] = asset('/uploads/course_banner/'.$images[0]);
                        $top_sell_product['course_id'] = $top_sell['course_id'];
                        $top_sell_product['total_sales'] = $top_sell['count'];
                        $top_sell_product['product_title'] = $course->course_title;
                        $top_sell_product['product_fee'] = $course->course_fee;
                        $top_sell_product['created_at'] = $course->created_at;
                        $top_sell_product['seller'] = $course->full_name;
                        $top_sell_product['revenue'] = $course->course_fee * $top_sell->count;
                    }
                    array_push($all_data,$top_sell_product);
                }
                 foreach($all_data as $key => $final_data){
                    
                    if(empty($final_data)){
                       unset($all_data[$key]);
                    }else{
                       array_push($filtredData,$final_data);
                    }  
                 }
                return response()->json([
                    'success'=>true,
                    'response'=>$filtredData
                ],200);
            }
        }catch(Exception $e){
            return $e;
        }
    }

    public function orderToday(Request $request)
    {
        try{
            if($request->user['role'] == 'admin'){
            $response=[];
            $ordertoday = DB::table('order')
            ->where(DB::raw("(DATE_FORMAT(order.created_at,'%Y-%m-%d'))"),'=',date('Y-m-d'))
            ->get();
            $count = $ordertoday->count();
            $response['TodayOrder']= $count;
            return response()->json([
                'success'=>true,
                'response'=> $response
            ],200);
            }else{
                $data['status']= 'error';
                $data['error']= 400;
                $data['result']='only admin can access!';
                return response()->json($data);
            }
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }

    }

    public function todaySales(Request $request)
    {
        try{
            if($request->user['role'] == 'admin')
            {
                $response=[];
                $todaySeller = DB::table('order')
                ->where(DB::raw("(DATE_FORMAT(order.created_at,'%Y-%m-%d'))"),'=',date('Y-m-d'))
                ->get();
                $sum= $todaySeller->sum('total');
                $response['today_sales'] = $sum;
                return response()->json([
                    'success'=>true,
                    'response'=> $response
                ],200);
            }else{
                $data['status']= 'error';
                $data['error']= 400;
                $data['result']='only admin can access!';
                return response()->json($data);
            }
            
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }

    public function totalRevenue(Request $request)
    {
        try{
            if($request->user['role'] == 'admin')
            {
                $response=[];
                $totalrevenue = Order::all();
                $sum = $totalrevenue->sum('total');
                $response['total_revenue'] = $sum;
                return response()->json([
                    'success'=>true,
                    'response'=> $response
                ],200);
            }else{
                $data['status'] = 'error';
                $data['error'] = 400;
                $data['result'] ='only admin can access!';
                return response()->json($data);
            }
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }
//graph
    public function getSellersCountByMonth(Request $request){
        try{
            if($request->user['role'] == 'admin')
            {
                $users = User::where('role','seller')
                    ->whereBetween('created_at',array($request->start_date,$request->end_date))
                    ->get()
                    ->groupBy(function ($date){
                        return Carbon::parse($date->created_at)->format('m');
                    });

                $usermcount = [];
                $userArr = [];

                foreach ($users as $key => $value) {
                    $usermcount[(int)$key] = count($value);
                }

                $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                for ($i = 1; $i <= 12; $i++) {
                    if (!empty($usermcount[$i])) {
                        $userArr[$i]['count'] = $usermcount[$i];
                    } else {
                        $userArr[$i]['count'] = 0;
                    }
                    $userArr[$i]['month'] = $month[$i - 1];
                }

                return response()->json(array_values($userArr),200);
            }
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }
   //graph
    public function getUserCountByMonth(Request $request){
        try{
            if($request->user['role'] == 'admin')
            {
                $users = User::where('role','user')
                     ->whereBetween('created_at',array($request->start_date,$request->end_date))
                    ->get()
                    ->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('m');
                    });

                $usermcount = [];
                $userArr = [];

                foreach ($users as $key => $value) {
                    $usermcount[(int)$key] = count($value);
                }

                $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                for ($i = 1; $i <= 12; $i++) {
                    if (!empty($usermcount[$i])) {
                        $userArr[$i]['count'] = $usermcount[$i];
                    } else {
                        $userArr[$i]['count'] = 0;
                    }
                    $userArr[$i]['month'] = $month[$i - 1];
                }

                return response()->json(array_values($userArr),200);
            }
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }

    public function deleteUser(Request $request)
    {
        try{
            $ids = $request->ids;
            $delete = User::whereIn('id',$ids)->update([
                'verified' => 0, 
            ]); 
            if($delete){
                return response()->json([
                    'success'=>true,
                    'response'=> 'User deleted !'
                ],200);
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }

    public function OrderDeleteAction(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $ids = $request->order_ids;
                $orderItems = OrderItems::whereIn('order_id',$ids)->delete();
                if($orderItems){
                    $order = Order::whereIn('id',$ids)->delete();
                    if($order){
                        return response()->json([
                            'success'=>true,
                            'response'=> 'Order deleted !'
                        ],200);
                    }
                }else{
                    $orderstatus = Order::whereIn('id',$ids)->delete();
                    if($orderstatus){
                        return response()->json([
                            'success'=>true,
                            'response'=> 'Order deleted !'
                        ],200);
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

    public function productDelete(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $ids = $request->ids;
                $product = Course::whereIn('id',$ids)->update([
                    'verify' => 0, 
                ]); 
                if($product){
                    return response()->json([
                        'success'=>true,
                        'response'=> 'Product deleted !'
                    ],200);
                }
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }

    public function categoryDelete(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $ids = $request->order_ids;
                $category = Category::whereIn('id',$ids)->delete();
                if($category){
                    return response()->json([
                        'success'=>true,
                        'response'=> 'Category deleted !'
                    ],200);
                }
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }

    public function testimonialDelete(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $ids = $request->order_ids;
                $testimonal = Testimonal::whereIn('id',$ids)->delete();
                if($testimonal){
                    return response()->json([
                        'success'=>true,
                        'response'=> 'Testimonal deleted !'
                    ],200);
                }
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }
    
         //graph
    public function saleOverview(Request $request){
        try{
            // Carbon::setWeekStartsAt(Carbon::SUNDAY);
            // Carbon::setWeekEndsAt(Carbon::SATURDAY);
            //Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
            if($request->user['role'] == 'admin'){
                $byweek = Order::select(DB::raw("(COUNT(*)) as count"),DB::raw("DAYNAME(created_at) as dayname"))
                ->whereBetween('created_at', [$request->start_date,$request->end_date])
                //->whereMonth('created_at', date('M'))
                ->groupBy('dayname')
                ->get();
               $sumoforder =  Order::whereBetween('created_at', [$request->start_date,$request->end_date])
                ->sum('total');
                return response()->json([
                    'success'=>true,
                    'response'=> $byweek,
                    'week_earning' => $sumoforder
                ],200);
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }
    //graph
    public function orderOverview(Request $request){
        try{
            if($request->user['role'] == 'admin'){
                $byweek = Order::select(DB::raw("(COUNT(*)) as count"),DB::raw("DATE_FORMAT(created_at,  '%d-%b') as date"))
                ->whereBetween('created_at', [$request->start_date,$request->end_date])
                ->groupBy('date')
                ->get();
                return response()->json([
                    'success'=>true,
                    'response'=> $byweek
                ],200);
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }

    //graph
    public function getSubscriberCountByMonth(Request $request){
        try{
            if($request->user['role'] == 'admin')
            {
                $users = Subscribe::whereBetween('created_at',array($request->start_date,$request->end_date))
                    ->get()
                    ->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('m');
                    });

                $usermcount = [];
                $userArr = [];

                foreach ($users as $key => $value) {
                    $usermcount[(int)$key] = count($value);
                }

                $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                for ($i = 1; $i <= 12; $i++) {
                    if (!empty($usermcount[$i])) {
                        $userArr[$i]['count'] = $usermcount[$i];
                    } else {
                        $userArr[$i]['count'] = 0;
                    }
                    $userArr[$i]['month'] = $month[$i - 1];
                }

                return response()->json(array_values($userArr),200);
            }
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }

    public function resourseCounting(Request $request){
        try{
            if($request->user['role'] == 'admin')
            {
                $users = Course::whereBetween('created_at',array($request->start_date,$request->end_date))
                    ->get()
                    ->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('m');
                    });

                $usermcount = [];
                $userArr = [];

                foreach ($users as $key => $value) {
                    $usermcount[(int)$key] = count($value);
                }

                $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                for ($i = 1; $i <= 12; $i++) {
                    if (!empty($usermcount[$i])) {
                        $userArr[$i]['count'] = $usermcount[$i];
                    } else {
                        $userArr[$i]['count'] = 0;
                    }
                    $userArr[$i]['month'] = $month[$i - 1];
                }

                return response()->json(array_values($userArr),200); 
            }
        }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
            }
    }

    public function todayOrder(Request $request){
        try{
            if($request->user['role'] == 'admin')
            {
                //Get all data for the day
                // $all_data = Order::whereDate('created_at',date('Y-m-d'))
                // ->select(DB::raw("(COUNT(*)) as count"),DB::raw("DATE_FORMAT(created_at,  '%h %i') as hourly"))
                // ->groupBy('hourly')
                // ->get();
                // return response()->json([
                //     'success'=>true,
                //     'response'=> $all_data
                // ],200);

                // $all_data = Order::whereDate('created_at',date('Y-m-d'))
                // ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), DB::raw('count(*) as applications'))
                // ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                // ->get();
                // return response()->json([
                //         'success'=>true,
                //         'response'=> $all_data
                //     ],200);

                // $all_data = Order::whereDate('created_at',date('Y-m-d'))->get();
                // //Recursive to groupBy hours
                // $i=1;
                // while ($all_data->last() != null){
                //     $hourly_data = Order::where('created_at','=',Carbon::today()->addHours($i))->get();
                //     $all_data= $all_data->merge($hourly_data);
                // $i++;
                // }
                // return response()->json($all_data,200);

                    $today_Order_time=DB::table('order')
                      ->where(DB::raw("(DATE_FORMAT(order.created_at,'%Y-%m-%d'))"),'=',date('Y-m-d'))
                      ->select('created_at',DB::raw('hour(created_at) as time')
                      ,DB::raw('COUNT(*) as count'))->groupBy('time','created_at')->get();
                      $newdata=array();
                      foreach($today_Order_time as $valdata)
                      {
                          $newdata[]=['time'=>date('h:i:a',strtotime($valdata->created_at)),
                          'count'=> $valdata->count];
                      }
                      return response()->json([
                          'success'=>true,
                          'response'=>$newdata
                      ],200);
            }
        }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
            }
    }

    public function TotalOrderCountByMonth(Request $request){
        try{
            if($request->user['role'] == 'admin')
            {
                $users = OrderItems::whereBetween('created_at',array($request->start_date,$request->end_date))
                    ->get()
                    ->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('m');
                    });
                    $total_sales = OrderItems::whereBetween('created_at',array($request->start_date,$request->end_date))
                                   ->sum('course_fee');
  
                $usermcount = [];
                $userArr = [];
  
                foreach ($users as $key => $value) {
                    $usermcount[(int)$key] = count($value);
                }
  
                $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  
                for ($i = 1; $i <= 12; $i++) {
                    if (!empty($usermcount[$i])) {
                        $userArr[$i]['count'] = $usermcount[$i];
                    } else {
                        $userArr[$i]['count'] = 0;
                    }
                    $userArr[$i]['month'] = $month[$i - 1];
                }
                $userArr[]['total_sales'] = $total_sales;
                return response()->json(array_values($userArr),200);
            }
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        }
    }
  
}
