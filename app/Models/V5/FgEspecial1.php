<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Support\Localization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class FgEspecial1 extends Model
{
	protected $table = 'fgespecial1';
	protected $primaryKey = 'per_especial1';

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];
	protected $appends = ['description'];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_especial1' => Config::get("app.main_emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_especial1', Config::get("app.main_emp"));
		});
	}

	public function description(): Attribute
	{
		//has relation and relation is not null
		if($this->relationLoaded('specialistLang') && $this->specialistLang) {
			return Attribute::make(
				get: fn() => $this->specialistLang->desc_especial1_lang
			);
		}

		return Attribute::make(
			get: fn() => $this->desc_especial1
		);
	}

	public function image(): Attribute
	{
		$imageIsSharedWihtErp = false;
		$theme = Config::get('app.theme');

		return Attribute::make(
			get: fn() => $imageIsSharedWihtErp ? "/img/PER/{$this->per_especial1}" : "/themes/{$theme}/assets/img/specialists/{$this->per_especial1}"
		);
	}

	public static function getSpecialistsWithJoins()
	{
		$lang = Localization::getLocaleComplete();

		return self::query()
			->select([
				'emp_especial1',
				'lin_especial1',
				'orden_especial1',
				'nom_especial1',
				'email_especial1',
				'nvl(esp1_lang.desc_especial1_lang, fgespecial1.desc_especial1) desc_especial1',
				'nvl(esp1_lang.per_especial1_lang, fgespecial1.per_especial1) per_especial1',
				'nvl(espi0_lang.titulo_especial0_lang,  esp0.titulo_especial0) titulo_especial0',
				'esp0.orden_especial0'
			])
			->join('fgespecial0 as esp0', 'esp0.lin_especial0 = fgespecial1.lin_especial1 and esp0.emp_especial0 = fgespecial1.emp_especial1')
			->leftJoin('fgespecial0_lang as espi0_lang', function ($join) use ($lang) {
				$join->on('espi0_lang.lin_especial0_lang', 'esp0.lin_especial0')
					->on('espi0_lang.emp_especial0_lang', 'esp0.emp_especial0')
					->where('espi0_lang.lang_especial0_lang', $lang);
			})
			->leftJoin('fgespecial1_lang as esp1_lang', function ($join) use ($lang) {
				$join->on('esp1_lang.lin_especial1_lang', 'fgespecial1.lin_especial1')
					->on('esp1_lang.emp_especial1_lang', 'fgespecial1.emp_especial1')
					->on('esp1_lang.per_especial1_lang', 'fgespecial1.per_especial1')
					->where('esp1_lang.lang_especial1_lang', $lang);
			})
			->orderBy('esp0.orden_especial0')
			->orderBy('fgespecial1.orden_especial1')
			->get();
	}

	public static function getSpecialist($per_especial1)
	{
		return self::query()
			->select('emp_especial1', 'lin_especial1', 'per_especial1', 'orden_especial1', 'nom_especial1', 'desc_especial1', 'email_especial1')
			->withSpecialty()
			->withOrtsec()
			->where('per_especial1', $per_especial1)
			->orderBy('lin_especial1')
			->first();
	}

	public static function getSpecialists()
	{
		return self::query()
			->select('emp_especial1', 'min(lin_especial1) as lin_especial1', 'per_especial1', 'min(orden_especial1) as orden_especial1', 'nom_especial1', 'desc_especial1', 'email_especial1')
			->withSpecialty()
			->withOrtsec()
			->groupBy('emp_especial1', 'per_especial1', 'nom_especial1', 'desc_especial1', 'email_especial1')
			->orderBy('orden_especial1', 'asc')
			->get();
	}

	public static function getSpecialistsByOrtsec($lin_ortsec0)
	{
		return self::withSpecialty()
			->whereOrtsec($lin_ortsec0)
			->orderBy('orden_especial1', 'asc')
			->get();
	}

	public function specialty()
	{
		return $this->belongsTo(FgEspecial0::class, 'lin_especial1', 'lin_especial0');
	}

	public function specialistLang()
	{
		return $this->hasOne(FgEspecial1_Lang::class, 'per_especial1_lang', 'per_especial1');
	}

	public function ortsec()
	{
		if ($this->isLocale()) {
			return $this->belongsTo(FgOrtsec0::class, 'lin_especial1', 'lin_ortsec0')
				->where('sub_ortsec0', FgOrtsec0::SUB_ORTSEC0_DEPARTAMENTOS);
		}

		return $this->belongsTo(FgOrtsec0_Lang::class, 'lin_especial1', 'lin_ortsec0_lang')
			->where('sub_ortsec0_lang', FgOrtsec0_Lang::SUB_ORTSEC0_DEPARTAMENTOS)
			->where('lang_ortsec0_lang', Config::get('app.language_complete')[Config::get('app.locale')]);
	}

	public function scopeWithSpecialty($query)
	{
		$longLocale = Config::get('app.language_complete')[Config::get('app.locale')];
		$relationLanguage = $longLocale == 'es-ES' ? 'specialty' : 'specialty.specialtyLang';


		return $query->with($relationLanguage)
			->when($longLocale != 'es-ES', function ($query) {
				$query->with('specialistLang');
			});
	}

	public function scopeWithOrtsec($query)
	{
		return $query->with('ortsec');
	}

	public function scopeWhereOrtsec($query, $lin_ortsec0)
	{
		return $query->where('lin_especial1', $lin_ortsec0);
	}

	private function isLocale()
	{
		$longLocale = Config::get('app.language_complete')[Config::get('app.locale')];
		return $longLocale == 'es-ES';
	}
}
