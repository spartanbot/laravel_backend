<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class posts extends Controller
{
    public function dummy(){
        return [
            'name'=>'panch',
            'age'=>24,
            'city'=>'ambala'
        ];
    }
}
