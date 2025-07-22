<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use App\Support\Localization;

class ObjectTypeValues extends Model
{
	protected $table = '"object_types_values"';
	protected $primaryKey = null;

	public $timestamps = false;
	public $incrementing = true;

	protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'"company"' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('"company"', Config::get("app.emp"));
		});
	}


	public function scopeLeftJoinLang($query)
	{
		$lang = Localization::getLocaleComplete();

		$query->leftJoin('"object_types_values_lang"', function ($join) use ($lang) {
			$join->on('"transfer_sheet_number_lang"', '=', '"transfer_sheet_number"')
				->on('"transfer_sheet_line_lang"', '=', '"transfer_sheet_line"')
				->on('"company_lang"', '=', '"company"')
				->where('"lang_object_types_values_lang"', '=', $lang);
		});
	}

	/**
	 * Lo utiliza Tauler en las fichas
	 */
	public static function getConservationCurrency($num_hces, $lin_hces)
	{
		return ObjectTypeValues::query()
			->select([
				'COALESCE("conservation_1_lang", "conservation_1") as "conservation_1"',
				'COALESCE("conservation_2_lang", "conservation_2") as "conservation_2"'
			])
			->leftJoinLang()
			->where([
				'"transfer_sheet_number"' => $num_hces,
				'"transfer_sheet_line"' => $lin_hces,
			])
			->first();
	}
}
