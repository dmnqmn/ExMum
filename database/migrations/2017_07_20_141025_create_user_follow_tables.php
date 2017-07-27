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

         Schema::create('user_follow', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('follow_uid');
            $table->timestamps();
         });

        Schema::create('photo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 50);
            $table->integer('uid');
            $table->unsignedTinyInteger('tag1')->nullable();
            $table->unsignedTinyInteger('tag2')->nullable();
            $table->unsignedTinyInteger('tag3')->nullable();
            $table->unsignedTinyInteger('tag4')->nullable();
            $table->unsignedTinyInteger('tag5')->nullable();
            $table->unsignedTinyInteger('tag6')->nullable();
            $table->unsignedTinyInteger('tag7')->nullable();
            $table->unsignedTinyInteger('tag8')->nullable();
            $table->unsignedTinyInteger('tag9')->nullable();
            $table->unsignedTinyInteger('tag10')->nullable();
            $table->unsignedTinyInteger('tag11')->nullable();
            $table->unsignedTinyInteger('tag12')->nullable();
            $table->unsignedTinyInteger('tag13')->nullable();
            $table->unsignedTinyInteger('tag14')->nullable();
            $table->unsignedTinyInteger('tag15')->nullable();
            $table->unsignedTinyInteger('tag16')->nullable();
            $table->unsignedTinyInteger('tag17')->nullable();
            $table->unsignedTinyInteger('tag18')->nullable();
            $table->unsignedTinyInteger('tag19')->nullable();
            $table->unsignedTinyInteger('tag20')->nullable();
            $table->unsignedTinyInteger('tag21')->nullable();
            $table->unsignedTinyInteger('tag22')->nullable();
            $table->timestamps();
         });

        Schema::create('copy_photo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('photo_id');
            $table->integer('photo_book_id');
            $table->timestamps();
        });

        Schema::create('photo_book', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->string('name');
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
