<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_care', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->bigInteger('concern_id')->unsigned()->nullable()->index();
            $table->string('ticket_id');
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('ticket_subject');
            $table->string('ticket_description');
            $table->bigInteger('status_id')->unsigned()->nullable()->index();
            $table->string('assigned_to_id');
            $table->string('assigned_to_alias');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('concern_id')->references('id')->on('concerns');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('transaction_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_care');
    }
}
