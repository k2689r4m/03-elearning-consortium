<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFourthTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fourth_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('month');
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
        Schema::dropIfExists('fourth_totals');
    }
}
