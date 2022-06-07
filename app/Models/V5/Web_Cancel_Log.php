<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;

class Web_Cancel_Log extends Model
{
    protected $table = 'WEB_CANCEL_LOG';
    protected $primaryKey = 'ID_WEB_CANCEL_LOG';

    public $timestamps = false;
    public $incrementing = false;
 	//public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	const ACCION_ELIMINAR = 'E';
	const ACCION_MODIFICAR = 'M';

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'id_emp' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('id_emp', Config::get("app.emp"));
        });
    }
    protected $casts = [
        'ref_orlic' => 'float',
        'himp_orlic' => 'float',

    ];

    public function scopeJoinCli($query){
        return $query->join("FGLICIT", "EMP_LICIT = ID_EMP AND SUB_LICIT = ID_SUB AND COD_LICIT = ID_LICIT")
                     ->join("FXCLI", "GEMP_CLI = '". Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT");

    }

}
