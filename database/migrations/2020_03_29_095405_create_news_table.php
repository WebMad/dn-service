<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dn_news_id');
            $table->text('text');
            $table->string('topic_name');
            $table->bigInteger('views_count');
            $table->bigInteger('author_uid');
            $table->timestamp('dn_created_at');
            $table->bigInteger('school_id');
            $table->bigInteger('eg_id')->nullable();
            $table->foreignId('thread_id');
            $table->timestamps();

            $table->foreign('thread_id')
                ->on('threads')
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
        Schema::dropIfExists('news');
    }
}
