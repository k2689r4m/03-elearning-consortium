<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdPer extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'year',
        'month',
        'imageName',
        'imagePathName',
        'content',
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
