<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dn_id');
            $table->string('title')->nullable();
            $table->dateTime('date');
            $table->integer('number')->nullable();
            $table->bigInteger('subject_id');
            $table->string('status')->nullable();
            $table->bigInteger('result_place_id')->nullable();
            $table->string('building')->nullable();
            $table->string('place')->nullable();
            $table->string('floor')->nullable();
            $table->string('hours')->nullable();
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
        Schema::dropIfExists('lessons');
    }
}
