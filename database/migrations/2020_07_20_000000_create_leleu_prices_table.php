<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeleuPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leleu_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('volume')->unsigned();
            $table->double('price')->unsigned();
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('id')->on('country_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leleu_prices');
    }
}
