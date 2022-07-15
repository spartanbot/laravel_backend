<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class FrontendController extends Controller
{
  
    public function allfrontendResourse(Request $request){
        try{
                  if($request->populer && $request->new === false ){
                    $fetchallpopular = DB::table('course')
                    ->join('users','users.id','=','course.seller_id')
                    ->join('enrollments','enrollments.course_id','=','course.id')
                    ->select('course.id','course.course_title','course.course_banner','course.grade_label','course.course_fee','users.full_name as seller_name','users.user_profile')
                    ->orderBy('id','asc')->get()->toArray();
                    if($fetchallpopular){
                        foreach($fetchallpopular as $popular){
                            $popular->course_banner = asset('/uploads/course_banner/'.$popular->course_banner);
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
                                $resourse->course_banner = asset('/uploads/course_banner/'.$resourse->course_banner);
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
                            $newdata->course_banner = asset('/uploads/course_banner/'.$newdata->course_banner);
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
                            $resourse->course_banner = asset('/uploads/course_banner/'.$resourse->course_banner);
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
                        $resourse->course_banner = asset('/public/uploads/course_banner/'.$resourse->course_banner);
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

    
}
