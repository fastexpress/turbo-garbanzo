<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bags', function (Blueprint $table) {
            $table->string('bag');
            $table->string('capacity');

            $table->bigInteger('idShipment')->unsigned();
            $table->foreign('idShipment')->references('id')->on('shipment_u_s_a_s');

            $table->bigInteger('userTraveler')->unsigned();
            $table->foreign('userTraveler')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
