<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;



class FxCliObcta extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FXCLIOBCTA';
	protected $primaryKey = 'GEMP_CLIOBCTA, CLI_CLIOBCTA, LIN_CLIOBCTA';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	const TIPOBS_CLIOBCTA_TARGETA = 'TC';
	const USR_CLIOBCTA_WEB = 'WEB';

	public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_cliobcta' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('gemp_cliobcta', \Config::get("app.gemp"));
        });
	}



}
