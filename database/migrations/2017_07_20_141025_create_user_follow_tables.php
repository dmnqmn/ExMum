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
            $table->string('name', 20);
            $table->string('password', 255);
            $table->integer('phone')->nullable()->unique();
            $table->string('email', 50)->nullable()->unique();
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
