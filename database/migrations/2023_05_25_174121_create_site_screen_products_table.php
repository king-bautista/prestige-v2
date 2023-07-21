<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteScreenProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_screen_products', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->string('serial_number')->nullable();
            $table->bigInteger('site_screen_id')->unsigned();
            $table->string('ad_type')->nullable();
            $table->text('description')->nullable();
            $table->string('dimension')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->integer('sec_slot')->default(0);
            $table->integer('slots')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('is_exclusive')->default(false);
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('site_screen_products');
    }
}
