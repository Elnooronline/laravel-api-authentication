<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResetPasswordTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_password_reset_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->index()->nullable();
            $table->string('mobile')->index()->nullable();
            $table->string('code');
            $table->timestamp('updated_at');
        });

        Schema::create('api_password_reset_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('token');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_password_reset_tokens');
        Schema::dropIfExists('api_password_reset_codes');
    }
}
