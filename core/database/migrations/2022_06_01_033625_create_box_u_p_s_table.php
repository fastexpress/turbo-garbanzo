<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxUPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box_u_p_s', function (Blueprint $table) {
            $table->id();
            $table->decimal('weightKg', 10, 2);
            $table->string('idPackage');
            $table->foreign('idPackage')->references('id')->on('package_u_p_s');
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
        Schema::dropIfExists('box_u_p_s');
    }
}
