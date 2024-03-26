<?php

# Ubicacion del modelo
namespace App\Models\V5;

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


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_especial1' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_especial1', Config::get("app.emp"));
		});
	}

	public function description(): Attribute
	{
		return Attribute::make(
			get: fn () => $this->relationLoaded('specialistLang') ? $this->specialistLang->desc_especial1_lang : $this->desc_especial1
		);
	}

	public static function getSpecialists()
	{
		return self::withSpecialty()->get();
	}

	public function specialty()
	{
		return $this->belongsTo(FgEspecial0::class, 'lin_especial1', 'lin_especial0');
	}

	public function specialistLang()
	{
		return $this->hasOne(FgEspecial1_Lang::class, 'lin_especial1_lang', 'lin_especial1');
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
}
