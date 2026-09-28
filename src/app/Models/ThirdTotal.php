<?php

namespace App\Models;

use App\Casts\PercentageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdTotal extends Model
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
        'memberCount',
        'graduatesCount',
        'finalCompletionRate',
        'sum',
        'learningProgressRate',
        'attendanceRate',
        'lateRate',
        'absenceRate',
        'midtermTestTakers',
        'midtermTestApplicationRate',
        'finalTestTakers',
        'finalTestApplicationRate',
        'testApplicationRate',
        'taskPerformanceRate',
        'discussionParticipationRate',
        'quizProgressRate',
        'learningParticipationRate',

        //추가 0621
        'FinalScoreSixtyPointTakers',        //최종성적 60점 이상 취득자수
        'FinalScoreSixtyPointApplicantRate',     //최종성적 60점 이상 취득률
    ];

    protected $casts = [
        'finalCompletionRate' => PercentageCast::class,
        'learningProgressRate' => PercentageCast::class,
        'attendanceRate' => PercentageCast::class,
        'lateRate' => PercentageCast::class,
        'absenceRate' => PercentageCast::class,
        'midtermTestApplicationRate' => PercentageCast::class,
        'finalTestApplicationRate' => PercentageCast::class,
        'testApplicationRate' => PercentageCast::class,
        'taskPerformanceRate' => PercentageCast::class,
        'discussionParticipationRate' => PercentageCast::class,
        'quizProgressRate' => PercentageCast::class,
        'learningParticipationRate' => PercentageCast::class,


        //추가 0621
        'FinalScoreSixtyPointTakers' => PercentageCast::class,
        'FinalScoreSixtyPointApplicantRate' => PercentageCast::class,
    ];
}

