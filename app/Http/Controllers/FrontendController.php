<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Mail\Contactusmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Course;
use App\Models\User;

class FrontendController extends Controller
{
  
    public function allfrontendResourse(Request $request){
        try{
            $images = [];
                  if($request->populer && $request->new === false ){
                    $fetchallpopular = DB::table('course')
                    ->join('users','users.id','=','course.seller_id')
                    ->join('enrollments','enrollments.course_id','=','course.id')
                    ->select('course.id','course.course_title','course.course_banner','course.course_description','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
                    ->orderBy('id','asc')->get()->toArray();
                    if($fetchallpopular){
                        foreach($fetchallpopular as $popular){
                            $images = explode(",",$popular->course_banner);
                            $popular->course_banner = asset('/uploads/course_banner/'.$images[0]);
                            $popular->user_profile = asset('/uploads/'.$popular->user_profile);
                            $getpoprating = DB::table('ratereview')->where('course_id',$popular->id)->get()->avg('rating');
                           if($getpoprating){
                            $popular->rating = $getpoprating;
                           }else{
                            $popular->rating = 0;
                           }
                        }
                        return response()->json([
                            'success'=>true,
                            'response_popular'=> $fetchallpopular
                        ],200);
                    }else{
                        $fetchallproducts = DB::table('course')
                            ->join('users','users.id','=','course.seller_id')
                            ->select('course.id','course.course_title','course.course_banner','course.course_description','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
                            ->orderBy('id','asc')->get()->toArray();
                            foreach($fetchallproducts as $resourse){
                                $images = explode(",",$resourse->course_banner);
                                $resourse->course_banner = asset('/uploads/course_banner/'.$images[0]);
                                $popular->user_profile = asset('/uploads/'.$popular->user_profile);
                                $getrating = DB::table('ratereview')->where('course_id',$resourse->id)->get()->avg('rating');
                            if($getrating){
                                $resourse->rating = $getrating;
                            }else{
                                $resourse->rating = 0;
                            }
                            }
                            return response()->json([
                                'success'=>true,
                                'response'=>$fetchallproducts
                            ],200);
                    }
                  }
                  if($request->new && $request->populer ===false ){
                    $date = Carbon::now()->subDays(7);
                    $fetchallnew = DB::table('course')->where(DB::raw("(DATE_FORMAT(course.created_at,'%Y-%m-%d'))"), '>=', $date)
                    ->join('users','users.id','=','course.seller_id')
                    ->select('course.id','course.course_title','course.course_banner','course.course_description','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
                    ->orderBy('id','asc')->get()->toArray();
                    if($fetchallnew){
                        foreach($fetchallnew as $newdata){
                            $images = explode(",",$newdata->course_banner);
                            $newdata->course_banner = asset('/uploads/course_banner/'.$images[0]);
                            $popular->user_profile = asset('/uploads/'.$popular->user_profile);
                            $getnewRating = DB::table('ratereview')->where('course_id',$newdata->id)->get()->avg('rating');
                           if($getnewRating){
                            $newdata->rating = $getnewRating;
                           }else{
                            $newdata->rating = 0;
                           }
                        }
                        return response()->json([
                            'success'=>true,
                            'response_new'=> $fetchallnew
                        ],200);
                    }else{
                        $fetchallproducts = DB::table('course')
                        ->join('users','users.id','=','course.seller_id')
                        ->select('course.id','course.course_title','course.course_banner','course.course_description','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
                        ->orderBy('id','asc')->get()->toArray();
                        foreach($fetchallproducts as $resourse){
                            $images = explode(",",$resourse->course_banner);
                            $resourse->course_banner = asset('/uploads/course_banner/'.$images[0]);
                            $popular->user_profile = asset('/uploads/'.$popular->user_profile);
                            $getrating = DB::table('ratereview')->where('course_id',$resourse->id)->get()->avg('rating');
                        if($getrating){
                            $resourse->rating = $getrating;
                        }else{
                            $resourse->rating = 0;
                        }
                        }
                        return response()->json([
                            'success'=>true,
                            'response'=>$fetchallproducts
                        ],200);
                    }
                }
                if($request->populer === false && $request->new === false){
                    $fetchallproducts = DB::table('course')
                    ->join('users','users.id','=','course.seller_id')
                    ->select('course.id','course.course_title','course.course_banner','course.course_description','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
                    ->orderBy('id','asc')->get()->toArray();
                    foreach($fetchallproducts as $resourse){
                        $images = explode(",",$resourse->course_banner);
                        $resourse->course_banner = asset('/public/uploads/course_banner/'.$images[0]);
                        $resourse->user_profile = asset('/uploads/'.$resourse->user_profile);
                        $getrating = DB::table('ratereview')->where('course_id',$resourse->id)->get()->avg('rating');
                    if($getrating){
                        $resourse->rating = $getrating;
                    }else{
                        $resourse->rating = 0;
                    }
                    }
                    return response()->json([
                        'success'=>true,
                        'response'=>$fetchallproducts
                    ],200);
                }
                

        }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
              }
    }

    public function fetchAllTestimonial(){
        try{
            $fetchallTestimonial = DB::table('testimonal')->get();
            foreach($fetchallTestimonial as $testimonal){
                 $testimonal->image = asset('/uploads/'.$testimonal->image);
            }
            if($fetchallTestimonial){
                return response()->json([
                    'success'=>true,
                    'response'=>$fetchallTestimonial
                ],200);
            }
           }catch(Exception $e){
              return "error";
           }
    }

    public function contactUs(Request $request){
        try{
            $details = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'phone' => $request->phone,
                'message' => $request->message
            ];
            $admin_mail = 'panchram@spartanbots.com'; 
            $sendmail = Mail::to($admin_mail)->send(new Contactusmail($request->subject, $details));
            return response()->json(['message' => 'Mail Sent Sucssfully'], 200); 
        }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
              }
    }

    public function globalSearch(Request $request){
       try{
        $query = Course::select('id','course_title','subject','grade_label','course_fee','course_banner','seller_id');
        $search = $request->input('search',null);
        $query = is_null($search)  ? $query : $query->where('course_title','LIKE','%'.$search.'%')->orWhere('subject','LIKE','%'.$search.'%')->orWhere('grade_label','LIKE','%'.$search.'%')->get();
        foreach($query as $qr){
            $images = explode(",",$qr->course_banner);
            $qr->course_banner = asset('/public/uploads/course_banner/'.$images[0]);
            $sellerdetila =  DB::table('users')->where('id','=',$qr->seller_id)->get();
           foreach($sellerdetila as $details){
            $qr->seller_name = $details->full_name;
            $qr->user_profile = asset('/uploads/'.$details->user_profile);
           }
            $getrating = DB::table('ratereview')->where('course_id',$qr->id)->get()->avg('rating');
            if($getrating){
                $qr->rating = $getrating;
            }else{
                $qr->rating = 0;
            }
        }
        if($query){
        return response()->json([
                    'success'=>true,
                    'response'=>$query
                ],200);
       }
       }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
              }
    }

    //single product page support apis

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
                                $course->course_banner = asset('/uploads/course_banner/'.$images[0]);
                                $course->subject =  unserialize($course->subject);
                                $course->submission_type =  unserialize($course->submission_type);
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

    public function  course_rating_avg(Request $request)
    {
      try{
         
            $Rating = DB::table('ratereview')->where('course_id','=', $request->course_id)
            ->get()->avg('rating');
                return response()->json([
                    'success'=>true,
                    'response'=>$Rating
                ],200);
        
            
        }catch(Exception $e){
            return $e;
        }
    }

    public function ratingProgressBar(Request $request){
        try{
            $allprogress = [];
                $product_rating = Ratereview::where('course_id','=',$request->course_id)
                ->select('rating')
                ->get()
                ->toArray();
                $progress = [];
                $progress['count5'] = 0;
                $progress['count4'] = 0;
                $progress['count3'] = 0;
                $progress['count2'] = 0;
                $progress['count1'] = 0;
                 foreach($product_rating as $progressRate){
                    if($progressRate['rating'] == 5){
                        $progress['count5']++;
                    }
                    if($progressRate['rating'] == 4){
                        $progress['count4']++;
                    }
                    if($progressRate['rating'] == 3){
                        $progress['count3']++;
                    }
                    if($progressRate['rating'] == 2){
                        $progress['count2']++;
                    }
                    if($progressRate['rating'] == 1){
                        $progress['count1']++;
                    }
                 }
                return response()->json([
                    'success'=>true,
                    'response'=> $progress
                ],200);
            
        }catch(Exception $e){
                return $e;
            }
    }

    public function totalRating(Request $request){
        try{
             $response=[];
     //rating
             $product_review = DB::table('ratereview')
             ->where('course_id', '=',$request->course_id)
             ->where('title','!=', '')
             ->where('description', '!=', '')
             ->get();
             $product_rating = DB::table('ratereview')
             ->where('course_id','=',$request->course_id)
             ->where('rating','!=','')
             ->get();
             $review = $product_review->count();
             $rating = $product_rating->count();
             $response['product_rating'] = $rating;
             $response['product_review'] = $review;
             return response()->json([
                 'success'=>true,
                 'response'=> $response
             ],200);
        }
        catch(Exception $e){
            return $e;
        }
    }

    public function get_course_Rating_Review(Request $request){
        try{
            $get_Rating = User::leftJoin('ratereview', 'ratereview.user_id', '=', 'users.id')
            ->select('ratereview.*','users.full_name','users.user_profile')
            ->where('ratereview.course_id', '=',$request->course_id)
            ->get();
            foreach($get_Rating as $rate){
                $rate->user_profile = asset('/uploads/'.$rate->user_profile);
            }
             if($get_Rating){
                 return response()->json([
                     'success'=>true,
                     'response'=>$get_Rating
                 ],200);
             }
             else{
                 $response['status'] = 'error';
                 $response['message'] = 'Only user can fetch rating data';
                 return response()->json($response, 403);
         }
         
        }
        catch(Exception $e){
         return $e;
        }
    }

    public function getBuyernameList(Request $request){
        try{
            $full_name = User::where('role','=','user')
               ->select('full_name')
               ->get();
             return response()->json([
                   'success'=>true,
                   'response'=> $full_name
               ],200); 
        }catch(Exception $e){
               return $e;
           }
   }
   
   public function getSellernameList(Request $request){
        try{
            $full_name = User::where('role','=','seller')
               ->select('full_name')
               ->get();
             return response()->json([
                   'success'=>true,
                   'response'=> $full_name
               ],200); 
        }catch(Exception $e){
               return $e;
           }
   }
   
   public function getAdminnameList(Request $request){
        try{
            $full_name = User::where('role','=','admin')
               ->select('full_name')
               ->get();
             return response()->json([
                   'success'=>true,
                   'response'=> $full_name
               ],200); 
        }catch(Exception $e){
               return $e;
           }
   }
    
}
