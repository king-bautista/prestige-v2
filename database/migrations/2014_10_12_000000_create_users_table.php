<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('user_name')->unique();
            $table->string('password');
            $table->string('salt')->nullable();
            $table->integer('login_attempt')->default(0);
            $table->integer('is_blocked')->default(0);
            $table->boolean('status')->default(0);
            $table->string('activation_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users_meta', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('meta_key')->nullable()->index();
            $table->longText('meta_value')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_meta');
        Schema::dropIfExists('users');
    }
}
