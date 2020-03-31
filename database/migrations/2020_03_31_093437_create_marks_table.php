<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dn_id');
            $table->string('dn_str_id');
            $table->string('type')->nullable();
            $table->integer('value')->nullable();
            $table->string('textValue')->nullable();
            $table->bigInteger('person_id');
            $table->string('person_str_id')->nullable();
            $table->bigInteger('homework_id')->nullable();
            $table->string('homework_str_id')->nullable();
            $table->bigInteger('lesson_id')->nullable();
            $table->string('lesson_str_id')->nullable();
            $table->integer('number')->nullable();
            $table->dateTime('date')->nullable();
            $table->bigInteger('work_type')->nullable();
            $table->string('mood')->nullable();
            $table->boolean('use_avg_calc')->default(true);
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
        Schema::dropIfExists('marks');
    }
}
