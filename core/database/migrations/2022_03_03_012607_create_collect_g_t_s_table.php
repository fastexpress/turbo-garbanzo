<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectGTSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collect_g_t_s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('telephone');
            $table->string('alternativeTelephone')->nullable();
            $table->date('date');
            // $table->string('state');
            // $table->string('city');
            // 2-PENDIENTE DE RECOLECTAR
            // 1-RECOLECTADO
            // 0-ANULADO

            $table->tinyInteger('status')->default(2);

            // $table->bigInteger('idShipmentUSA')->unsigned();
            // $table->foreign('idShipmentUSA')->references('id')->on('shipment_u_s_a_s');

            $table->bigInteger('idTown')->unsigned();
            $table->foreign('idTown')->references('id')->on('towns');

            $table->bigInteger('userCollect')->unsigned()->nullable();
            $table->foreign('userCollect')->references('id')->on('users');

            $table->bigInteger('userCreated')->unsigned();
            $table->foreign('userCreated')->references('id')->on('users');

            $table->bigInteger('userUpdated')->unsigned();
            $table->foreign('userUpdated')->references('id')->on('users');


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
        Schema::dropIfExists('collect_g_t_s');
    }
}
