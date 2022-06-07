<?php

# Ubicacion del modelo
namespace App\Models\V5;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FxCliOrigen extends Pivot
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FxCliOrigen';
	protected $primaryKey = 'id_cliorigen';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_cliorigen' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('gemp_cliorigen', \Config::get("app.gemp"));
        });
	}

}
