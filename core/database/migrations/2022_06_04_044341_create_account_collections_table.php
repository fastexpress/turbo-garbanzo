<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_collections', function (Blueprint $table) {
            $table->id();

            $table->decimal('exchangeRate', 10, 2);
            $table->decimal('amountDollar', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->string('bank')->nullable();
            $table->string('keyNumber')->nullable();
            $table->string('nameSend')->nullable();
            $table->string('collect')->default('PENDIENTE');
            $table->dateTime('inCollect')->nullable();
            $table->string('type')->default('DEPÓSITO');
            $table->dateTime('inType')->nullable();

            $table->string('idPackageUPS');
            $table->foreign('idPackageUPS')->references('id')->on('package_u_p_s');

            $table->bigInteger('idAccount')->unsigned();
            $table->foreign('idAccount')->references('id')->on('accounts');

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
        Schema::dropIfExists('account_collections');
    }
}
