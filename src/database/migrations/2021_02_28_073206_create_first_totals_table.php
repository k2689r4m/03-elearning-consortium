<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('first_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('month');
            $table->unique(['year', 'month']);
            $table->unsignedSmallInteger('lectureCount')->nullable();
            $table->unsignedSmallInteger('memberCount')->nullable();
            $table->unsignedSmallInteger('attendanceRate')->nullable();
            $table->unsignedSmallInteger('lateRate')->nullable();
            $table->unsignedSmallInteger('absenceRate')->nullable();
            $table->unsignedSmallInteger('learningProgressRate')->nullable();
            $table->unsignedSmallInteger('completionRate')->nullable();
            $table->unsignedSmallInteger('overSixtyRate')->nullable();
            $table->unsignedSmallInteger('testApplicationRate')->nullable();
            $table->unsignedSmallInteger('taskPerformanceRate')->nullable();
            $table->unsignedSmallInteger('discussionParticipationRate')->nullable();
            $table->unsignedSmallInteger('quizProgressRate')->nullable();
            $table->unsignedSmallInteger('learningParticipationRate')->nullable();
            $table->unsignedSmallInteger('satisfactionRate')->nullable();
            $table->unsignedSmallInteger('selfEvaluationSatisfactionRate')->nullable();
            $table->unsignedSmallInteger('lectureSupportSatisfactionRate')->nullable();
            $table->unsignedSmallInteger('overallSatisfactionRate')->nullable();
            $table->unsignedSmallInteger('learningEvaluationSatisfactionRate')->nullable();
            $table->unsignedSmallInteger('educationOperationSatisfactionRate')->nullable();
            $table->unsignedSmallInteger('overallSystemSatisfactionRate')->nullable();
            $table->unsignedSmallInteger('totalSatisfactionRate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('first_totals');
    }
}
