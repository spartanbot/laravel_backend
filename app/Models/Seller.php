<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

     protected $table ="sellers";

    protected $fillable =[
        'product_name',
        'subject',
        'category',
        'language',
        'grade_level',
        'seller_image',
        'description',
        'price'
    ];
}
