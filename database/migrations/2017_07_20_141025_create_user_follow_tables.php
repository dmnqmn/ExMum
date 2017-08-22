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

        // Schema::create('user_follow', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('uid');
        //     $table->integer('follow_uid');
        //     $table->timestamps();
        //  });

        Schema::create('photo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 200);
            $table->integer('uid');
            $table->unsignedTinyInteger('tag1')->nullable()->comment('Home feed');
            $table->unsignedTinyInteger('tag2')->nullable()->comment('Popular');
            $table->unsignedTinyInteger('tag3')->nullable()->comment('Everything');
            $table->unsignedTinyInteger('tag4')->nullable()->comment('Gifts');
            $table->unsignedTinyInteger('tag5')->nullable()->comment('Videos');
            $table->unsignedTinyInteger('tag6')->nullable()->comment('Animals and pets');
            $table->unsignedTinyInteger('tag7')->nullable()->comment('Architecture');
            $table->unsignedTinyInteger('tag8')->nullable()->comment('Art');
            $table->unsignedTinyInteger('tag9')->nullable()->comment('Cars and motocyles');
            $table->unsignedTinyInteger('tag10')->nullable()->comment('Celebrities');
            $table->unsignedTinyInteger('tag11')->nullable()->comment('DIY and crafts');
            $table->unsignedTinyInteger('tag12')->nullable()->comment('Design');
            $table->unsignedTinyInteger('tag13')->nullable()->comment('Education');
            $table->unsignedTinyInteger('tag14')->nullable()->comment('Entertainment');
            $table->unsignedTinyInteger('tag15')->nullable()->comment('Food and drink');
            $table->unsignedTinyInteger('tag16')->nullable()->comment('Gardening');
            $table->unsignedTinyInteger('tag17')->nullable()->comment('Geek');
            $table->unsignedTinyInteger('tag18')->nullable()->comment('Hair and beauty');
            $table->unsignedTinyInteger('tag19')->nullable()->comment('Health and fitness');
            $table->unsignedTinyInteger('tag20')->nullable()->comment('History');
            $table->unsignedTinyInteger('tag21')->nullable()->comment('Holidays and events');
            $table->unsignedTinyInteger('tag22')->nullable()->comment('Humor');
            $table->timestamps();
         });

    //     Schema::create('copy_photo', function (Blueprint $table) {
    //         $table->increments('id');
    //         $table->integer('uid');
    //         $table->integer('photo_id');
    //         $table->integer('photo_book_id');
    //         $table->timestamps();
    //     });

    //     Schema::create('photo_book', function (Blueprint $table) {
    //         $table->increments('id');
    //         $table->integer('uid');
    //         $table->string('name');
    //         $table->timestamps();
    //     });
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
