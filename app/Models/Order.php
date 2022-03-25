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
        'total',
        'fullname',
        'email',
        'charge_id',
        'transaction_id'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class,'order_id','id');
    }
}