<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('name');
            $table->mediumText('descriptions')->nullable();            
            $table->mediumText('kiosk_image_primary')->nullable();      
            $table->mediumText('kiosk_image_top')->nullable();     
            $table->mediumText('online_image_primary')->nullable();
            $table->mediumText('online_image_top')->nullable();     
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
