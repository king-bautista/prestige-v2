<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->engine = "InnoDB";

        $table->bigIncrements('id');
        $table->dateTime('last_login')->nullable();
        $table->datetime('last_password_reset')->nullable();
        $table->string('module_accessed');
        $table->bigInteger('company_id')->unsigned();
        $table->enum('type', ['Admin', 'Portal'])->nullable();
        $table->bigInteger('user_id')->unsigned();
        $table->string('user_name');
        $table->integer('transaction_id')->default(0);
        $table->text('query');
        $table->text('bindings');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('company_id')->references('id')->on('companies');
       
        });

        // Schema::create('user_activity_logs_metas', function (Blueprint $table) {
        //     $table->engine = "InnoDB";
            
        //     $table->bigIncrements('id');
        //     $table->bigInteger('user_activity_log_id')->unsigned();
        //     $table->string('meta_key')->nullable()->index();
        //     $table->longText('meta_value')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
    
        //     $table->foreign('user_activity_log_id')->references('id')->on('user_activity_logs');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activity_logs');
       // Schema::dropIfExists('user_activity_logs_meta');
    }
}
