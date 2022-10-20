<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_labels', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('site_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('category_labels');
    }
}
