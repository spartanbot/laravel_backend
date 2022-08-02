<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Seller;
use App\Models\SellerAccounts;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use App\Models\Course;
use App\Models\Ratereview;
use App\Models\Visitor;
use Auth;
use JWTAuth;
use DB;

class SellerDashboardController extends Controller
{

   private $user;
      
   public function __construct(){
         $this->user = JWTAuth::parseToken()->authenticate();
   }

   public function addBankAccount(Request $request){
     //get stript key
      $stripe_key = new StripeController();
      $skey = $stripe_key->fetchStripeSecretKeys();
      
      //stripe 
      \Stripe\Stripe::setApiKey($skey);
      $response=[];

      if($request['account_holder_name'] ==''){
          $response['account_holder_name']= 'Please Enter account holder name';
      }

      if($request['routing_number'] ==''){
        $response['routing_number']= 'Please Enter routing number';
      }

      if($request['account_number'] ==''){
        $response['account_number']= 'Please Enter account number';
      }

      if($request['line1'] ==''){
        $response['line1']= 'Please Enter address line1';
      }

      if($request['city'] ==''){
        $response['city']= 'Please Enter city';
      }

      if($request['state'] ==''){
        $response['state']= 'Please Enter state';
      }

      if($request['postal_code'] ==''){
        $response['postal_code']= 'Please Enter postal code';
      }

      if($request['dob'] ==''){
        $response['dob']= 'Please Enter Date of birth(day/month/year)';
      }

      if($request['first_name'] ==''){
        $response['first_name']= 'Please Enter First name';
      }

      if($request['last_name'] ==''){
        $response['last_name']= 'Please Enter Last name';
      }

      if($request['gender'] ==''){
        $response['gender']= 'Please Enter gender';
      }

      if($request['phone'] ==''){
        $response['phone']= 'Please Enter phone number';
      }

      if($request['ssn_last_4'] ==''){
        $response['ssn_last_4']= 'Please Enter social security number last 4 digit';
      }
      if(count($response)){
        $response['error']= true;
        return response()->json($response);
     }else{
            try{
              $selleraccount = SellerAccounts::where('user_id','=',$this->user['id'])->get();
              if(sizeof($selleraccount)){
                $response['status'] = 'error';
                $response['message'] = 'You Have already account on stripe. Please contact your Admin ';
                return response()->json($response, 403);

              }else{

              $dob = $request->dob;
              $date = explode('/', $dob);

              // first create bank token
              $bankToken =  \Stripe\Token::create([
                'bank_account' => [
                    'country' => 'US',
                    'currency' => 'usd',
                    'account_holder_name' => $request->account_holder_name,
                    'account_holder_type' => 'individual',
                    'routing_number' => $request->routing_number,
                    'account_number' => $request->account_number
                ]
              ]); 

              // second create stripe account
              $stripeAccount = \Stripe\Account::create([
                "type" => "custom",
                "country" => "US",
                "email" => $this->user['user_email'],   
                "business_type" => "individual",
                'capabilities' => [
                  'card_payments' => ['requested' => true],
                  'transfers' => ['requested' => true],
                ],
                "individual" => [
                    'address' => [

                        'city' => $request->city,
                        'line1' => $request->line1,
                        'state'=> $request->state,
                        'postal_code' => $request->postal_code,            
                    ],
                    'dob'=>[
                        "day" => $date[0],
                        "month" => $date[1],
                        "year" => $date[2]
                    ],
                    "email" => $this->user['user_email'],
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                    "gender" => $request->gender,
                    "phone"=> $request->phone,
                    "ssn_last_4"=> $request->ssn_last_4,
                ]     
              ]);
              // third link the bank account with the stripe account
              $bankAccount = \Stripe\Account::createExternalAccount(
              $stripeAccount->id,['external_account' => $bankToken->id]
              );
              // Fourth stripe account update for tos acceptance
              \Stripe\Account::update(
                $stripeAccount->id,[
                'tos_acceptance' => [
                      'date' => time(),
                      'ip' => $_SERVER['REMOTE_ADDR'] // Assumes you're not using a proxy
                    ],
                ]
              );
              $response = ["bankToken"=>$bankToken->id,"stripeAccount"=>$stripeAccount->id,"bankAccount"=>$bankAccount->id];
              if($bankToken->id && $stripeAccount->id && $bankAccount->id){
                  $accountDetails  =  SellerAccounts::create([
                    'user_id' => $this->user['id'],
                    'bankToken' => $bankToken->id ,
                    'stripeAccount' => $stripeAccount->id ,
                    'bankAccount' => $bankAccount->id ,
                    //'status',
                ]);
                if($accountDetails){
                  return response()->json([
                    'success'=>true,
                    'responce'=>$response
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
   }

   public function sellerProductDelete(Request $request){
    try{
        if($request->user['role'] == 'seller' || $request->user['role'] == 'user'){
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

public function sellerOrderDeleteAction(Request $request){
  try{
      if($request->user['role'] == 'seller' || $request->user['role'] == 'user'){
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
   
   public function topProducts(Request $request){
     try{
      if($this->user['role'] = 'seller' || $this->user['role'] == 'user'){
        $all_data = [];
        $finalData = [];
        $fetch_course = DB::table('course')->whereBetween('course.created_at',array($request->start_date,$request->end_date))
            ->where('seller_id','=',$this->user['id'])
            ->where('verify','=',1)
            ->select('id','course_title','course_fee')
            ->get();
          $top_sell_product = [];
          foreach($fetch_course as $top_sell){
            $top_sell_product['id'] = $top_sell->id;
            $top_sell_product['course_title'] = $top_sell->course_title;
            $top_sell_product['course_fee'] = $top_sell->course_fee;
            $getpoprating = DB::table('ratereview')->where('course_id',$top_sell->id)->get()->avg('rating');
              if($getpoprating){
                  $top_sell_product['product_rating'] = $getpoprating;
                }else{
                  $top_sell_product['product_rating'] = 0;
                }
            $all_topSELL = OrderItems::where('course_id','=',$top_sell->id)
            ->select('course_id', DB::raw('COUNT(course_id) as count'))
            ->groupBy('course_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();
            foreach($all_topSELL as $sell_item){
                $top_sell_product['total_sale'] = $sell_item->count;
                $top_sell_product['total_revenue'] = $top_sell->course_fee * $sell_item->count;
            }
            array_push($all_data,$top_sell_product);
          }
          foreach($all_data as $data){
                   if(empty($data['total_sale'])){
                    $data['total_sale'] = 0;
                    $data['total_revenue'] = 0;
                   }
            array_push($finalData,$data);    
          }
          return response()->json([
            'success'=>true,
            'response'=>$finalData
            ],200);
      }
     }catch(Exception $e){
          $error = $e->getMessage();
          $response['status'] = 'error';
          $response['message'] = $error;
          return response()->json($response, 403);
        }
   }

  public function fetchSellerOrder(Request $request){
      try{
        $all_data = [];
        if($this->user['role'] = 'seller' || $this->user['role'] == 'user' ){
          $orderd_item = OrderItems::whereBetween('order_item.created_at',array($request->start_date,$request->end_date))
          ->where('seller_id','=',$this->user['id'])
          ->join('users', 'users.id','=','user_id')
          ->select('order_id','course_id','user_id','users.full_name')
          ->get();
          $all_order = [];
          foreach($orderd_item as $items){
                   $orders = Order::where('id','=',$items['order_id']) 
                   ->select('created_at','status','total')
                   ->get();
                   foreach($orders as $order){
                    $all_order['order_id'] = $items->order_id;
                    $all_order['customer'] = $items->full_name;
                    $all_order['created_at'] = $order->created_at;
                    $all_order['status'] = $order->status;
                    $all_order['total'] = $order->total;
                   }
            array_push($all_data,$all_order);
          }
          return response()->json([
            'success'=>true,
            'response'=>$all_data
            ],200);
        }
      }catch(Exception $e){
        $error = $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $error;
        return response()->json($response, 403);
      }
  }

    public function fetchOrderItems(Request $request){
      try{
        $all_data = [];
        $images = [];
        if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
          $orderd_item = OrderItems::where('order_id','=',$request->order_id)
          ->join('users', 'users.id','=','user_id')
          ->join('course','course.id','=','course_id')
          ->select('order_id','course_id','user_id','users.full_name','users.user_profile','course.course_title','course.course_description','course.course_banner','course.course_fee')
          ->get();
          $all_orderItems = [];
          $total_price=0;
          foreach($orderd_item as $item){
            $images = explode(",",$item->course_banner);
            $item->course_banner = asset('/uploads/course_banner/'.$images[0]);
            $item->user_profile = asset('/uploads/'.$item->user_profile);
            $all_orderItems = $item;
            $total_price += $item['course_fee'];
            array_push($all_data,$all_orderItems);
          }
          $all_orderItems['total_price'] = $total_price;
          return response()->json([
            'success'=>true,
            'response'=>$all_data
            ],200);
        }
      }catch(Exception $e){
          $error = $e->getMessage();
          $response['status'] = 'error';
          $response['message'] = $error;
          return response()->json($response, 403);
        }
    }

    public function getSellerProducts(Request $request){
      try{
        $all_data = [];
        if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
          $get_products = Course::whereBetween('course.created_at',array($request->start_date,$request->end_date))
          ->where('seller_id','=',$this->user['id'])
          ->where('verify','=',1)
          ->select('id','course_title','subject','category_id','course_banner','created_at','course_fee')
          ->get();
          foreach($get_products as $products){
            $products->subject =  unserialize($products->subject);
            $images = explode(",",$products->course_banner);
            $products->course_banner = asset('/uploads/course_banner/'.$images[0]);
            array_push($all_data,$products);
          }
          return response()->json([
            'success'=>true,
            'response'=>$all_data
            ],200);
        }
      }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
              }
    }

    public function soldProducts(Request $request){
      try{
        $all_seller_prod = [];
        $final_data = [];
        if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
        $fetch_products = Course::whereBetween('course.created_at',array($request->start_date,$request->end_date))
        ->where('seller_id','=',$this->user['id'])
        ->where('verify','=',1)
        ->select('id','course_title','course_fee','course_banner')
        ->get();
        $products = [];
        foreach($fetch_products as $product){
            $enrolment_count = DB::table('enrollments')
                 ->select('course_id', DB::raw('count(*) as total'))
                 ->groupBy('course_id')
                 ->get();
            foreach($enrolment_count as $enroled){
                    if($product['id'] == $enroled->course_id){
                        $products['id'] = $product['id'];
                        $products['course_title'] = $product['course_title'];
                        $products['course_fee'] = $product['course_fee'];
                        $products['total_sale'] = $enroled->total;
                        $total_revenue = $product['course_fee'] * $enroled->total;
                        $products['total_revenue'] = $total_revenue;
                        $images = explode(",",$product->course_banner);
                        $products['course_banner'] = asset('/uploads/course_banner/'.$images[0]);
                        $getpoprating = DB::table('ratereview')->where('course_id',$product->id)->get()->avg('rating');
                            if($getpoprating){
                                $products['product_rating'] = $getpoprating;
                              }else{
                                $products['product_rating'] = 0;
                              }
                    }
                }
                array_push($all_seller_prod,$products);
              }
              foreach($all_seller_prod as $seller_products){
                if(!empty($seller_products)){
                    array_push($final_data,$seller_products);
                }
               }
                return response()->json([
                    'success'=>true,
                    'response'=>$all_seller_prod
                ],200);
            }
      }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
              }
    }

    public function allBuyer(Request $request){
      try{
        $all_data = [];
        if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
          $all_buyer = OrderItems::whereBetween('order_item.created_at',array($request->start_date,$request->end_date))
          ->where('seller_id','=',$this->user['id'])
          ->select('user_id')
          ->get();
          $all_user = [];
          foreach($all_buyer as $key => $buyer){
           $user = User::where('id','=',$buyer->user_id)
            ->where('verified','=',1)
            ->select('id','full_name','user_email','phone','user_profile')
            ->get();
            foreach($user as $udata){
              $udata->user_profile = asset('/uploads/'.$udata->user_profile);
              $all_user = $udata;
              array_push($all_data,$all_user);
            }
          }
        }
        if(sizeof($all_data)){
          return response()->json([
              'success'=>true,
              'response'=>$all_data
          ],200);
        }else{
          return response()->json([
            'success'=>true,
            'response'=>'User Not found'
          ],403);
        }
      }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
              }
    }

    public function viewProfile(Request $request){
           try{
            if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
              $get_user = User::where('id','=',$request->user_id)
              ->where('role','=','user')
              ->get();
              if(sizeof($get_user)){
                return response()->json([
                  'success'=>true,
                  'response'=>$get_user
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

    public function fetchUserOrders(Request $request){
           try{
            if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
              $response = [];
              $basic_info = User::where('id','=',$request->user_id)
              ->select('full_name','user_name','user_email','i_am_a')
              ->get();
              $orderItem = OrderItems::where('user_id','=',$request->user_id)
              ->select('order_id',DB::raw('COUNT(order_id) as item'))
              ->groupBy('order_id')->get();
               $data = [];
              foreach($orderItem as $item){
                $orders = Order::whereBetween('order.created_at',array($request->start_date,$request->end_date))
                ->where('id','=',$item->order_id)
                ->select('id','created_at','status','total')
                ->get();
                foreach($orders as $order){
                  $order->item = $item->item;
                  array_push($data,$order);
                }
              }
              if($basic_info){
                  $response['basic_info'] = $basic_info;
                  $response['orderHistory'] = $data;
                  return response()->json([
                      'success'=>true,
                      'response'=>$response
                  ],200);
              }
              if(sizeof($orders)){
                return response()->json([
                  'success'=>true,
                  'response'=>$orders
                ],200);
              }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Order does not exist';
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

    public function user_OrderItems(Request $request){
      try{
        $all_order_items =[];
        $total_price =0;
        $orderItems = [];
        $images = [];
            $Items = OrderItems::whereBetween('order_item.created_at',array($request->start_date,$request->end_date))
            ->where('order_id','=',$request->order_id)
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
    public function sellerProfile(){
      try{
        if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
          $user = User::where('id','=',$this->user['id'])
          ->get(); 
          foreach($user as $data){
            $data->user_profile = asset('/uploads/'.$data->user_profile);
          }
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

    // public function updateBasicInfo(Request $request){
    //    try{
    //     if($this->user['role'] = 'seller'){
    //       $update_info =  User::where('user_email',$this->user['user_email'])->update([
    //         'full_name' => $request->full_name,
    //         'gender' => $request->gender,
    //         'phone' => $request->phone
    //       ]);
    //     }
    //     if($update_info){
    //       $data['status']= 'success';
    //       $data['result']='Successfull update info';
    //       return response()->json($data,200);
    //     }
    //    }catch(Exception $e){
    //             $error = $e->getMessage();
    //             $response['status'] = 'error';
    //             $response['message'] = $error;
    //             return response()->json($response, 403);
    //           }
    // }

    // public function teachingInfo(Request $request){
    //   try{
    //     if($this->user['role'] = 'seller'){
    //       $update_info =  User::where('user_email',$this->user['user_email'])->update([
    //         'i_am_a' => $request->i_am_a,
    //         'location' => $request->location,
    //         'preferred_language' => $request->preferred_language,
    //         'affiliation' => $request->affiliation,
    //         'age_group' => $request->age_group,
    //         'subject' => $request->subject,
    //       ]);
    //     }
    //     if($update_info){
    //       $data['status']= 'success';
    //       $data['result']='Successfull update teaching info';
    //       return response()->json($data,200);
    //     }
    //    }catch(Exception $e){
    //             $error = $e->getMessage();
    //             $response['status'] = 'error';
    //             $response['message'] = $error;
    //             return response()->json($response, 403);
    //           }
    // }

 
    public function changePassword(Request $request){
      try{
        if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
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
    //graph api
    public function total_products(Request $request){
     try{
      if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
        $products = Course::where('seller_id',$this->user['id'])->get();
        $total_products = $products->count();
        $response['total_products'] = $total_products;
                return response()->json([
                    'success'=>true,
                    'response'=> $response
                ],200);
      }
     }catch(Exception $e){
        $error = $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $error;
        return response()->json($response, 403);
      }
    }

    public function products_sold(Request $request){
      try{
       if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
         $productsSold = Course::where('seller_id',$this->user['id'])
                    ->join('enrollments', 'enrollments.course_id', '=', 'course.id')
                    ->get();
         $totalSold_products = $productsSold->count();
         $response['sold_products'] = $totalSold_products;
                 return response()->json([
                     'success'=>true,
                     'response'=> $response
                 ],200);
       }
      }catch(Exception $e){
         $error = $e->getMessage();
         $response['status'] = 'error';
         $response['message'] = $error;
         return response()->json($response, 403);
       }
     }

     public function total_order(Request $request){
      try{
       if($this->user['role'] = 'seller'|| $this->user['role'] == 'user'){
         $orderItem = OrderItems::where('seller_id',$this->user['id'])
                    ->get();
         $order_products = $orderItem->count();
         $response['total_order'] = $order_products;
                 return response()->json([
                     'success'=>true,
                     'response'=> $response
                 ],200);
       }
      }catch(Exception $e){
         $error = $e->getMessage();
         $response['status'] = 'error';
         $response['message'] = $error;
         return response()->json($response, 403);
       }
     }

     public function getTotalOrderCountByMonth(Request $request){
      try{
          if($this->user['role'] == 'seller'|| $this->user['role'] == 'user')
          {
              $users = OrderItems::where('seller_id',$this->user['id'])
                   ->whereBetween('created_at',array($request->start_date,$request->end_date))
                  ->get()
                  ->groupBy(function ($date) {
                      return Carbon::parse($date->created_at)->format('m');
                  });
                  $total_sales = OrderItems::where('seller_id',$this->user['id'])
                                ->whereBetween('created_at',array($request->start_date,$request->end_date))
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

  public function todaySales(Request $request){
    try{
        if($this->user['role'] == 'seller'|| $this->user['role'] == 'user')
        {
                $today_Order_time=DB::table('order_item')
                  ->where(DB::raw("(DATE_FORMAT(order_item.created_at,'%Y-%m-%d'))"),'=',date('Y-m-d'))
                  ->select('created_at',DB::raw('hour(created_at) as time')
                  ,DB::raw('COUNT(*) as count'))->groupBy('time','created_at')->get();
                    $newdata=array();
                    foreach($today_Order_time as $valdata)
                    {
                        $newdata[]=['time'=>date('h:i:a',strtotime($valdata->created_at)),
                        'count'=> $valdata->count];
                    }
                    if(empty($newdata)){
                      return response()->json([
                        'success'=>true,
                        'response'=> 'No oreder found !'
                       ],200);
                    }else{
                      return response()->json([
                        'success'=>true,
                        'response'=>$newdata
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
 // for product page sections
   public function totalSalesOrderAndProfit(Request $request){
    try{
      if($this->user['role'] == 'seller'|| $this->user['role'] == 'user')
      {
        $newdata=[];
          $sales = OrderItems::where('seller_id',$request->user['id'])
                   ->sum('course_fee');
                   $percentage = 30;
                   $net_profit = ($percentage / 100) * $sales;
                  $newdata['Total_sales']  =  $sales;
                  $newdata['Total_profit']  = $net_profit;
                  
           $count_order = OrderItems::where('seller_id',$request->user['id'])->get();
           $order_products = $count_order->count();
           $newdata['Total_order']  =  $order_products;

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

   public function todaySalesProductSection(Request $request){
    try{
      if($this->user['role'] == 'seller'|| $this->user['role'] == 'user')
      {
        $newdata=[];
          $sales = OrderItems::where('seller_id',$request->user['id'])
                  ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',date('Y-m-d'))
                   ->sum('course_fee');
                   $percentage = 30;
                   $net_profit = ($percentage / 100) * $sales;
                  $newdata['Today_sales']  =  $sales;
                  $newdata['Today_profit']  = $net_profit;

           $count_order = OrderItems::where('seller_id',$request->user['id'])
                        ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'=',date('Y-m-d'))
                        ->get();
           $order_products = $count_order->count();
           $newdata['Today_order']  =  $order_products;
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

   public function totalProductAndProductSold(Request $request){
    try{
      if($this->user['role'] == 'seller'|| $this->user['role'] == 'user')
        {
          $data = [];
           $product = Course::where('seller_id',$request->user['id'])->get();
           $total_product = $product->count();
           $data['Total_Products'] = $total_product;
           if($total_product){
            $soldProduct = Course::where('seller_id',$request->user['id'])
                       ->join('enrollments','enrollments.course_id','=','course.id')
                       ->get();
              $data['Sold_Products'] = $soldProduct->count();
           }
           return response()->json([
            'success'=>true,
            'response'=>$data
           ],200);
        }
      }catch(Exception $e){
        $error = $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $error;
        return response()->json($response, 403);
      }
   }

   public function visitorGraph(Request $request){
    try{
                $users = Visitor::whereBetween('created_at',array($request->start_date,$request->end_date))
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
    }catch(Exception $e){
        $error = $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $error;
        return response()->json($response, 403);
    }
}

  public function ratingGraph(Request $request){
     try{
        $users = Ratereview::whereBetween('created_at',array($request->start_date,$request->end_date))
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
     }catch(Exception $e){
        $error = $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $error;
        return response()->json($response, 403);
    }
  }
     
}