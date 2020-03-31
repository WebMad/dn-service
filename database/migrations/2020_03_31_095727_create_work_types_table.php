<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dn_id');
            $table->bigInteger('school_id');
            $table->string('abbreviation');
            $table->string('name');
            $table->boolean('is_final')->default(false);
            $table->boolean('is_important')->default(false);
            $table->integer('kind_id');
            $table->string('kind');
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
        Schema::dropIfExists('work_types');
    }
}
