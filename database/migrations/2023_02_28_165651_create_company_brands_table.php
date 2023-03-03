<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_brands', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_brands');
    }
}
