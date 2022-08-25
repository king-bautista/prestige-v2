<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->string('name');
            $table->mediumText('descriptions')->nullable();      
            $table->mediumText('logo')->nullable();      
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes()->index();

            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::create('brand_supplementals', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->bigInteger('supplemental_id')->unsigned()->nullable();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('supplemental_id')->references('id')->on('supplementals');
        });

        Schema::create('brand_tags', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->bigInteger('tag_id')->unsigned()->nullable();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('tag_id')->references('id')->on('tags');
        });

        Schema::create('brand_products_promos', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->string('name');
            $table->mediumText('descriptions')->nullable();      
            $table->enum('type', ['product', 'promo']);
            $table->mediumText('thumbnail')->nullable();      
            $table->mediumText('image_url')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->integer('sequence')->default(0);        
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes()->index();

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
        Schema::dropIfExists('brand_products_promos');
        Schema::dropIfExists('brand_tags');
        Schema::dropIfExists('brand_supplementals');
        Schema::dropIfExists('brands');
    }
}
