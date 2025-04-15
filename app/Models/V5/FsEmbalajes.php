<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * @property string $emp_embalajes
 * @property string $cod_embalajes
 * @property string $desc_embalajes
 * @property string $alto_embalajes
 * @property string $ancho_embalajes
 * @property string $grueso_embalajes
 * @property string $imp_embalajes
 * @property string $id_art
 */
class FsEmbalajes extends Model
{
	protected $table = 'fsembalajes';
	protected $primaryKey = 'cod_embalajes';
	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_embalajes' => Config::get("app.emp"),
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_embalajes', Config::get("app.emp"));
		});
	}

	/**
	 * @return self|null
	 */
	public static function getEmbalajesByCod($codEmbalajes) :?self
	{
		return self::where('cod_embalajes', $codEmbalajes)->first();
	}
}
