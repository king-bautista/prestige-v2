<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteTenantProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brand_products_promos', function (Blueprint $table) {
            $table->bigInteger('tenant_id')->after('brand_id')->nullable()->index();
        });

        Schema::create('site_tenant_products', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('brand_product_promo_id')->unsigned()->nullable();
            $table->bigInteger('site_tenant_id')->unsigned()->nullable();

            $table->foreign('brand_product_promo_id')->references('id')->on('brand_products_promos');
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
        Schema::dropIfExists('site_tenant_products');
    }
}
