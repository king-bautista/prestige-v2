<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned()->nullable()->index();
            $table->bigInteger('contract_id')->unsigned()->nullable()->index();
            $table->bigInteger('brand_id')->unsigned()->nullable()->index();
            $table->bigInteger('status_id')->unsigned()->nullable()->index();
            $table->integer('display_duration')->default(0);
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('status_id')->references('id')->on('transaction_statuses');

        });

        Schema::create('advertisement_materials', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('advertisement_id')->unsigned()->nullable()->index();
            $table->mediumText('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_size')->nullable();
            $table->string('dimension')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('advertisement_id')->references('id')->on('advertisements');
        });

        Schema::create('advertisement_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('advertisement_id')->unsigned()->nullable()->index();
            $table->bigInteger('material_id')->unsigned()->nullable()->index();
            $table->bigInteger('pi_product_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_screen_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_id')->unsigned()->nullable()->index();
            $table->string('ad_type')->nullable();
            $table->timestamps();

            $table->foreign('advertisement_id')->references('id')->on('advertisements');
            $table->foreign('material_id')->references('id')->on('advertisement_materials');
            $table->foreign('pi_product_id')->references('id')->on('pi_products');
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
        Schema::dropIfExists('advertisement_screens');
        Schema::dropIfExists('advertisement_materials');
        Schema::dropIfExists('advertisements');
        
    }
}
