<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FgEspecial0 extends Model
{
	protected $table = 'fgespecial0';
	protected $primaryKey = 'lin_especial0';

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_especial0' => Config::get("app.main_emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_especial0', Config::get("app.main_emp"));
		});
	}

	public function specialtyLang()
	{
		return $this->hasOne(FgEspecial0_Lang::class, 'lin_especial0_lang', 'lin_especial0');
	}

	public function title(): Attribute
	{
		return Attribute::make(
			get: fn () => $this->relationLoaded('specialtyLang') ? $this->specialtyLang->titulo_especial0_lang : $this->titulo_especial0
		);
	}
}
