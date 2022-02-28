<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Exception;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addLanguage(Request $request)
    {

        $response = [];

        if ($request['language_name'] == '') {
            $response['language_name'] = 'Please enter language name';
        }

        if (count($response)) {
            $response['status'] = 'error';
            return response()->json($response, 403);
        } else {
            try {
                $fetchLanguage = Language::where('language_name','=',$request->language_name)->first();
                if($fetchLanguage){
                  return response()->json([
                      'status' => false,
                      'message' => 'Language already exist!'
                   ],403);
                }else{
                $language_name  =  Language::create([
                    'language_name' => $request->language_name,
                ]);
                if ($language_name) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Language created successfully!'
                    ], 200);
                }
            }
            } catch (Exception $e) {
                return "error";
            }
        }
    }

    public function editLanguage(Request $request)
    {
        try {
            $fetchCatgory = Language::where('id', '=', $request->id)->first();
            if ($fetchCatgory) {
                return response()->json([
                    'success' => true,
                    'response' => $fetchCatgory
                ], 200);
            }
        } catch (Exception $e) {
            return "error";
        }
    }
    public function fetchAllLanguage()
    {
        try {
            $fetchallCategory = DB::table('language')->get();
            if ($fetchallCategory) {
                return response()->json([
                    'success' => true,
                    'response' => $fetchallCategory
                ], 200);
            }
        } catch (Exception $e) {
            return "error";
        }
    }

    public function updateLanguage(Request $request)
    {

        $response = [];

        if ($request['language_name'] == '') {
            $response['language_name'] = 'Please enter language name';
        }

        if (count($response)) {
            $response['status'] = 'error';
            return response()->json($response, 403);
        } else {
            try {
                $UpdateLanguage = Language::where('id', $request->id)->update([
                    'language_name' => $request->language_name,
                ]);
                if ($UpdateLanguage) {
                    return response()->json([
                        'status' => true,
                        'success' => "Language updated successfully!"
                    ], 200);
                }
            } catch (Exception $e) {
                return "error";
            }
        }
    }
}
