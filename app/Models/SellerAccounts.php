<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerAccounts extends Model
{
    use HasFactory;
    protected $table ="sellers_accounts";

    protected $fillable =[
        'user_id',
        'bankToken',
        'stripeAccount',
        'bankAccount',
        'status',
    ];
}
