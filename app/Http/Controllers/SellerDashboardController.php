<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;

class SellerDashboardController extends Controller
{
    public function seller_create(Request $request){

        $response=[];

        if($request['product_name'] ==''){
            $response['product_name']= 'Please enter product name';
        }

        if($request['subject'] ==''){
            $response['subject']= 'Please enter subject';
        }

        if($request['category'] ==''){
            $response['category']= 'Please enter category';
        }

        if($request['language'] ==''){
            $response['language']= 'Please enter language';
        }
        if($request['grade_level'] ==''){
            $response['grade_level']= 'Please enter grade level';
        }
        if($request['seller_image'] ==''){
            $response['seller_image']= 'Please enter seller image';
        }

        if($request['description'] ==''){
            $response['description']= 'Please enter description';
        }

         if($request['price'] ==''){
            $response['price']= 'Please enter price';
        }


        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{
               $path = $request->file('seller_image')->store('/images/seller');

               $sellers = new Seller;
               $sellers->product_name =$request->product_name;
               $sellers->subject =$request->subject;
               $sellers->category =$request->category;
               $sellers->language =$request->language;
               $sellers->grade_level =$request->grade_level;
               $sellers->seller_image =$path;
               $sellers->description =$request->description;
               $sellers->price =$request->price;
               $sellers->save();             
               return response()->json([
              'message' => 
              'sellers create successfully!',
              'sellers' => $sellers
              ,],201);

         }

    }

    public function seller_update(Request $request){

        $response=[];

        if($request['product_name'] ==''){
            $response['product_name']= 'Please enter product name';
        }

        if($request['subject'] ==''){
            $response['subject']= 'Please enter subject';
        }

        if($request['category'] ==''){
            $response['category']= 'Please enter category';
        }

        if($request['language'] ==''){
            $response['language']= 'Please enter language';
        }
        if($request['grade_level'] ==''){
            $response['grade_level']= 'Please enter grade level';
        }
        if($request['seller_image'] ==''){
            $response['seller_image']= 'Please enter seller image';
        }

        if($request['description'] ==''){
            $response['description']= 'Please enter description';
        }

         if($request['price'] ==''){
            $response['price']= 'Please enter price';
        }


        if(count($response)){
            $response['error']= true;
            return response()->json($response);
         }else{
            $path = $request->file('seller_image')->store('/images/seller');

            $seller_update = Seller::where('id', $request->id)->update([
                'product_name' => $request->product_name,
                'subject'=> $request->subject,
                'category'=> $request->category,
                'language'=> $request->language,
                'grade_level'=> $request->grade_level,
                'seller_image'=> $path,
                'description'=> $request->description,
                'price'=> $request->price
              ]);
            return response()->json([
            'error'=>false,
            'msg'=>"seller updated successfully!"
           ]);
         }

    }


    public function seller_edit($id){

         try{
         $seller=Seller::where('id','=',$id)->where('status',1)->first();

         return response()->json([
            'error'=>false,
            'response'=>$seller
        ]);

        }catch(Exception $e){
           return "error";
        }

    }

    public function seller_delete($id){
          try{
          $seller_delete = Seller::where('id',$id)->update([
            'status' =>'0'
          ]);
         return response()->json([
            'error'=>false,
            'msg'=>"Delete recard successfully!"]);
        }catch(Exception $e){
         return "error";
        }

    }

    public function allSeller(){
        
        $sellers=Seller::where('status','=','1')->orderBy('created_at','desc')->get()->toArray();
         if($sellers){
            return response()->json([
                'error'=>false, 
                'response'=>$sellers
            ]);
         }else{
            return response()->json([
                'error'=>true,
                'response'=>$sellers
            ]);
          }
    }
}
