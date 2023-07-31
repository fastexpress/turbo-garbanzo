<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageUPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_u_p_s', function (Blueprint $table) {
            $table->string('id', 19)->primary();
            $table->bigInteger('code')->nullable();
            $table->string('guide');
            $table->string('observation')->nullable();

            $table->string('category')->nullable();
            $table->string('departament')->nullable();
            $table->string('type')->nullable();
            $table->string('office')->nullable();

            $table->string('serie');
            $table->string('receive');
            $table->string('sender');
            $table->string('telephone');
            $table->string('codeUPS', 25)->nullable();
            $table->boolean('typical')->default(true);

            $table->boolean('typePaymentTypical')->nullable();
            $table->boolean('typePayment')->nullable();
            $table->string('telephoneAlternative')->nullable();

            $table->bigInteger('idCustomer')->unsigned()->nullable();
            $table->foreign('idCustomer')->references('id')->on('customers');

            $table->string('address');
            $table->string('postalCode');
            $table->string('state');
            $table->string('city');

            $table->bigInteger('idAccountPersonal')->unsigned()->nullable();
            $table->foreign('idAccountPersonal')->references('id')->on('accounts');

            $table->decimal('totalKg', 10, 2);
            $table->decimal('totalContent', 10, 2);
            $table->decimal('weight', 10, 2)->default(0);
            $table->decimal('totalPayment', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('multiplicater', 10, 2)->default(0);
            $table->boolean('updatedMultiplier')->default(false);
            $table->string('status')->default('OFICINA');
            // OFFICE
            $table->dateTime('inOffice');
            // ADDRESS
            $table->dateTime('inContent')->nullable();
            // ADDRESS
            $table->dateTime('inAddress')->nullable();
            // PAYMENT
            $table->dateTime('inPayment')->nullable();
            // CODE UPS
            $table->dateTime('inCode')->nullable();
            // ROUTE
            $table->dateTime('inRoute')->nullable();
            $table->string('inRouteComment')->nullable();
            // ENTREGADO
            $table->dateTime('inDelivered')->nullable();
            $table->string('inDeliveredComment')->nullable();
            $table->string('inDeliveredDate')->nullable();
            // ANULADO
            $table->dateTime('inNull')->nullable();
            $table->string('inNullComment')->nullable();
            // STOPPED
            $table->dateTime('inStopped')->nullable();
            $table->string('inStoppedComment')->nullable();

            $table->bigInteger('inCharge')->unsigned()->nullable();
            $table->foreign('inCharge')->references('id')->on('users');

            $table->bigInteger('userCreated')->unsigned();
            $table->foreign('userCreated')->references('id')->on('users');

            $table->bigInteger('userUpdated')->unsigned();
            $table->foreign('userUpdated')->references('id')->on('users');

            $table->bigInteger('idBaler')->unsigned();
            $table->foreign('idBaler')->references('id')->on('balers');

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
        Schema::dropIfExists('package_u_p_s');
    }
}
