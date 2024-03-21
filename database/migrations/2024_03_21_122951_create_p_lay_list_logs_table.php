<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePLayListLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_list_logs', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->bigInteger('site_screen_id')->unsigned();
            $table->bigInteger('advertisement_id')->unsigned();
            $table->bigInteger('log_count')->unsigned();
            $table->date('log_date')->nullable();
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
        Schema::dropIfExists('play_list_logs');
    }
}
