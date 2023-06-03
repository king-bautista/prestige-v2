<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->string('serial_number')->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('is_indefinite')->default(false);
            $table->boolean('is_exclusive')->default(false);
            $table->integer('display_duration')->default(0);
            $table->integer('slots_per_loop')->default(0);
            $table->integer('exposure_per_day')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies');

        });

        Schema::create('contract_brands', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('contract_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();

            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->foreign('brand_id')->references('id')->on('brands');
        });

        Schema::create('contract_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('contract_id')->unsigned();
            $table->bigInteger('site_screen_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_id')->unsigned()->nullable()->index();
            $table->enum('product_application', ['Directory','Digital Signage', 'All']);

            $table->foreign('contract_id')->references('id')->on('contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_screens');
        Schema::dropIfExists('contract_brands');
        Schema::dropIfExists('contracts');
    }
}
