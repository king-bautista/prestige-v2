<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->engine = "InnoDB";
            
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->unsigned()->nullable()->index();
            $table->bigInteger('classification_id')->unsigned()->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->mediumText('address')->nullable();            
            $table->string('tin')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('classification_id')->references('id')->on('classifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
