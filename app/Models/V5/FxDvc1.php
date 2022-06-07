<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxDvc1 extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'Fxdvc1';
    protected $primaryKey = 'EMP_DVC1, ANUM_DVC1, NUM_DVC1';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_dvc1' => \Config::get("app.emp")

        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_dvc1', \Config::get("app.emp"));
        });
	}


	# en la API siempre pondremso lin_dvc1 a 1 siempre por lo que no hace falta ponrlo en el el where
	public function scopeWhereUpdateApi($query, $item){
		return $query->where('anum_dvc1', $item["anum_dvc1"])
				->where('num_dvc1', $item["num_dvc1"]);

	}

}
