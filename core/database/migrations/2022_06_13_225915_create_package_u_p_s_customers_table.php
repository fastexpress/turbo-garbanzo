<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageUPSCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_u_p_s_customers', function (Blueprint $table) {
            $table->id();
            $table->string('idPackageUPS');
            $table->foreign('idPackageUPS')->references('id')->on('package_u_p_s');

            $table->bigInteger('idCustomer')->unsigned();
            $table->foreign('idCustomer')->references('id')->on('customers');

            $table->bigInteger('idAccountPersonal')->unsigned()->nullable();
            $table->foreign('idAccountPersonal')->references('id')->on('accounts');

            $table->decimal('weight', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->decimal('cost', 10, 2);
            $table->decimal('multiplicater', 10, 2);
            $table->boolean('updatedMultiplier');
            $table->boolean('typePaymentTypical');

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
        Schema::dropIfExists('package_u_p_s_customers');
    }
}
