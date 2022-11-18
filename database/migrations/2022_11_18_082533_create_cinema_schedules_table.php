<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cinema_genre', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->string('genre_code')->index();
            $table->string('genre_label');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('cinema_schedules', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('synopsis')->nullable();
            $table->date('opening_date')->nullable();
            $table->string('rating')->nullable();
            $table->string('rating_description')->nullable();
            $table->integer('genre')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('casting')->nullable();
            $table->string('trailer_url')->nullable();
            $table->integer('cinema_id')->nullable();
            $table->bigInteger('screen_code')->nullable();
            $table->string('screen_name')->nullable();
            $table->string('film_id')->nullable();
            $table->string('genre2')->nullable();
            $table->string('genre3')->nullable();
            $table->string('cinema_id_code')->nullable();
            $table->dateTime('show_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cinema_schedules');
        Schema::dropIfExists('cinema_genre');
    }
}
