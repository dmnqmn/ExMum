<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 10);
            $table->string('last_name', 10);
            $table->string('user_name', 50);
            $table->string('gender', 20);
            $table->string('password', 255);
            $table->string('phone', 12)->unique()->nullable();
            $table->string('email', 50)->unique();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('avatar', 50)->nullable();
            $table->string('infomation', 50)->nullable();
            $table->string('tags', 50)->nullable();
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
        Schema::drop('user');
    }
}
