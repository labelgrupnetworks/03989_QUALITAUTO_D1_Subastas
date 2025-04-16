<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * @property string $emp_contav
 * @property string $cla_contav
 * @property string $ser_contav
 * @property string $per_contav
 * @property string $dfec_contav
 * @property string $hfec_contav
 * @property string $alc_contav
 * @property string $fcc_contav
 * @property string $tv_contav
 * @property string $logo_contav logo serie contador
 * @property string $desc_contav descripción
 */
class FsContav extends Model
{
	protected $table = 'fscontav';

	protected $primaryKey = null;

	protected $dateFormat = 'U';

	protected $attributes = false;

	public $timestamps = false;

	public $incrementing = false;

	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_contav' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_contav', Config::get("app.emp"));
		});
	}
	# No creo función  scopeWhereUpdateApi por que no se debería updatear desde la API

	#comprueba que esté activo en la fecha indicada
	public function scopeWhereActiveDate($query, $date)
	{
		return $query->where('dfec_contav', '<',   $date)
			->where('hfec_contav', '>',   $date);
	}

	/**
	 * @return string|null
	 */
	public static function getInvoceTypeBySerie(string $serie) :?string
	{
		$firstLetter = Str::substr($serie, 0, 1);
		$serieNumber = Str::substr($serie, 1);

		return self::where([
			'ser_contav' => $firstLetter,
			'per_contav' => $serieNumber
		])->value('tv_contav');
	}

}
