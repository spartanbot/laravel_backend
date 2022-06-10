<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratereview extends Model
{
    use HasFactory;
    protected $table ="ratereview";
    protected $fillable =[
        'user_id',
        'course_id',
        'rating',
        'name',
        'title',
        'description'
    ];
}
