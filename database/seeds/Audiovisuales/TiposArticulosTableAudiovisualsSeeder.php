<?php

use Illuminate\Database\Seeder;

/*
 * Modelos
 */
use App\Container\Audiovisuals\Src\TipoArticulo;

class TiposArticulosTableAudiovisualsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            [ 'TPART_Nombre' => 'Computador', 'TPART_Tiempo' => 1,'TPART_HorasMantenimiento'=> 200],
            [ 'TPART_Nombre' => 'Control', 'TPART_Tiempo' => 2, 'TPART_HorasMantenimiento'=> 500 ],
            [ 'TPART_Nombre' => 'Cable', 'TPART_Tiempo' => 1, 'TPART_HorasMantenimiento'=> 300 ],
            [ 'TPART_Nombre' => 'VideoBeam', 'TPART_Tiempo' => 2, 'TPART_HorasMantenimiento'=> 150],
            [ 'TPART_Nombre' => 'Cabina' , 'TPART_Tiempo' => 1, 'TPART_HorasMantenimiento'=> 605],

        ];

        foreach ($tipos as $tipo ) {
            $aux = new TipoArticulo();
            $aux->TPART_Nombre = $tipo['TPART_Nombre'];
			$aux->TPART_Tiempo = $tipo['TPART_Tiempo'];
            $aux->TPART_HorasMantenimiento = $tipo['TPART_HorasMantenimiento'];
            $aux->save();
        }
    }
}
