<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBasicinfo extends Model
{
    use HasFactory;

    protected $table ="user_basicinfos";

    protected $fillable = [
        'first_name',
        'last_name',
        'user_type',
        'change_password'

    ];
}
