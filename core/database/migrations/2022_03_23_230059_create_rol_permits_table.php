<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_permits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idRol')->unsigned();
            $table->foreign('idRol')->references('id')->on('rols');
            $table->bigInteger('idPermit')->unsigned();
            $table->foreign('idPermit')->references('id')->on('permits');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('rol_permits');
    }
}
