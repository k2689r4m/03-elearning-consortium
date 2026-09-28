<?php

namespace App\Models;

use App\Casts\PercentageCast;
use App\Casts\SatisfactionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondTotal extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'lectureName',
        'grades',
        'subjectClassification',
        'professorUnivName',
        'professorName',
        'overallSatisfactionRate',
        'selfEvaluationRate',
        'lectureSupportRate',
        'learningContentEvaluationRate',
        'evaluationRate',
        'operatorEvaluationRate',
        'systemEvaluationRate',
        'totalSatisfactionRate',
    ];

    protected $casts = [
        'overallSatisfactionRate' => SatisfactionCast::class,
        'selfEvaluationRate' => SatisfactionCast::class,
        'lectureSupportRate' => SatisfactionCast::class,
        'learningContentEvaluationRate' => SatisfactionCast::class,
        'evaluationRate' => SatisfactionCast::class,
        'operatorEvaluationRate' => SatisfactionCast::class,
        'systemEvaluationRate' => SatisfactionCast::class,
        'totalSatisfactionRate' => SatisfactionCast::class,
    ];
}
