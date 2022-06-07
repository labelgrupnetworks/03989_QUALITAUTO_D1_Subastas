<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxPcob extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'FxPcob';
    protected $primaryKey = 'EMP_PCOB, CLA_PCOB, ANUM_PCOB, NUM_PCOB, EFEC_PCOB, COD_PCOB';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_pcob' => \Config::get("app.emp")

        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_pcob', \Config::get("app.emp"));
        });
	}



	public function scopeWhereUpdateApi($query, $item){
		return $query->where('anum_pcob', $item["anum_pcob"])
				->where('num_pcob', $item["num_pcob"]);

	}

	public function scopeJoinCli($query){
		return $query->join('FXCLI', "FXCLI.COD_CLI = FXPCOB.COD_PCOB" )->
						where("GEMP_CLI", \Config::get("app.gemp"));
	}

	public function scopeJoinPcob1($query){
		return $query->join('FXPCOB1', "FXPCOB1.EMP_PCOB1 = FXPCOB.EMP_PCOB AND FXPCOB1.SERIE_PCOB1 = FXPCOB.ANUM_PCOB AND FXPCOB1.NUMERO_PCOB1 = FXPCOB.NUM_PCOB" );


	}
	#ES NECEARIO JOIN CON PCOB1
	public function scopeJoinPcob0($query){
		return $query->join('FXPCOB0', "FXPCOB0.EMP_PCOB0 = FXPCOB1.EMP_PCOB1 AND FXPCOB0.ANUM_PCOB0 = FXPCOB1.ANUM_PCOB1 AND FXPCOB0.NUM_PCOB0 = FXPCOB1.NUM_PCOB1" );


	}

}
