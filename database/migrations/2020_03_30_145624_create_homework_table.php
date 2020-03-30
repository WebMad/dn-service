<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dn_id');
            $table->string('dn_str_id');
            $table->string('type');
            $table->string('work_type');
            $table->string('mark_type');
            $table->integer('mark_count');
            $table->bigInteger('lesson_id');
            $table->string('lesson_str_id');
            $table->boolean('display_in_journal')->default(true);
            $table->string('status');
            $table->bigInteger('eg_id');
            $table->string('eg_str_id');
            $table->text('text');
            $table->integer('period_number');
            $table->string('period_type');
            $table->bigInteger('subject_dn_id');
            $table->boolean('is_important')->default(false);
            $table->dateTime('target_date');
            $table->dateTime('sent_date');
            $table->bigInteger('created_by');
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
        Schema::dropIfExists('homework');
    }
}
