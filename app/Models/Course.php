<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table ="course";
    protected $fillable =[
        'course_title',
        'course_description',
        'subject',
        'category_id',
        'language_id',
        'grade_label',
        'course_banner',
        'course_content',
        'course_fee',
        'seller_id',
        'verify',
        'created_at',
        'updated_at',
    ];
}