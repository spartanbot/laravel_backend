<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $table ="order_item";

    protected $fillable =[
        'user_id',
        'course_id',
        'order_id',
        'course_name',
        'course_fee',
        'discount',
    ];
}