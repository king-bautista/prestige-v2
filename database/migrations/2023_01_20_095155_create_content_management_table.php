<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_management', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->string('serial_number')->nullable();
            $table->bigInteger('advertisement_id')->unsigned()->nullable()->index();
            $table->bigInteger('status_id')->unsigned()->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('advertisement_id')->references('id')->on('advertisements');
            $table->foreign('status_id')->references('id')->on('transaction_statuses');
        });

        Schema::create('content_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('content_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_screen_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_id')->unsigned()->nullable()->index();
            $table->string('product_application')->nullable();
            $table->timestamps();

            $table->foreign('content_id')->references('id')->on('content_management');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_screens');
        Schema::dropIfExists('content_management');
    }
}
