<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * Direccion de envío de un pedido
 * @property string $emp_dvc0dir
 * @property string $anum_dvc0dir
 * @property int $num_dvc0dir
 * @property string $dir_dvc0dir
 * @property string $cp_dvc0dir
 * @property string $pob_dvc0dir
 * @property string $pro_dvc0dir
 * @property string $codpais_dvc0dir
 * @property string $pais_dvc0dir
 * @property string $nom_dvc0dir
 */
class FxDvc0Dir extends Model
{
	protected $table = 'fxdvc0dir';
	protected $primaryKey = false;
	protected $attributes = false;
	public $timestamps = false;
	public $incrementing = false;
	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_dvc0dir' => Config::get("app.emp")

		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_dvc0dir', Config::get("app.emp"));
		});
	}

	/**
	 * Devuelve la dirección de envío de un pedido
	 * @param string $number Número de pedido
	 * @param string $serie Serie del pedido
	 * @return FxDvc0Dir
	 */
	public static function getDirectionByIds(string $number, $serie): self
	{
		return self::where([
			'anum_dvc0dir' => $number,
			'num_dvc0dir' => $serie
		])->first() ?? new self();
	}
}
