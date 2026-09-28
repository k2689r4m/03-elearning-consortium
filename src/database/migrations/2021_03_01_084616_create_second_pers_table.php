<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondPersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('second_pers', function (Blueprint $table) {
            $table->id();
//            $table->unsignedBigInteger('userId');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('month');
            $table->string('lectureName')->nullable();
            $table->unique(['year', 'month', 'lectureName']);
            $table->boolean('fusion')->default(false);
            $table->boolean('creative')->default(false);
            $table->boolean('professionalism')->default(false);
            $table->boolean('informationCommunication')->default(false);
            $table->boolean('problemPrediction')->default(false);
            $table->boolean('utilizationNewTechnology')->default(false);
            $table->boolean('expression')->default(false);
            $table->boolean('collaboration')->default(false);
            $table->boolean('discrimination')->default(false);
            $table->boolean('adaptation')->default(false);
            $table->boolean('solution')->default(false);
            $table->timestamps();



//            $table->foreign('userId')
//                ->references('id')
//                ->on('users')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('second_pers');
    }
}
