<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesPriceUSASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_price_u_s_a_s', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('idPriceUSA')->unsigned();
            $table->foreign('idPriceUSA')->references('id')->on('price_u_s_a_s');

            $table->bigInteger('idCategory')->unsigned();
            $table->foreign('idCategory')->references('id')->on('categories');

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
        Schema::dropIfExists('categories_price_u_s_a_s');
    }
}
