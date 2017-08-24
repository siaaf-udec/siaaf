<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnteproyectoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('gesap')->create('tbl_Anteproyecto', function (Blueprint $table) {
            $table->increments('PK_NPRY_idMinr008');
            $table->string('NPRY_Titulo',250);
            $table->String('NPRY_Keywords',300);
            $table->integer('NPRY_Duracion');
            $table->date('NPRY_FechaR');
            $table->date('NPRY_FechaL');
            $table->String('NPRY_Estado',90)->default("EN ESPERA");
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
        Schema::dropIfExists('TBL_Anteproyecto');
    }
}
