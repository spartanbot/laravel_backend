<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category;
use App\Models\Language;
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
        if ($request['category_id'] == '') {
            $response['category_id'] = 'Please enter category';
        }
        if ($request['language_id'] == '') {
            $response['language_id'] = 'Please enter language';
        }
        if ($request['grade_label'] == '') {
            $response['grade_label'] = 'Please enter grade lable';
        }
        if ($request['course_fee'] == '') {
            $response['course_fee'] = 'Please enter course fee';
        }
        if (count($response)) {
            $response['status'] = 'error';
            return response()->json($response, 403);
        } else {
            try{
                if($this->user['role'] == 'seller'){
                     $fetchCourse = Course::where('course_title','=',$request->course_title)->first();
                      if($fetchCourse){
                            return response()->json([
                                'status' => false,
                                'message' => 'Course Name already exist!'
                             ],403);
                       }else{
                    
                    if($request->hasFile('course_content')){
                        $file = $request->file('course_content');
                            $filename = time().$file->getClientOriginalName();
                            $path = public_path().'/uploads/';
                            $file->move($path, $filename);
                    }else{
                        $response['status'] = 'error';
                        $response['message'] = 'Course Content can not empty';
                        return response()->json($response, 403);
                    }
                    if($request->hasFile('course_banner')){
                        $file_course_banner = $request->file('course_banner');
                        $course_banner = time().$file_course_banner->getClientOriginalName();
                        $course_path = public_path().'/uploads/course_banner/';
                        $file_course_banner->move($course_path, $course_banner);
                    }else{
                        $response['status'] = 'error';
                        $response['message'] = 'Course Banner can not empty';
                        return response()->json($response, 403);
                    }
                    $course  =  Course::create([
                            'course_title' => $request->course_title,
                            'course_description' => $request->course_description,
                            'subject' => $request->subject,
                            'category_id' => $request->category_id,
                            'language_id' => $request->language_id,
                            'grade_label' => $request->grade_label,
                            'course_banner' => 'banner'.$course_banner,
                            'course_content' => $filename,
                            'course_fee' => $request->course_fee,
                            'seller_id' => $this->user['id'],
                            'verify' => 0,
                    ]);
                if($course){
                    return response()->json([
                        'status' => true,
                        'message' => 'Course created successfully!'
                    ], 200);
                }
                
                       }
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Only seller can create course';
                    return response()->json($response, 403);
                }
            }catch (Exception $e) {
                return $e;
            }
        }
    }
    
    public function editCourse(Request $request){
        try{
            if($this->user['role'] != 'user'){
            $editCourse = Course::where('id','=',$request->id)
                                  ->where('verify','=',1)
                                  ->first();
            $catdata = DB::table('category')
            ->select('category_name')
            ->where('id','=', $editCourse['category_id'])
            ->get();
            $langdata = DB::table('language')
            ->select('language_name')
            ->where('id','=', $editCourse['language_id'])
            ->get();
            $editCourse['category_name'] = $catdata[0]->category_name;
            $editCourse['language_name'] = $langdata[0]->language_name;
            if($editCourse){
                return response()->json([
                    'success'=>true,
                    'response'=>$editCourse
                ],200);
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Only seller can edit course';
            return response()->json($response, 403);
        }
           }catch(Exception $e){
              return "error";
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
                    'success'=>"Content remove successfully!"
                   ],200);
            }
        }else{
            $response['status']= 'error';
            $response['message']= 'File dose not exist!';
            return response()->json($response,403);
        }
    }
    
    public function updateCourse(Request $request){
    
            if($this->user['role'] != 'user'){
                $response = [];

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
                if ($request['category_id'] == '') {
                    $response['category_id'] = 'Please enter category';
                }
                if ($request['language_id'] == '') {
                    $response['language_id'] = 'Please enter language';
                }
                if ($request['grade_label'] == '') {
                    $response['grade_label'] = 'Please enter grade lable';
                }
                if ($request['course_fee'] == '') {
                    $response['course_fee'] = 'Please enter course fee';
                }
                if ($request['course_banner'] == '') {
                    $response['course_banner'] = 'Please upload course banner';
                }
                if ($request['course_content'] == '') {
                    $response['course_content'] = 'Please upload course content';
                }
                if(count($response)){
                    $response['status']= 'error';
                    return response()->json($response,403);
                }else{
                    try{
                        $UpdateCourse = Course::where('id', $request->id)->update([
                            'course_title' => $request->course_title,
                            'course_description' => $request->course_description,
                            'subject' => $request->subject,
                            'category_id' => $request->category_id,
                            'language_id' => $request->language_id,
                            'grade_label' => $request->grade_label,
                            'course_banner' => $request->course_banner,
                            'course_content' => $request->course_content,
                            'course_fee' => $request->course_fee,
                            'seller_id' => $this->user['id'],
                            'verify' => 1,
                      ]);
                      if($UpdateCourse){
                        return response()->json([
                            'status'=>true,
                            'success'=>"Course updated successfully!"
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
                $response['message'] = 'Only seller can edit course';
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

}