<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeKeys extends Model
{
    use HasFactory;
    protected $table ="stripe_keys";

    protected $fillable =[
        'publishable_key',
        'secret_key'
    ];
}
