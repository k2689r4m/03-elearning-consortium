<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('second_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('month');
//            $table->unique(['year', 'month']);
            $table->string('lectureName')->nullable();
            $table->tinyInteger('grades')->nullable();
            $table->string('subjectClassification')->nullable();
            $table->string('professorUnivName')->nullable();
            $table->string('professorName')->nullable();
            $table->unsignedSmallInteger('overallSatisfactionRate')->nullable();
            $table->unsignedSmallInteger('selfEvaluationRate')->nullable();
            $table->unsignedSmallInteger('lectureSupportRate')->nullable();
            $table->unsignedSmallInteger('learningContentEvaluationRate')->nullable();
            $table->unsignedSmallInteger('evaluationRate')->nullable();
            $table->unsignedSmallInteger('operatorEvaluationRate')->nullable();
            $table->unsignedSmallInteger('systemEvaluationRate')->nullable();
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
        Schema::dropIfExists('second_totals');
    }
}
