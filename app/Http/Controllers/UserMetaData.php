<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserMeta;
class UserMetaData extends Controller
{
    public function add_meta($uid,$meta_key,$meta_value){
        $usermeta = UserMeta::Create(
            [
                'user_id' => $uid,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
            ]
        );
    }
    public function get_meta_data($uid,$meta_key){
        $meta_result = DB::table('user_meta')
        ->where('user_id', $uid)->get();
        foreach($meta_result as $meta_data){
             if($meta_data->meta_key == $meta_key){
               $meta_value  = $meta_data->meta_value;
                return $meta_value;
             }
        }
    }
}
