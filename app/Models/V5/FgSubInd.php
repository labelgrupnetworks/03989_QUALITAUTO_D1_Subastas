<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class FgSubInd extends Model
{
	protected $table = 'FGSUBIND';

	protected $primaryKey = false;
	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	public $lang;

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_subind' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {

			$builder->where('emp_subind', Config::get("app.emp"));
		});
	}

	public function scopeAddSelectDescriptions($query)
	{
		$query->addSelect([
			'des_subind',
			'desaux_subind'
		]);
	}

	public function scopeAddSelectDescriptionsLang($query)
	{
		$query->addSelect([
			'NVL(FGSUBIND_LANG.DES_SUBIND_LANG, FGSUBIND.DES_SUBIND) as DES_SUBIND',
			'NVL(FGSUBIND_LANG.DESAUX_SUBIND_LANG, FGSUBIND.DESAUX_SUBIND) as DESAUX_SUBIND'
		]);
	}

	/**
	 * Scope para unir la tabla de idiomas
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeJoinLangSubInd($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

		$query->leftJoin('FGSUBIND_LANG', function ($join) use ($lang) {
			$join->on('FGSUBIND.EMP_SUBIND', '=', 'FGSUBIND_LANG.EMP_SUBIND_LANG')
				->on('FGSUBIND.SUB_SUBIND', '=', 'FGSUBIND_LANG.SUB_SUBIND_LANG')
				->on('FGSUBIND.SESION_SUBIND', '=', 'FGSUBIND_LANG.SESION_SUBIND_LANG')
				->on('FGSUBIND.LIN_SUBIND', '=', 'FGSUBIND_LANG.LIN_SUBIND_LANG')
				->where('FGSUBIND_LANG.LANG_SUBIND_LANG', $lang);
		});

		return  $query;
	}

	/**
	 * Scope para unir la tabla de sesiones
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeJoinAucSessions($query)
	{
		$query->join('"auc_sessions"', function ($join) {
			$join->on('"auc_sessions"."company"', '=', 'FGSUBIND.EMP_SUBIND')
				->on('"auc_sessions"."auction"', '=', 'FGSUBIND.SUB_SUBIND')
				->on('"auc_sessions"."reference"', '=', 'FGSUBIND.SESION_SUBIND');
		});

		return  $query;
	}
}
