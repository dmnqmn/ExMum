<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagTableWithPhotoTagAndUserTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creater_uid')->nullable();
            $table->string('name', 20)->unique();
            $table->string('description', 100)->nullable();
            $table->timestamps();
        });

        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('tags');
        });

        Schema::table('photo', function (Blueprint $table) {
            foreach (range(1, 22) as $tagId) {
                $table->dropColumn("tag$tagId");
            }
        });

        Schema::create('user_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('tag_id');
            $table->timestamps();
        });

        Schema::create('photo_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('photo_id');
            $table->integer('tag_id');
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
        Schema::drop('tag');

        Schema::table('user', function (Blueprint $table) {
            $table->string('tags', 50)->nullable();
        });

        Schema::table('photo', function (Blueprint $table) {
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
        });

        Schema::drop('user_tag');
        Schema::drop('photo_tag');
    }
}
