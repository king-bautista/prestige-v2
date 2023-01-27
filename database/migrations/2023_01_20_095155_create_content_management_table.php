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
            $table->bigInteger('advertisement_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_tenant_id')->unsigned()->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('uom')->default(0);
            $table->bigInteger('status_id')->unsigned()->nullable()->index();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('advertisement_id')->references('id')->on('advertisements');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('site_tenant_id')->references('id')->on('site_tenants');
            $table->foreign('status_id')->references('id')->on('transaction_statuses');
        });

        Schema::create('content_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('content_id')->unsigned()->nullable()->index();
            $table->bigInteger('site_screen_id')->unsigned()->nullable()->index();

            $table->foreign('content_id')->references('id')->on('content_management');
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
        Schema::dropIfExists('content_screens');
        Schema::dropIfExists('content_management');
    }
}
