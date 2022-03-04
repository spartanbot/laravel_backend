<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table ="order";

    protected $fillable =[
        'user_id',
        'status',
        'subtotal',
        'total',
        'promo',
        'discount',
        'grandtotal',
        'fullname',
        'email'
    ];
}
