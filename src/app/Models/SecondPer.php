<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondPer extends Model
{
    use HasFactory;

    protected $fillable = [
//        'userId',
        'year',
        'month',
        'lectureName',
        'fusion',
        'creative',
        'professionalism',
        'informationCommunication',
        'problemPrediction',
        'utilizationNewTechnology',
        'expression',
        'collaboration',
        'discrimination',
        'adaptation',
        'solution',
    ];

    protected $casts = [
        'fusion' => 'boolean',
        'creative' => 'boolean',
        'professionalism' => 'boolean',
        'informationCommunication' => 'boolean',
        'problemPrediction' => 'boolean',
        'utilizationNewTechnology' => 'boolean',
        'expression' => 'boolean',
        'collaboration' => 'boolean',
        'discrimination' => 'boolean',
        'adaptation' => 'boolean',
        'solution' => 'boolean',
    ];
}
