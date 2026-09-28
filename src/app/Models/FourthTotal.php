<?php

namespace App\Models;

use App\Casts\PercentageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FourthTotal extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
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
        'overallSatisfactionRate' => PercentageCast::class,
        'selfEvaluationRate' => PercentageCast::class,
        'lectureSupportRate' => PercentageCast::class,
        'learningContentEvaluationRate' => PercentageCast::class,
        'evaluationRate' => PercentageCast::class,
        'operatorEvaluationRate' => PercentageCast::class,
        'systemEvaluationRate' => PercentageCast::class,
        'totalSatisfactionRate' => PercentageCast::class,
    ];
}
