<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resourse extends Model
{
    use HasFactory;
    protected $table ="resourse";

    protected $fillable =[
        'resourse_title',
        'resourse_description',
        'price',
        'seller_id',
        'resourse_content',
        'verify',
    ];
}
