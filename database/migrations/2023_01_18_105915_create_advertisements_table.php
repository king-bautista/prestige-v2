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
            $table->enum('product_application', ['Directory','Digital Signage']);
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
            $table->enum('ad_type', ['Banner Ad','Fullscreen Ad']);
            $table->enum('orientation', ['Landscape','Portrait']);

            $table->foreign('advertisement_id')->references('id')->on('advertisements');
        });

        Schema::create('advertisement_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('advertisement_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_screen_id')->unsigned()->nullable()->index();

            $table->foreign('advertisement_id')->references('id')->on('advertisements');
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
        Schema::dropIfExists('advertisements');
    }
}
