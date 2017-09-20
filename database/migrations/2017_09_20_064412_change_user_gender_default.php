<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserGenderDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('information', 255)->change();
            $table->renameColumn('information', 'description');
            $table->string('first_name', 10)->nullable()->change();
            $table->string('last_name', 10)->nullable()->change();
            $table->integer('gender')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('description', 50)->change();
            $table->renameColumn('description', 'information');
            $table->string('first_name', 10)->nullable(false)->change();
            $table->string('last_name', 10)->nullable(false)->change();
            $table->string('gender', 20)->default(false)->change();
        });
    }
}
