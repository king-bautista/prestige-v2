<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('site_screen_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('site_screen_id')->references('id')->on('site_screens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_screens');
    }
}
