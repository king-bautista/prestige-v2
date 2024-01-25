<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteMapConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_map_configs', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_map_id')->unsigned();
            $table->bigInteger('site_building_id')->unsigned();
            $table->bigInteger('site_building_level_id')->unsigned();
            $table->bigInteger('site_screen_id')->unsigned();
            $table->string('map_type')->nullable();
            $table->decimal('start_scale', 10, 2);
            $table->decimal('start_x', 10, 2);
            $table->decimal('start_y', 10, 2);
            $table->decimal('default_zoom', 10, 2);
            $table->decimal('default_x', 10, 2);
            $table->decimal('default_y', 10, 2);
            $table->integer('name_angle')->default(0);
            $table->integer('view_angle')->default(0);
            $table->integer('building_label_height')->default(0);
            $table->integer('building_label_space')->default(0);
            $table->integer('building_animation_height')->default(0);
            $table->integer('floor_label_height')->default(0);
            $table->integer('floor_label_space')->default(0);
            $table->integer('floor_animation_height')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('is_default')->default(false);
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
        Schema::dropIfExists('site_map_configs');
    }
}
