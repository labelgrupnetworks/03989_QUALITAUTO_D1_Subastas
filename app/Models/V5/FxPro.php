<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxPro extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'FXPRO';
    protected $primaryKey = 'GEMP_PRO, COD_PRO';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    //public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

	const CREATED_AT = 'f_alta_pro';
    const UPDATED_AT = 'f_modi_pro';

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva
	public function __construct(array $vars = []){
        $this->attributes=[
			'gemp_pro' => \Config::get("app.gemp")

        ];
        parent::__construct($vars);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('gemp_pro', \Config::get("app.gemp"));
        });
	}

}
