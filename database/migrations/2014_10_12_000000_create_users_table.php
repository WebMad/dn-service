<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('person_id')->unique();
            $table->bigInteger('dn_uid')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('login')->nullable()->unique();
            $table->string('short_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamp('birthday')->nullable();
            $table->bigInteger('school_id')->nullable();
            $table->bigInteger('eg_id')->nullable();
            $table->foreignId('dn_cookies_file_id')->nullable();
            $table->string('dn_access_token')->nullable();
            $table->timestamps();

            $table->foreign('dn_cookies_file_id')
                ->on('dn_cookies_files')
                ->references('id')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
