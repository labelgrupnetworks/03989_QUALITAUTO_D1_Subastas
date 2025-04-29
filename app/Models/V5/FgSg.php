<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Support\Localization;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cod_sg código del tipo de vía
 * @property string $des_sg descripción del tipo de vía
 */
class FgSg extends Model
{
	protected $table = 'fgsg';
	protected $primaryKey = 'cod_sg';
	protected $attributes = false;
	public $timestamps = false;
	public $incrementing = false;
	protected $guarded = [];

	public static function getList()
	{
		return self::pluck('des_sg', 'cod_sg');
	}

	public function scopeJoinLangSg($query)
	{
		$lang = Localization::getLocaleComplete();

		//$query->select("cod_sg", "nvl(FGSG_LANG.DES_SG_LANG,FGSG.des_SG) des_SG");
		$query->leftJoin('FGSG_LANG', function ($join) use ($lang) {
			$join->on("FGSG_LANG.COD_SG_LANG", "=", "FGSG.cod_sg")
				->on("FGSG_LANG.LANG_SG_LANG", "=", "'$lang'");
		});
		return  $query;
	}

	/**
	 * Query para obtener los diferentes tipos de vías (avenida, calle, etc.).
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function getStreetTypesQuery()
	{
		return self::query()
			->when(!Localization::isDefaultLocale(), function ($query) {
				return $query->joinLangSg()
					->select("cod_sg", "nvl(FGSG_LANG.DES_SG_LANG,FGSG.des_sg) des_sg");
			}, function ($query) {
				return $query->select("cod_sg", "des_sg");
			});
	}
}
