<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteTenantMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_tenants', function (Blueprint $table) {
            $table->bigInteger('company_id')->after('site_building_level_id')->nullable()->index();
        });

        Schema::create('site_tenant_metas', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_tenant_id')->unsigned();
            $table->string('meta_key')->nullable()->index();
            $table->longText('meta_value')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_tenant_id')->references('id')->on('site_tenants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_tenant_metas');
    }
}
