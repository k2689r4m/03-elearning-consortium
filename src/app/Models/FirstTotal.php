<?php

namespace App\Models;

use App\Casts\SatisfactionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\PercentageCast;

class FirstTotal extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'lectureCount',
        'memberCount',
        'attendanceRate',
        'lateRate',
        'absenceRate',
        'learningProgressRate',
        'completionRate',
//        'overSixtyRate',
        'testApplicationRate',
        'taskPerformanceRate',
        'discussionParticipationRate',
        'quizProgressRate',
        'learningParticipationRate',
        'satisfactionRate',
        'selfEvaluationSatisfactionRate',
        'lectureSupportSatisfactionRate',
        'overallSatisfactionRate',
        'learningEvaluationSatisfactionRate',
        'educationOperationSatisfactionRate',
        'overallSystemSatisfactionRate',
        'totalSatisfactionRate',
    ];

    protected $casts = [
        'attendanceRaQte' => PercentageCast::class,
        'lateRate' => PercentageCast::class,
        'absenceRate' => PercentageCast::class,
        'learningProgressRate' => PercentageCast::class,
        'completionRate' => PercentageCast::class,
//        'overSixtyRate' => PercentageCast::class,
        'testApplicationRate' => PercentageCast::class,
        'taskPerformanceRate' => PercentageCast::class,
        'discussionParticipationRate' => PercentageCast::class,
        'quizProgressRate' => PercentageCast::class,
        'learningParticipationRate' => PercentageCast::class,
        'satisfactionRate' => SatisfactionCast::class,
        'selfEvaluationSatisfactionRate' => SatisfactionCast::class,
        'lectureSupportSatisfactionRate' => SatisfactionCast::class,
        'overallSatisfactionRate' => SatisfactionCast::class,
        'learningEvaluationSatisfactionRate' => SatisfactionCast::class,
        'educationOperationSatisfactionRate' => SatisfactionCast::class,
        'overallSystemSatisfactionRate' => SatisfactionCast::class,
        'totalSatisfactionRate' => SatisfactionCast::class,
    ];
}
