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
            $table->bigIncrements('id');
            $table->string('lastname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('profile_pic_path')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_reported')->default(0);
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('DOB')->nullable();
            $table->string('gender')->nullable();
            $table->longText('about')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
