<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category;
use App\Models\Language;
use App\Models\Resourse;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Exception;
use File;

class CourseController extends Controller
{
    private $user;
    
    public function __construct(){
         $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    public function createCourse(Request $request)
    {
        
        $response = [];

        if ($request['course_title'] == '') {
            $response['course_title'] = 'Please enter course title';
        }
        if ($request['course_description'] == '') {
            $response['course_description'] = 'Please enter course description';
        }
        if ($request['subject'] == '') {
            $response['subject'] = 'Please enter course subject';
        }
        if ($request['category'] == '') {
            $response['category'] = 'Please enter category';
        }
        if ($request['language'] == '') {
            $response['language'] = 'Please enter language';
        }
        if ($request['grade_label'] == '') {
            $response['grade_label'] = 'Please enter grade lable';
        }
        if ($request['age_group'] == '') {
            $response['age_group'] = 'Please enter age group';
        }
        if ($request['course_fee'] == '') {
            $response['course_fee'] = 'Please enter course fee';
        }
        if ($request['affiliation'] == '') {
            $response['affiliation'] = 'Please enter course affiliation';
        }
        if ($request['submission_type'] == '') {
            $response['submission_type'] = 'Please enter course submission_type';
        }
        if ($request['difficulty'] == '') {
            $response['difficulty'] = 'Please enter course difficulty';
        }
        if (count($response)) {
            $response['status'] = 'error';
            return response()->json($response, 403);
        } else {
            try{
                if($this->user['role'] == 'seller' || $this->user['role'] == 'user'){
                     $fetchCourse = Course::where('course_title','=',$request->course_title)->first();
                      if($fetchCourse){
                            return response()->json([
                                'status' => false,
                                'message' => 'Item name already exist'
                             ],403);
                       }else{
                    
                    $course  =  Course::create([
                            'course_title' => $request->course_title,
                            'course_description' => $request->course_description,
                            'subject' => serialize($request->subject),
                            'category_id' => $request->category_id,
                            'language_id' => $request->language_id,
                            'category' => $request->category,
                            'language' => $request->language,
                            'grade_label' => $request->grade_label,
                            'age_group' => $request->age_group,
                            'course_fee' => $request->course_fee,
                            'affiliation'=> $request->affiliation,
                            'submission_type' => serialize($request->submission_type),
                            'difficulty' => $request->difficulty,
                            'seller_id' => $this->user['id'],
                            'verify' => 1,
                    ]);
                    //notification
                    $message = $this->user['full_name'].' added a new item '.$request->course_title;
                    $navigate = new NavigationController();
                    $navigate->createNotification(null,$this->user['id'],$message,1);
                    
                if($course){
                    return response()->json([
                        'status' => true,
                        'message' => 'Resource created successfully',
                        'course_id' => $course->id,
                    ], 200);
                }
                
                       }
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Only seller can create resources';
                    return response()->json($response, 403);
                }
            }catch (Exception $e) {
                return $e;
            }
        }
    }

    public function addCoursecontent(Request $request){
    try{
        if($request->hasFile('course_content') && $request->course_id){
            $file = $request->file('course_content');
            $filename = time().$file->getClientOriginalName();
            $path = public_path().'/uploads/';
            $file->move($path, $filename);
            $UpdateCourse = Course::where('id', $request->course_id)->update([
                'course_content' => $filename, 
            ]);
                if($UpdateCourse){
                    return response()->json([
                        'status' => true,
                        'message' => 'Item content updated successfully'
                    ], 200);
                }
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Item content cannot be empty';
                    return response()->json($response, 403);
                }
            }catch(Exception $e){
                $error = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = $error;
                return response()->json($response, 403);
            }     
    }

    public function addCourseBanner(Request $request){
        try{
            $images=array();
            if($request->hasFile('course_banner') && $request->course_id){
                $files = $request->file('course_banner');
                foreach($files as $file){
                    $course_banner = time().$file->getClientOriginalName();
                    $course_path = public_path().'/uploads/course_banner/';
                    $file->move($course_path, $course_banner);
                    $images[]=$course_banner;
                }
            $UpdateCourse = Course::where('id', $request->course_id)->update([
                'course_banner' => implode(",",$images), 
            ]);   
            if($UpdateCourse){
                return response()->json([
                    'status' => true,
                    'message' => 'Item banner updated successfully'
                ], 200);
            }
            
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Item banner cannot be empty';
                return response()->json($response, 403);
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = $error;
            return response()->json($response, 403);
        } 
    }

    public function editCourse(Request $request){
        try{
            if($this->user['role'] == 'seller' || $this->user['role'] == 'user'){
            $images = [];
            $banners = [];
            $finalBannerImages = [];
            $editCourse = Course::where('id','=',$request->id)
                                  ->where('verify','=',1)
                                  ->get();
                  foreach($editCourse as $course){
                    $images = explode(",",$course->course_banner);
                    array_push($banners,$images);
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
              $editCourse[0]->course_banner = $finalBannerImages;              
            // $catdata = DB::table('category')
            // ->select('category_name')
            // ->where('id','=', $editCourse['category_id'])
            // ->get();
            // $langdata = DB::table('language')
            // ->select('language_name')
            // ->where('id','=', $editCourse['language_id'])
            // ->get();
            //$editCourse['course_content'] = '/uploads/'.$editCourse['course_content'];
            // $editCourse['category_name'] = $catdata[0]->category_name;
            // $editCourse['language_name'] = $langdata[0]->language_name;
            if($editCourse){
                return response()->json([
                    'success'=>true,
                    'response'=>$editCourse
                ],200);
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Only seller can edit resource items';
            return response()->json($response, 403);
        }
           }catch(Exception $e){
              return "error";
           }
    }

    public function fetchAllCourse(){
        try{
            $fetchallCourse = DB::table('course')
            ->where('verify','=',1)
            ->orderBy('created_at','desc')->get()->toArray();
            if($fetchallCourse){
                return response()->json([
                    'success'=>true,
                    'response'=>$fetchallCourse
                ],200);
            }
        }catch(Exception $e){
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong';
            return response()->json($response, 500);
         }
    }

    public function removeContent(Request $request){
        $response = [];
        if($request['id'] ==''){
            $response['id']= 'Please enter course id';
        }
        if($request['course_content'] ==''){
            $response['course_content']= 'Please select content';
        }
        if(File::exists(public_path('uploads/'.$request->course_content))){
            File::delete(public_path('uploads/'.$request->course_content));
            $removeContent = Course::where('id','=',$request->id)
            ->update(['course_content'=>'']);
            if($removeContent){
                return response()->json([
                    'status'=>true,
                    'success'=>"Content removed successfully"
                   ],200);
            }
        }else{
            $response['status']= 'error';
            $response['message']= 'File does not exist';
            return response()->json($response,403);
        }
    }
    
    public function updateCourse(Request $request){
    
            if($this->user['role'] == 'seller' || $this->user['role'] == 'user'){
                $response = [];
                $images=array();
                if($request['id'] ==''){
                    $response['id']= 'Please enter course id';
                }
                if ($request['course_title'] == '') {
                    $response['course_title'] = 'Please enter course title';
                }
                if ($request['course_description'] == '') {
                    $response['course_description'] = 'Please enter course description';
                }
                if ($request['subject'] == '') {
                    $response['subject'] = 'Please enter course subject';
                }
                if ($request['category'] == '') {
                    $response['category'] = 'Please enter category';
                }
                if ($request['language'] == '') {
                    $response['language'] = 'Please enter language';
                }
                if ($request['grade_label'] == '') {
                    $response['grade_label'] = 'Please enter grade lable';
                }
                if ($request['age_group'] == '') {
                    $response['age_group'] = 'Please enter age group';
                }
                if ($request['course_fee'] == '') {
                    $response['course_fee'] = 'Please enter course fee';
                }
                if ($request['affiliation'] == '') {
                    $response['affiliation'] = 'Please enter course affiliation';
                }
                if ($request['submission_type'] == '') {
                    $response['submission_type'] = 'Please enter course submission_type';
                }
                if ($request['difficulty'] == '') {
                    $response['difficulty'] = 'Please enter course difficulty';
                }
                if(count($response)){
                    $response['status']= 'error';
                    return response()->json($response,403);
                }else{
                    try{
                        $sub = json_decode($request->subject);
                        $submission_type = json_decode($request->submission_type);
                        $UpdateCourse = Course::where('id', $request->id)->update([
                            'course_title' => $request->course_title,
                            'course_description' => $request->course_description,
                            'subject' => serialize($sub),
                            'category_id' => $request->category_id,
                            'language_id' => $request->language_id,
                            'category' => $request->category,
                            'language' => $request->language,
                            'grade_label' => $request->grade_label,
                            'age_group' => $request->age_group,
                            'course_banner' => $request->course_banner,
                            'course_content' => $request->course_content,
                            'course_fee' => $request->course_fee,
                            'affiliation'=> $request->affiliation,
                            'submission_type' => serialize($submission_type),
                            'difficulty' => $request->difficulty,
                            'seller_id' => $this->user['id'],
                            'verify' => 1,
                      ]);
                      if($request->hasFile('course_content')){
                        $file = $request->file('course_content');
                        $filename = time().$file->getClientOriginalName();
                        $path = public_path().'/uploads/';
                        $file->move($path, $filename);
                        $UpdateCourse = Course::where('id', $request->id)->update([
                            'course_content' => $filename, 
                        ]);
                       }
                       if($request->hasFile('course_banner')){
                        $files = $request->file('course_banner');
                        foreach($files as $file){
                            $course_banner = time().$file->getClientOriginalName();
                            $course_path = public_path().'/uploads/course_banner/';
                            $file->move($course_path, $course_banner);
                            array_push($images,$course_banner);
                            $images[]=$course_banner;
                        }
                            $UpdateCourse = Course::where('id', $request->id)->update([
                                'course_banner' => implode(",",$images), 
                            ]); 
                        }
                      if($UpdateCourse){
                        return response()->json([
                            'status'=>true,
                            'success'=>"Item updated successfully"
                           ],200);
                      }
                    }catch(Exception $e){
                        $response['status'] = 'error';
                        $response['message'] = 'Somthing went wrong!';
                        return response()->json($response, 500);
                    }
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only seller can edit resource items';
                return response()->json($response, 403);
            }
    }
    
    public function fetchUnpublishCourse(){
        try{
        if($this->user['role'] == 'admin'){
            $courses = Course::where('verify','=',0)->get();
            if($courses){
                $category_name = '';
                $language_name = '';
                foreach($courses as $course){
                    $catdata = DB::table('category')
                       ->select('category_name')
                       ->where('id','=', $course['category_id'])
                       ->get();
                       foreach($catdata as $catname){
                        $category_name = $catname->category_name;
                       }
                    $langdata = DB::table('language')
                       ->select('language_name')
                       ->where('id','=', $course['language_id'])
                       ->get();
                       foreach($langdata as $langname){
                        $language_name = $langname->language_name;
                       }
                    $course['category_name'] = $category_name;
                    $course['language_name'] = $language_name; 
                }
                return response()->json([
                    'success'=>true,
                    'response'=>$courses
                ],200);
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Only admin can fetch unpublish course';
            return response()->json($response, 403);
        }
           }catch(Exception $e){
            $response['status'] = 'error';
            $response['message'] = 'Somthing went wrong!';
            return response()->json($response, 500);
           }
    }

    public function updatePublishStatus(Request $request){
        $response = [];
        if($request['id']==''){
            $response['id'] = 'Please enter course id';
        }
        if (count($response)) {
            $response['status'] = 'error';
            return response()->json($response, 403);
        } else {
            try{
            if($this->user['role'] == 'admin'){
                $publishCourse= Course::where('id', $request->id)->update([
                    'verify' => 1,
              ]);
              if($publishCourse){
                return response()->json([
                    'status'=>true,
                    'success'=>"Course Publish successfully!"
                   ],200);
              }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Only admin can publish course';
                return response()->json($response, 403);
            }
            }catch(Exception $e){
                $response['status'] = 'error';
                $response['message'] = 'Somthing went wrong!';
                return response()->json($response, 500);
            }
        }
    }

    public function fetchAllResourse(Request $request){
       try{
           if($this->user['role'] == 'seller' || $this->user['role'] == 'user'){
            $resourseItem = Resourse::where('seller_id','!=',$this->user['id'])->orderBy('id','desc')->get()->toArray();
            if($resourseItem){
                return response()->json([
                    'status'=>true,
                    'response'=>$resourseItem
                   ],200);
            }
           }else{
                $response['status'] = 'error';
                $response['message'] = 'Only seller, User can use fetch Resourse';
                return response()->json($response, 403);
            }
       }catch(Exception $e){
         return $e;
       }
    }

}