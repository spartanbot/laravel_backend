<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonal extends Model
{
    use HasFactory;
    protected $table ="testimonal";

    protected $fillable =[
        'title',
        'grade',
        'school',
        'location',
        'description',
        'image',
    ];
}