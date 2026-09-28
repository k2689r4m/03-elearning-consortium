<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstPer extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'year',
        'month',
        'content',
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
