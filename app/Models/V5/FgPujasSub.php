<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;


class FgPujasSub extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FgPujasSub';
	protected $primaryKey = 'EMP_PUJASSUB, IMP_PUJASSUB';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_pujassub' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_pujassub', Config::get("app.emp"));
		});
	}
}
