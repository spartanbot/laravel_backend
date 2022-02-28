<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicInformation extends Model
{
    use HasFactory;

    protected $table ="basic_information";

    protected $fillable =[
        'first_name',
        'last_name',
        'user_name',
        'i_am_a',
        'change_password'
    ];
}
