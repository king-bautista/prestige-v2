<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteMapPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_map_paths', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('point_orig')->unsigned();
            $table->bigInteger('point_dest')->unsigned();
            $table->mediumText('path');
            $table->decimal('distance', 10, 2);
            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_screen_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
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
        Schema::dropIfExists('site_map_paths');
    }
}
