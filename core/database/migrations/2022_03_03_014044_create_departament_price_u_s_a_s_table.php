<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentPriceUSASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departament_price_u_s_a_s', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('idPriceUSA')->unsigned();
            $table->foreign('idPriceUSA')->references('id')->on('price_u_s_a_s');

            $table->bigInteger('idDepartament')->unsigned();
            $table->foreign('idDepartament')->references('id')->on('departaments');

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
        Schema::dropIfExists('departament_price_u_s_a_s');
    }
}
