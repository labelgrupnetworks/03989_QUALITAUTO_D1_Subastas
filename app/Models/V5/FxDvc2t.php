<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxDvc2t extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'FxDvc2t';
    protected $primaryKey = 'EMP_DVC2T, ANUM_DVC2T, NUM_DVC2T';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_dvc2t' => \Config::get("app.emp"),
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_dvc2t', \Config::get("app.emp"));
        });
	}

	public function scopeWhereUpdateApi($query, $item){
		return $query->where('anum_dvc2t', $item["anum_dvc2t"])
				->where('num_dvc2t', $item["num_dvc2t"]);
	}

}
