<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Exception;


class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function createCategory(Request $request){

        $response=[];

        if($request['category_name'] ==''){
            $response['category_name']= 'Please enter category name';
        }

        if($request['description'] ==''){
            $response['description']= 'Please enter category description';
        }
 
        if(count($response)){
            $response['status']= 'error';
            return response()->json($response,403);
         }else{
            try {
              $fetchCatgory = Category::where('category_name','=',$request->category_name)->first();
              if($fetchCatgory){
                return response()->json([
                    'status' => false,
                    'message' => 'Category already exist'
                 ],403);
              }else{
                $category  =  Category::create([
                    'category_name' => $request->category_name,
                    'description' => $request->description
                ]);
                if($category){
                    return response()->json([
                        'status' => true,
                        'message' => 'Category created successfully'
                     ],200);
                }
              }
            } catch (Exception $e) {
                return "error";
            }
            
         }
}

public function editCategory(Request $request){
    try{
        $fetchCatgory = Category::where('id','=',$request->id)->first();
        if($fetchCatgory){
            return response()->json([
                'success'=>true,
                'response'=>$fetchCatgory
            ],200);
        }
       }catch(Exception $e){
          return "error";
       }

}

public function fetchAllCategory(Request $request){
    try{
        $fetchallCategory = DB::table('category')->whereBetween('created_at',array($request->start_date,$request->end_date))
        ->get();
        if($fetchallCategory){
            return response()->json([
                'success'=>true,
                'response'=>$fetchallCategory
            ],200);
        }
       }catch(Exception $e){
        return $e;
       }

}

public function updateCategory(Request $request){

    $response=[];

    if($request['category_name'] ==''){
        $response['category_name']= 'Please enter category name';
    }
     
    if(count($response)){
        $response['status']= 'error';
        return response()->json($response,403);
     }else{
        try {
            $UpdateCategory= Category::where('id', $request->id)->update([
                'category_name' => $request->category_name,
                'description' => $request->description
          ]);
          if($UpdateCategory){
            return response()->json([
                'status'=>true,
                'success'=>"Category updated successfully"
               ],200);
          }
        } catch (Exception $e) {
            return "error";
        }
        
     }

}

}