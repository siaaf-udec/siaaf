<?php

namespace App\Container\Acadspace\src;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
//use App\Container\Users\src\UsersUdec;

class Mantenimiento extends Model
{
    /**
     * Conexión de la base de datos usada por el modelo
     * @var string
     */
    protected $connection = 'acadspace';

    /**
     * Tabla utilizada por el modelo.
     * @var string
     */
    protected $table = 'TBL_Registro_Mantenimientos';

    /**
     * Llave primaria usada por el modelo
     */
    protected $primaryKey = 'PK_MANT_Id_Registro';

    /**
     * Atributos que son asignables.
     * @var array
     */
    protected $fillable = [
        'MANT_Fecha_Inicio',
        'MANT_Fecha_Fin',
        'MANT_Descripcion',
        'MANT_Nombre_Tecnico',
        'MANT_Descripcion_Errores',
        'FK_MANT_Id_Hojavida',
        'FK_MANT_Id_Tipo'
            
    ];   
    /**
     *  Función que retorna la relación entre la tabla 'TBL_Hojavida' y la tabla
     * 'TBL_Registro_Mantenimientos' a través de la llave foránea 'FK_MANT_Id_Hojavida'
     *  y la llave 'PK_HOJ_Id_Hojavida'
     */
    public function hojavida()
    {
        return $this->hasMany(Hojavida::class, 'FK_MANT_Id_Hojavida', 'PK_HOJ_Id_Hojavida');
    }
    
     /**
     *  Función que retorna la relación entre la tabla 'TBL_Registro_Manteniemientos' y la tabla
     * 'TBL_Tipos_Mantenimientos' a través de la llave foránea 'FK_MANT_Id_Tipo'
     *  y la llave 'PK_MAN_Id_Tipo'
     */
    public function tiposmant()
    {
        return $this->hasMany(TiposMant::class, 'FK_MANT_Id_Tipo', 'PK_MAN_Id_Tipo');
    }
        
}
