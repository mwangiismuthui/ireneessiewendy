<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('user_id');
            $table->string('type');
            $table->string('status')->default('1');
            $table->string('text')->nullable();
            $table->string('tags')->nullable();
            $table->string('file_path')->nullable();
            $table->string('location')->nullable();
            $table->string('views')->nullable();
            $table->string('dummy')->nullable();
            $table->string('has_link')->nullable();
            $table->string('background_color')->nullable();
            $table->string('backlink')->nullable();
            $table->string('thumbnails')->nullable();
            $table->string('videopreview')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('posts');
    }
}
