<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $emp_prmsub Empresa
 * @property string $numlicweb_prmsub Numero inicial de licitador por web
 * @property int $intop_prmsub Intervalo llamada operadores
 */
//EMP_PRMSUB
class FgPrmSub extends Model
{
	protected $table = 'fgprmsub';
	protected $primaryKey = 'emp_prmsub';
	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];

	#definimos la variable gemp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_prmsub' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_prmsub', Config::get("app.emp"));
		});
	}

	/**
	 * @return int
	 */
	public static function getIntervalPhoneBiddingAgents(): ?int
	{
		return self::value('intop_prmsub');
	}


}
