<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_feedback', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned()->index();
            $table->bigInteger('site_screen_id')->unsigned()->index();
            $table->string('helpful')->nullable();
            $table->string('reason')->nullable();
            $table->string('reason_other')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
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
        Schema::dropIfExists('site_feedback');
    }
}
