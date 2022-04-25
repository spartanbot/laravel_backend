<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonal;
use JWTAuth;
use DB;
use File;

class TestimonialController extends Controller
{
    private $user;
      
    public function __construct(){
          $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function createTestimonial(Request $request){

        $response=[];

        if($request['title'] ==''){
            $response['title']= 'Please enter testimonial title';
        }

        if($request['grade'] ==''){
            $response['grade']= 'Please enter testimonial grade';
        }

        if($request['school'] ==''){
            $response['school']= 'Please enter testimonial school';
        }

        if($request['location'] ==''){
            $response['location']= 'Please enter testimonial location';
        }

        if($request['description'] ==''){
            $response['description']= 'Please enter testimonial description';
        }

        if(count($response)){
            $response['status']= 'error';
            return response()->json($response,403);
         }else{
            try {
              $fetchtestimonial = Testimonal::where('title','=',$request->title)->first();
              if($fetchtestimonial){
                return response()->json([
                    'status' => false,
                    'message' => 'testimonial already exist!'
                 ],403);
              }else{
                if($request->hasFile('image')){
                    $file = $request->file('image');
                        $filename = time().$file->getClientOriginalName();
                        $path = public_path().'/uploads/';
                        $file->move($path, $filename);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'testimonial image can not empty';
                    return response()->json($response, 403);
                }
                $testimonial  =  Testimonal::create([
                    'title' => $request->title,
                    'grade' => $request->grade,
                    'school' => $request->school,
                    'location' => $request->location,
                    'description' =>$request->description,
                    'image' => $filename
                ]);
                if($testimonial){
                    return response()->json([
                        'status' => true,
                        'message' => 'testimonial created successfully!'
                     ],200);
                }
              }
            } catch (Exception $e) {
                return "error";
            }
            
         }
    }

    public function editTestimonial(Request $request){
        try{
            $fetchTestimonial = Testimonal::where('id','=',$request->id)->first();
            if($fetchTestimonial){
                return response()->json([
                    'success'=>true,
                    'response'=>$fetchTestimonial
                ],200);
            }
           }catch(Exception $e){
              return "error";
           }
    
    }

    public function fetchAllTestimonial(){
        try{
            $fetchallTestimonial = DB::table('testimonal')->get();
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

    public function updateTestimonial(Request $request){

        $response=[];
    
        if($request['title'] ==''){
            $response['title']= 'Please enter testimonial title';
        }

        if($request['grade'] ==''){
            $response['grade']= 'Please enter testimonial grade';
        }

        if($request['school'] ==''){
            $response['school']= 'Please enter testimonial school';
        }

        if($request['location'] ==''){
            $response['location']= 'Please enter testimonial location';
        }

        if($request['description'] ==''){
            $response['description']= 'Please enter testimonial description';
        }
         
        if(count($response)){
            $response['status']= 'error';
            return response()->json($response,403);
         }else{
            try {
                $UpdateTestimonal= Testimonal::where('id', $request->id)->update([
                    'title' => $request->title,
                    'grade' => $request->grade,
                    'school' => $request->school,
                    'location' => $request->location,
                    'description' =>$request->description,
              ]);
              if($UpdateTestimonal){

                return response()->json([
                    'status'=>true,
                    'success'=>"Testimonal updated successfully!"
                   ],200);
              }
            } catch (Exception $e) {
                return "error";
            }
            
         }
    
    }

    public function updateImage(Request $request){
         try{
            $fetchTestimonial_image = Testimonal::where('id','=',$request->id)
            ->select('image')
            ->get();
            if (File::exists(public_path('uploads/'.$fetchTestimonial_image[0]['image']))) {
                File::delete(public_path('uploads/'.$fetchTestimonial_image[0]['image']));
                if($request->hasFile('image')){
                    $file = $request->file('image');
                        $filename = time().$file->getClientOriginalName();
                        $path = public_path().'/uploads/';
                        $file->move($path, $filename);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'testimonial image can not empty';
                    return response()->json($response, 403);
                }
                $updated = Testimonal::where('id', $request->id)->update(['image' => $filename]);
                if($updated){
                    return response()->json([
                        'status'=>true,
                        'success'=>"Testimonal image updated successfully!"
                       ],200);
                }else{
                    $response['status'] = 'error';
                    $response['message'] = 'Somthing wrong in updation';
                    return response()->json($response, 403);
                }
            }
         }catch(Exception $e){
              return "error";
           }
    }

}
