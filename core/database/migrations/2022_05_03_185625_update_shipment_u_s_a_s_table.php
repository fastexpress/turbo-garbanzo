<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShipmentUSASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_u_s_a_s', function (Blueprint $table) {
            $table->dropColumn('shipment');
            $table->string('city');
            $table->date('date');

            $table->bigInteger('inCharge')->unsigned();
            $table->foreign('inCharge')->references('id')->on('users');
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
