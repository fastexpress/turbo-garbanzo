<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentUPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_u_p_s', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantity', 10, 2);
            $table->string('content');
            $table->string('content_en');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
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
        Schema::dropIfExists('content_u_p_s');
    }
}
