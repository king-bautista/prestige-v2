<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_brands', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_brands');
    }
}
