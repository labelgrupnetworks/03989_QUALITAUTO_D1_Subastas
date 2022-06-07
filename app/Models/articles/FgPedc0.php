<?php

# Ubicacion del modelo
namespace App\Models\articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FgPedc0 extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FGPEDC0';
	protected $primaryKey = 'EMP_PEDC0, ANUM_PEDC0, NUM_PEDC0';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	#definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
			'gemp_pedc0' => \Config::get("app.gemp"),
			'emp_pedc0' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_pedc0', \Config::get("app.emp"))
			->where('gemp_pedc0', \Config::get("app.gemp"));
        });
    }

	public function scopeJoinPedc1($query){
		return $query->join("FGPEDC1", "FGPEDC1.EMP_PEDC1 = FGPEDC0.EMP_PEDC0 AND FGPEDC1.NUM_PEDC1 = FGPEDC0.NUM_PEDC0");
	}

}
