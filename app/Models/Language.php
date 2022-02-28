<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    protected $table ="language";

    protected $fillable =[
        'language_name',
        'updated_at',
        'created_at'
    ];
}
