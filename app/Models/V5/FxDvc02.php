<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxDvc02 extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'Fxdvc02';
    protected $primaryKey = 'EMP_DVC02, ANUM_DVC02, NUM_DVC02';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_dvc02' => \Config::get("app.emp"),


        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_dvc02', \Config::get("app.emp"))

			;
        });
	}



	public function scopeWhereUpdateApi($query, $item){
		return $query->where('anum_dvc02', $item["anum_dvc02"])
				->where('num_dvc02', $item["num_dvc02"]);

	}

}
