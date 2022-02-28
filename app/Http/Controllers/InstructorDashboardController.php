<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;

class InstructorDashboardController extends Controller
{
    public function instruct_create(Request $request){

        $response=[];

        if($request['course_name'] ==''){
            $response['course_name']= 'name field is required';
        }

        if($request['subject'] ==''){
            $response['subject']= 'name field is required';
        }

        if($request['meet_times'] ==''){
            $response['meet_times']= 'email field is required';
        }

        if($request['meet_minuts'] ==''){
            $response['meet_minuts']= 'password field is required';
        }
        if($request['grade_level'] ==''){
            $response['grade_level']= 'Please enter location';
        }
        if($request['instructor_image'] ==''){
            $response['instructor_image']= 'Please enter preferred language';
        }

        if($request['instructor_description'] ==''){
            $response['instructor_description']= 'This field is required';
        }

         if($request['instructor_amount'] ==''){
            $response['instructor_amount']= 'This field is required';
        }


        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{
               $path = $request->file('instructor_image')->store('/images/instructor');

               $instructor = new Instructor;
               $instructor->course_name =$request->course_name;
               $instructor->subject =$request->subject;
               $instructor->meet_times =$request->meet_times;
               $instructor->meet_minuts =$request->meet_minuts;
               $instructor->grade_level =$request->grade_level;
               $instructor->instructor_description =$request->instructor_description;
               $instructor->instructor_image =$path;
               $instructor->instructor_amount =$request->instructor_amount;
               $instructor->save();             
               return response()->json([
              'message' => 'instructor create successfully!',
              'instructor' => $instructor,],201);

         }

    }

    public function instrct_update(Request $request){

        $response=[];

        if($request['course_name'] ==''){
            $response['course_name']= 'name field is required';
        }

        if($request['subject'] ==''){
            $response['subject']= 'name field is required';
        }

        if($request['meet_times'] ==''){
            $response['meet_times']= 'email field is required';
        }

        if($request['meet_minuts'] ==''){
            $response['meet_minuts']= 'password field is required';
        }
        if($request['grade_level'] ==''){
            $response['grade_level']= 'Please enter location';
        }
        if($request['instructor_image'] ==''){
            $response['instructor_image']= 'Please enter preferred language';
        }

        if($request['instructor_description'] ==''){
            $response['instructor_description']= 'This field is required';
        }

         if($request['instructor_amount'] ==''){
            $response['instructor_amount']= 'This field is required';
        }


        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{

            try{
                
               $path = $request->file('instructor_image')->store('/images/instructor');

               $instructor_update = Instructor::where('id', $request->id)->update([
                'course_name' => $request->course_name,
                'subject'=> $request->subject,
                'meet_times'=> $request->meet_times,
                'meet_minuts'=> $request->meet_minuts,
                'instructor_image'=> $path,
                'instructor_description'=> $request->instructor_description,
                'instructor_amount'=> $request->instructor_amount
              ]);
            return response()->json([
            'error'=>false,
            'msg'=>"instructor updated successfully!"
           ]);
            }catch(Exception $e){

                return "error";
            }
           
         }

    }

    public function instrct_edit(Request $request,$id){

        try{
         $instructor=Instructor::where('id','=',$id)->where('status',1)->first();

         return response()->json([
            'error'=>false,
            'response'=>$instructor
        ]);

        }catch(Exception $e){
           return "error";
        }

    }


    public function instrct_delete(Request $request,$id){

        try{
          $instructor_delete = Instructor::where('id',$id)->update([
            'status' =>'0'
          ]);
         return response()->json([
            'error'=>false,
            'msg'=>"Delete recard successfully!"]);
        }catch(Exception $e){
         return "error";
        }

    }


    public function getAllInscrtuct(Request $request){
        try{
        $instructor=Instructor::where('status','=','1')->orderBy('created_at','desc')->get()->toArray();
         if($instructor){
            return response()->json(['error'=>false, 'response'=>$instructor]);
         }else{
            return response()->json(['error'=>true, 'response'=>$instructor]);
          }

        }catch(Exception $e){

            return "error";
        }
        
    }
}
