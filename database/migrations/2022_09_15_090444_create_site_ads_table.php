<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_ads', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned()->nullable()->index();
            $table->string('name');
            $table->enum('ad_type', ['Events', 'Online', 'Banners', 'Fullscreen', 'Pop-Up', 'Promos']);
            $table->mediumText('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('display_order');
            $table->integer('display_duration');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('site_ad_sites', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('site_ad_id')->unsigned()->nullable();
            $table->bigInteger('site_id')->unsigned()->nullable();

            $table->foreign('site_ad_id')->references('id')->on('site_ads');
            $table->foreign('site_id')->references('id')->on('sites');
        });  

        Schema::create('site_ad_tenants', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('site_ad_id')->unsigned()->nullable();
            $table->bigInteger('site_tenant_id')->unsigned()->nullable();

            $table->foreign('site_ad_id')->references('id')->on('site_ads');
            $table->foreign('site_tenant_id')->references('id')->on('site_tenants');
        });   
        
        Schema::create('site_ad_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('site_ad_id')->unsigned()->nullable();
            $table->bigInteger('site_screen_id')->unsigned()->nullable();

            $table->foreign('site_ad_id')->references('id')->on('site_ads');
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
        Schema::dropIfExists('site_ad_screens');
        Schema::dropIfExists('site_ad_tenants');
        Schema::dropIfExists('site_ad_sites');
        Schema::dropIfExists('site_ads');
    }
}
