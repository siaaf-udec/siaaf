<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcesoCostosInformacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('calidadpcs')->create('TBL_Calidadpcs_proceso_costos_informacion', function (Blueprint $table) {
            $table->increments('PK_CPCI_Id_Costos');
            $table->string('CPCI_Abreviatura');
            $table->string('CPCI_Nombre');
            $table->string('CPCI_Definicion');
            $table->string('CPCI_Uso');
            $table->string('CPCI_Formula');
            $table->string('CPCI_Interpretacion');
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
        Schema::dropIfExists('TBL_Calidadpcs_proceso_costos_informacion');
    }
}
