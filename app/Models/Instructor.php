<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;
    protected $table ="instructors";

    protected $fillable =[
        'course_name',
        'subject',
        'meet_times',
        'meet_minuts',
        'grade_level',
        'instructor_image',
        'instructor_description',
        'instructor_amount'
    ];
}



