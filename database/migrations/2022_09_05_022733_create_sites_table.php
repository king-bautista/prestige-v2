<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->string('name');
            $table->mediumText('descriptions')->nullable();      
            $table->mediumText('site_logo')->nullable();      
            $table->mediumText('site_banner')->nullable();      
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes()->index();
        });

        Schema::create('sites_meta', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();
            $table->string('meta_key')->nullable()->index();
            $table->longText('meta_value')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::create('site_buildings', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();
            $table->string('name');
            $table->mediumText('descriptions')->nullable();      
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::create('site_building_levels', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('site_building_id')->references('id')->on('site_buildings');
        });

        Schema::create('site_maps', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->bigInteger('site_building_level_id')->unsigned();
            $table->string('map_file');
            $table->mediumText('map_preview')->nullable();
            $table->mediumText('descriptions')->nullable();      
            $table->decimal('position_x', 10, 2);
            $table->decimal('position_y', 10, 2);
            $table->decimal('position_z', 10, 2);
            $table->decimal('text_y_position', 10, 2);
            $table->decimal('default_zoom', 10, 2);
            $table->decimal('default_zoom_desktop', 10, 2);
            $table->decimal('default_zoom_mobile', 10, 2);
            $table->boolean('active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('site_building_id')->references('id')->on('site_buildings');
            $table->foreign('site_building_level_id')->references('id')->on('site_building_levels');
        });

        Schema::create('site_tenants', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('brand_id')->unsigned();
            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->bigInteger('site_building_level_id')->unsigned();
            $table->integer('view_count')->default(0);    
            $table->integer('like_count')->default(0);    
            $table->boolean('active')->default(true);
            $table->boolean('is_subscriber')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('site_building_id')->references('id')->on('site_buildings');
            $table->foreign('site_building_level_id')->references('id')->on('site_building_levels');
        });

        Schema::create('site_points', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_map_id')->unsigned();
            $table->bigInteger('tenant_id')->unsigned();
            $table->decimal('point_x', 10, 2);
            $table->decimal('point_y', 10, 2);
            $table->decimal('point_z', 10, 2);
            $table->decimal('rotation_z', 10, 2);
            $table->decimal('text_size', 10, 2);
            $table->decimal('is_pwd', 10, 2);
            $table->decimal('point_type', 10, 2);
            $table->string('point_label')->nullable();
            $table->boolean('wrap_at')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_map_id')->references('id')->on('site_maps');
            $table->foreign('tenant_id')->references('id')->on('site_tenants');
        });

        Schema::create('site_point_links', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_map_id')->unsigned();
            $table->decimal('point_a', 10, 2);
            $table->decimal('point_b', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_map_id')->references('id')->on('site_maps');
        });

        Schema::create('site_screens', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->bigInteger('site_building_level_id')->unsigned();
            $table->bigInteger('site_point_id')->unsigned();
            $table->enum('screen_type', ['Directory', 'LED', 'LFD', 'LED funnel']);
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('site_building_id')->references('id')->on('site_buildings');
            $table->foreign('site_building_level_id')->references('id')->on('site_building_levels');
        });

        Schema::create('site_screen_uptime', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_screen_id')->unsigned();
            $table->timestamps();

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
        Schema::dropIfExists('site_screen_uptime');
        Schema::dropIfExists('site_screens');
        Schema::dropIfExists('site_point_links');
        Schema::dropIfExists('site_points');
        Schema::dropIfExists('site_tenants');
        Schema::dropIfExists('site_maps');
        Schema::dropIfExists('site_building_levels');
        Schema::dropIfExists('site_buildings');        
        Schema::dropIfExists('sites_meta');
        Schema::dropIfExists('sites');
    }
}
