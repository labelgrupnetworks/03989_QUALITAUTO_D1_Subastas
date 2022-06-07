<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FsContav extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'FsContav';
    protected $primaryKey = 'EMP_CONTAV, ANUM_CONTAV, NUM_CONTAV';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_contav' => \Config::get("app.emp")


        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_contav', \Config::get("app.emp"))

			;
        });
	}
	# No creo función  scopeWhereUpdateApi por que no se debería updatear desde la API

	#comprueba que esté activo en la fecha indicada
	public function scopeWhereActiveDate($query, $date){
		return $query->where('dfec_contav','<',   $date)
					->where('hfec_contav','>',   $date);
    }



}
