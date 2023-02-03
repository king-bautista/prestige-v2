<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_screen_id')->unsigned()->nullable()->index();
            $table->bigInteger('category_id')->unsigned()->nullable()->index();
            $table->bigInteger('parent_category_id')->unsigned()->nullable()->index();
            $table->bigInteger('brand_id')->unsigned()->nullable()->index();
            $table->bigInteger('company_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_tenant_id')->unsigned()->nullable()->index();
            $table->bigInteger('advertisement_id')->unsigned()->nullable()->index();
            $table->string('action')->nullable();
            $table->string('page')->nullable();    
            $table->string('key_words')->nullable();    
            $table->mediumText('results')->nullable();    
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
        Schema::dropIfExists('logs');
    }
}
