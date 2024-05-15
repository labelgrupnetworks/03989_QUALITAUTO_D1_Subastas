<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

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


	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_dvc02' => Config::get("app.emp"),
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_dvc02', Config::get("app.emp"));
		});
	}

	public function scopeWhereUpdateApi($query, $item)
	{
		return $query->where('anum_dvc02', $item["anum_dvc02"])
			->where('num_dvc02', $item["num_dvc02"]);
	}

	public function scopeWhereMultiplesSeriesAndLines($query, $seriesAndLines = [])
	{
		return $query->when($seriesAndLines, function ($query, $seriesAndLines) {
			return $query->where(function ($query) use ($seriesAndLines) {
				foreach ($seriesAndLines as $serieAndLine) {
					$query->orWhere(function ($query) use ($serieAndLine) {
						$query->where('anum_dvc02', $serieAndLine['serie'])
							->where('num_dvc02', $serieAndLine['line']);
					});
				}
				return $query;
			});
		}, function ($query) {
			return $query;
		});
	}

	public function scopeWhereUser($query, $cod_cli)
	{
		return $query->where('cod_dvc0', $cod_cli);
	}

	public function scopeJoinDvc0($query)
	{
		return $query->join('fxdvc0', 'fxdvc0.emp_dvc0 = emp_dvc02 and fxdvc0.anum_dvc0 = anum_dvc02 and fxdvc0.num_dvc0 = num_dvc02');
	}
}
