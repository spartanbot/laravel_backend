<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BasicInformation;
use Illuminate\Support\Facades\Hash;

class BasicInformationController extends Controller
{
    public function basicCreate(Request $request){

        $response=[];

        if($request['first_name'] ==''){
            $response['first_name']= 'Please enter first name';
        }

        if($request['last_name'] ==''){
            $response['last_name']= 'Please enter last name';
        }

        if($request['user_name'] ==''){
            $response['user_name']= 'Please enter user name';
        }
        if($request['i_am_a'] ==''){
            $response['i_am_a']= 'This field is required';
        }

        if($request['change_password'] ==''){
            $response['change_password']= 'Please enter password';
        }
         
        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{
            try{
            $basicinformation = new BasicInformation;
            $basicinformation->first_name = $request->first_name; 
            $basicinformation->last_name = $request->last_name; 
            $basicinformation->user_name = $request->user_name; 
            $basicinformation->i_am_a = $request->i_am_a; 
            $basicinformation->password =Hash::make($request->password); 
            $basicinformation->save();
            return response()->json([
                'status' => true,
                'message' => 'Basic Information created successfully!'
              
             ],201);
            }catch(Exception $e){
              return "error";

            }

         }

    }

    public function basicEdit(Request $request,$id){
         try{
             
         $basicinformation=BasicInformation::where('id','=',$id)->where('status',1)->first();

         return response()->json([
            'error'=>false,
            'response'=>$basicinformation
        ]);

        }catch(Exception $e){
           return "error";
        }

    }

    public function basicUpdate(Request $request){

        $response=[];

        if($request['first_name'] ==''){
            $response['first_name']= 'Please enter first name';
        }

        if($request['last_name'] ==''){
            $response['last_name']= 'Please enter last name';
        }

        if($request['user_name'] ==''){
            $response['user_name']= 'Please enter user name';
        }
        if($request['i_am_a'] ==''){
            $response['i_am_a']= 'This field is required';
        }
         
        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{
            try{
                 $info_update = BasicInformation::where('id', $request->id)->update([
                'first_name' => $request->first_name,
                'last_name'=> $request->last_name,
                'user_name'=> $request->user_name,
                'i_am_a'=> $request->i_am_a
           
              ]);
           return response()->json([
            'error'=>false,
            'msg'=>"Basic Information updated successfully!"
        ]);

            }catch(Exception $e){
                return "error";
            }
        }

    }

    public function basicDelete(Request $request,$id){

        try{
          $info_delete = BasicInformation::where('id',$id)->update([
            'status' =>'0'
          ]);
         return response()->json([
            'error'=>false,
            'msg'=>"Delete recard successfully!"]);
        }catch(Exception $e){
         return "error";
        }

    }

    public function allInfo(Request $request){

        $basicinfo=BasicInformation::where('status','=','1')->orderBy('created_at','desc')->get()->toArray();
         if($basicinfo){
            return response()->json(['error'=>false, 'response'=>$basicinfo]);
         }else{
            return response()->json(['error'=>true, 'response'=>$basicinfo]);
         }

    }

}
