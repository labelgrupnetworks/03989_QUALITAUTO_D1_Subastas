<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;


/**
 * Modelo para la tabla FsParams
 *
 * @property string $emp_params
 * @property string $cla_params
 * @property string $div_params
 * @method static myEmpParams($emp)
 * @method static active()
 */
class FsParams extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FsParams';
	protected $primaryKey = null;
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_params' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_params', Config::get("app.emp"));
		});
	}

	public function scopeActive($query)
	{
		return $query->where('cla_params', 1);
	}


	# WHERE
	public function scopeMyEmpParams($query, $emp)
	{
		return  $query->where("emp_params", $emp);
	}

}
