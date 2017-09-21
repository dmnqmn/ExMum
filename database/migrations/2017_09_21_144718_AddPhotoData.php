<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photo', function (Blueprint $table) {
            $table->dropColumn(['url']);
            $table->integer('file_id');
            $table->string('name', 20);
            $table->string('description', 300);
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
            $table->string('url', 200);
            $table->dropColumn(['file_id', 'name', 'description']);
        });
    }
}
