<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoMoreData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photo', function (Blueprint $table) {
            $table->integer('gallery_id')->after('uid');
            $table->integer('original_id')->after('gallery_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photo', function (Blueprint $table) {
            $table->dropColumn(['gallery_id', 'original_id']);
        });
    }
}
