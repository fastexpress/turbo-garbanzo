<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('send');
            $table->string('departament');
            $table->string('type');
            $table->string('content');
            $table->string('content_en');
            $table->boolean('updatedMultiplier');
            $table->decimal('multiplier', 10, 2);
            $table->string('category');
            $table->string('weight');
            $table->string('cost');
            $table->string('payment');
            $table->string('guide');
            $table->string('observation')->nullable();
            $table->string('subtotal');
            $table->string('office')->nullable();


            $table->bigInteger('idBaler')->unsigned();
            $table->foreign('idBaler')->references('id')->on('balers');

            $table->bigInteger('idBag')->unsigned()->nullable();
            $table->foreign('idBag')->references('id')->on('bags');

            $table->string('idHeaderPackage');
            $table->foreign('idHeaderPackage')->references('id')->on('header_packages');

            $table->integer('idParent')->nullable();

            // 2 GUATEMALA
            // 1 USA
            // 0 ANULADO
            $table->tinyInteger('status')->default(2);

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
        Schema::dropIfExists('packages');
    }
}
