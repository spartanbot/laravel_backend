<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
   

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'full_name',
        'user_email',
        'password',
        'user_status',
        'location',
        'preferred_language',
        'user_name',
        'i_am_a',
        'affiliation',
        'subject',
        'age_group',
        'talent',
        'sample_content',
        'resourse_name',
        'resourse_one_name',
        'resourse_one_email',
        'resourse_one_phonenumber',
        'resourse_two_name',
        'resourse_two_email',
        'resourse_two_phonenumber',
        'verified',
        'course_name',
        'course_ref_name',
        'course_ref_email',
        'course_ref_phonenumber',
        'course_ref_two_name',
        'course_ref_two_email',
        'course_ref_two_phonenumber',
        'token',
        'role',
        'createdDate',
        'user_status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function verifyUser(){
     return $this->hasOne('App\Models\VerifyUser');
    }
}
