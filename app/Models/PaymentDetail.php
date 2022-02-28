<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $table ="payment_details";

    protected $fillable =[
        'email',
        'cart_details',
        'cart_holder_name',
        'billing_address',
        'state',
        'zip',
        'vat_number',
        'discount_code'
    ];
}
