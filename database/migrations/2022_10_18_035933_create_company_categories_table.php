<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_categories', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('sub_category_id')->unsigned()->nullable();
            $table->bigInteger('site_id')->unsigned()->nullable();
            $table->boolean('active')->default(true);
            $table->mediumText('kiosk_image_primary')->nullable();      
            $table->mediumText('kiosk_image_top')->nullable();     
            $table->mediumText('online_image_primary')->nullable();
            $table->mediumText('online_image_top')->nullable();     
            $table->mediumText('mobile_image_primary')->nullable();
            $table->mediumText('mobile_image_top')->nullable();     
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('sub_category_id')->references('id')->on('categories');
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
        Schema::dropIfExists('company_categories');
    }
}
