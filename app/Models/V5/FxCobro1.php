<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxCobro1 extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'Fxcobro1';
    protected $primaryKey = 'EMP_COBRO1, ANUM_COBRO1, NUM_COBRO1, LIN_COBRO1';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_cobro1' => \Config::get("app.emp")

        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_cobro1', \Config::get("app.emp"));
        });
	}



	public function scopeWhereUpdateApi($query, $item){
		return $query->where('afra_cobro1', $item["afra_cobro1"])
				->where('nfra_cobro1', $item["nfra_cobro1"]);

	}

}
