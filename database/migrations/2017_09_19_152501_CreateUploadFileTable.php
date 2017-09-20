<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_file', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category', 10);
            $table->string('storage_name', 50)->nullable();
            $table->string('original_name', 50)->nullable();
            $table->string('mimetype', 50);
            $table->string('extension', 50);
            $table->integer('size');
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
        Schema::drop('upload_file');
    }
}
