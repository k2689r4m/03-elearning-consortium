<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThirdTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('month');
            $table->string('lectureName')->nullable();
            $table->tinyInteger('grades')->nullable();
            $table->string('subjectClassification')->nullable();
            $table->string('professorUnivName')->nullable();
            $table->string('professorName')->nullable();
            $table->unsignedBigInteger('memberCount')->nullable();
            $table->unsignedBigInteger('graduatesCount')->nullable();
            $table->unsignedSmallInteger('finalCompletionRate')->nullable();
            $table->integer('sum')->nullable();
            $table->unsignedSmallInteger('learningProgressRate')->nullable();
            $table->unsignedSmallInteger('attendanceRate')->nullable();
            $table->unsignedSmallInteger('lateRate')->nullable();
            $table->unsignedSmallInteger('absenceRate')->nullable();
            $table->unsignedBigInteger('midtermTestTakers')->nullable();
            $table->unsignedSmallInteger('midtermTestApplicationRate')->nullable();
            $table->unsignedBigInteger('finalTestTakers')->nullable();
            $table->unsignedSmallInteger('finalTestApplicationRate')->nullable();
            $table->unsignedSmallInteger('testApplicationRate')->nullable();
            $table->unsignedSmallInteger('taskPerformanceRate')->nullable();
            $table->unsignedSmallInteger('discussionParticipationRate')->nullable();
            $table->unsignedSmallInteger('quizProgressRate')->nullable();
            $table->unsignedSmallInteger('learningParticipationRate')->nullable();


            //추가 0621
            $table->unsignedBigInteger('FinalScoreSixtyPointTakers')->nullable();
            $table->unsignedSmallInteger('FinalScoreSixtyPointApplicantRate')->nullable();
            ////////////////////////////////////////////////////
            $table->timestamps();

            $table->foreign('userId')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('third_totals');
    }
}
