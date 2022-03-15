<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table ="transaction";

    protected $fillable =[
        'user_id',
        'course_id',
        'email',
        'transaction_id',
        'created_at',
        'updated_at'
    ];
}