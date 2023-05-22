<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_lists', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->bigInteger('content_id')->unsigned();
            $table->bigInteger('site_screen_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('parent_category_id')->unsigned();
            $table->bigInteger('main_category_id')->unsigned();
            $table->bigInteger('site_tenant_id')->unsigned();
            $table->bigInteger('advertisement_id')->unsigned();
            $table->bigInteger('sequence')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('play_lists');
    }
}
