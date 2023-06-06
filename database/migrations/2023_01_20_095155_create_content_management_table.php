<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_management', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->string('serial_number')->nullable();
            $table->bigInteger('material_id')->unsigned()->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('material_id')->references('id')->on('advertisement_materials');
        });

        Schema::create('content_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('content_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_screen_product_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_screen_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_id')->unsigned()->nullable()->index();

            $table->foreign('content_id')->references('id')->on('content_management');
            $table->foreign('site_screen_product_id')->references('id')->on('site_screen_products');
            $table->foreign('site_screen_id')->references('id')->on('site_screens');
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
        Schema::dropIfExists('content_screens');
        Schema::dropIfExists('content_management');
    }
}
