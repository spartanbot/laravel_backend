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
                    ->select('course.id','course.course_title','course.course_banner','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
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
                            ->select('course.id','course.course_title','course.course_banner','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
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
                    ->select('course.id','course.course_title','course.course_banner','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
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
                        ->select('course.id','course.course_title','course.course_banner','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
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
                    ->select('course.id','course.course_title','course.course_banner','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
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
    
}
