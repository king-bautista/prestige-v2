<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sites', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('site_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('site_id')->references('id')->on('sites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sites');
    }
}
