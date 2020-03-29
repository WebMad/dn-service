<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thread_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dn_id');
            $table->bigInteger('reply_uid')->nullable();
            $table->bigInteger('author_uid');
            $table->dateTime('dn_created_at');
            $table->text('text');
            $table->foreignId('thread_id');
            $table->timestamps();

            $table->foreign('thread_id')
                ->on('threads')
                ->references('id')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thread_comments');
    }
}
