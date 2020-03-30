<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDnFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dn_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dn_id');
            $table->string('dn_str_id');
            $table->string('name');
            $table->string('type_group');
            $table->string('type');
            $table->text('page_url');
            $table->text('download_url');
            $table->bigInteger('owner_dn_uid');
            $table->bigInteger('size');
            $table->dateTime('uploaded_date');
            $table->string('storage_type');
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
        Schema::dropIfExists('dn_files');
    }
}
