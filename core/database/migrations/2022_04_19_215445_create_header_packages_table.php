<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('header_packages', function (Blueprint $table) {
            $table->string('id', 19)->primary();
            $table->string('receive');
            $table->string('serie');
            $table->string('telephone');
            $table->string('city');
            $table->string('alternativeTelephone')->nullable();
            //
            // 2 GUATEMALA
            // 1 USA
            // 0 REMBOLSADO
            $table->tinyInteger('status')->default(2);
            $table->string('observationStatus')->nullable();
            // 1 LLAMADO
            // 0 NO LLAMADO
            $table->tinyInteger('callStatus')->default(0);
            $table->string('observationCall')->nullable();
            // 1 MSJ ENVIADO
            // 0 MSJ NO ENVIADO
            $table->tinyInteger('whatsapp')->default(0);
            $table->string('observationWhatsapp')->nullable();

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
        Schema::dropIfExists('header_packages');
    }
}
