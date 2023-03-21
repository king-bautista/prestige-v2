<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExclusiveScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclusive_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_screen_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_screen_id')->references('id')->on('site_screens');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exclusive_screens');
    }
}
