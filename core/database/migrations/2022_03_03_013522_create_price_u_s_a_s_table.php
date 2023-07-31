<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceUSASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_u_s_a_s', function (Blueprint $table) {
            $table->id();
            $table->decimal('basePrice', 10, 2);
            $table->decimal('multiplicater', 10, 2);
            $table->string('isDelivery');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('price_u_s_a_s');
    }
}
