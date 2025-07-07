<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * @property string emp_operadores
 * @property int cod_operadores
 * @property string nom_operadores
 */
class FsOperadores extends Model
{
	protected $table = 'fsoperadores';
	protected $primaryKey = 'cod_operadores';

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_operadores' => Config::get("app.emp"),
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_operadores', Config::get("app.emp"));
		});
	}

	/**
	 * Nombres de los operadores para un select.
	 * Devuelve una colección con los códigos como claves y los nombres como valores.
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public static function toSelect() : \Illuminate\Support\Collection
	{
		return self::query()
			->orderBy('nom_operadores')
			->pluck('nom_operadores', 'cod_operadores');
	}
}
