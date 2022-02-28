<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    use HasFactory;

    protected $table ="verify_users";

    protected $fillable =['user_id','token','createdDate'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
