<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->bigInteger('idCustomer')->unsigned()->nullable();
            $table->foreign('idCustomer')->references('id')->on('customers');
            $table->decimal('amountBank', 10, 2)->default(0);
            $table->string('numberAccount')->nullable();
            $table->string('typeAccount')->nullable();
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
