<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description', 190)->nullable();
            $table->string('name', 50);
            $table->string('bank', 50)->nullable();
            $table->decimal('amount', 10, 2);
            // 0 DELETE
            $table->boolean('status')->default(1);
            // 1 DOLAR
            // 0 QUETZAL
            $table->string('sign')->default('Q');
            $table->string('isCashRegister')->default(0);
            // inCharge
            $table->bigInteger('inCharge')->unsigned()->nullable();
            $table->foreign('inCharge')->references('id')->on('users');

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
        Schema::dropIfExists('accounts');
    }
}
