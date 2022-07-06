<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    protected $table ="visitor";

    protected $fillable = [
        'ip',
        'browser_info'

    ];
    protected $casts = [
        'browser_info' => 'array',
    ];
}
