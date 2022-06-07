<?php

# Ubicacion del modelo

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;

class FsIdioma extends Model {

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.
    protected $table = 'FSIDIOMA';
    protected $primaryKey = 'COD_IDIOMA';
    //protected $connection = 'SUBALIA';
    public $timestamps = false;
    public $incrementing = false;
    //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];


    /**
     * Obtener array con el cod_idioma como key y la descripción de este como valor
     * @return array
     */
    public static function getArrayValues() :array{

        $array_aux = self::all();
        $array = array();
        if(!empty($array_aux)){
            foreach ($array_aux as $value) {
                $array[$value->cod_idioma] = mb_convert_case($value->des_idioma, MB_CASE_TITLE, "UTF-8");
            }
        }
        return $array;
    }

}
