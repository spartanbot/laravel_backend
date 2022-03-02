<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table ="cart";

    protected $fillable =[
        'user_id',
        'course_id',
        'course_name',
        'course_fee',
        'created_at',
        'updated_at'
    ];
}
