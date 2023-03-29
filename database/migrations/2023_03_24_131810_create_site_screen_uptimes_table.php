<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteScreenUptimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_screen_uptimes', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_screen_id')->unsigned();
            $table->date('up_time_date')->nullable();
            $table->integer('total_hours')->default(0);
            $table->time('opening_hour')->default(0);
            $table->time('closing_hour')->default(0);
            $table->integer('hours_up')->default(0);
            $table->decimal('percentage_uptime', 10, 2);            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_screen_id')->references('id')->on('site_screens');
        });

        Schema::create('site_screen_uptimes_temp', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_screen_id')->unsigned();
            $table->date('up_time_date')->nullable();
            $table->time('up_time_hours')->nullable();
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
        Schema::dropIfExists('site_screen_uptime_temp');
        Schema::dropIfExists('site_screen_uptimes');
    }
}
