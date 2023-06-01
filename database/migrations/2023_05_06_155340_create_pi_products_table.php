<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_products', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');            
            $table->string('serial_number')->nullable();
            $table->string('product_application')->nullable();
            $table->string('ad_type')->nullable();
            $table->mediumText('descriptions')->nullable();  
            $table->mediumText('remarks')->nullable();  
            $table->integer('sec_slot')->default(0);
            $table->integer('slots')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('is_exclusive')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pi_products');
    }
}
