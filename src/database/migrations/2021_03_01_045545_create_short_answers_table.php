<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('lectureId');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('month');
//            $table->string('lectureName');
            $table->string('positive');
            $table->string('negative');
            $table->string('wordCloudName');
            $table->string('wordCloudPathName');
            $table->timestamps();

            $table->foreign('userId')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('lectureId')
                ->references('id')
                ->on('third_totals')
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
        Schema::dropIfExists('short_answers');
    }
}
