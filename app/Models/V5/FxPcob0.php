<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FxPcob0 extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'fxpcob0';
    protected $primaryKey = 'EMP_PCOB0, ANUM_PCOB0, NUM_PCOB0';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo
    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;
    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public function __construct(array $vars = [])
	{
        $this->attributes=[
			'emp_pcob0' => Config::get("app.emp")

        ];
        parent::__construct($vars);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_pcob0', Config::get("app.emp"));
        });
	}

	public function scopeJoinCli($query)
	{
		return $query->join('FXCLI', "FXCLI.COD_CLI = FXPCOB0.COD_PCOB0" )->
						where("GEMP_CLI", Config::get("app.gemp"));
	}

	public function scopeJoinPcob1($query)
	{
		return $query->join('FXPCOB1', "FXPCOB1.EMP_PCOB1 = FXPCOB0.EMP_PCOB0  AND  FXPCOB1.ANUM_PCOB1 = FXPCOB0.ANUM_PCOB0  AND  FXPCOB1.NUM_PCOB1 = FXPCOB0.NUM_PCOB0 " );
	}

	#ES NECEARIO JOIN CON PCOB1
	public function scopeJoinCobro1($query)
	{
		return $query->join('FXCOBRO1', " FXCOBRO1.EMP_COBRO1 = FXPCOB1.EMP_PCOB1   AND  FXCOBRO1.AFRA_COBRO1 = FXPCOB1.SERIE_PCOB1  AND  FXCOBRO1.NFRA_COBRO1 = FXPCOB1.NUMERO_PCOB1 " );
	}

}
