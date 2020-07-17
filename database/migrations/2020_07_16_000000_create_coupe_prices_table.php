<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoupePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupe_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('weight')->unsigned();
            $table->double('price')->unsigned();
            $table->integer('state_id')->unsigned();
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
        Schema::dropIfExists('coupe_prices');
    }
}
