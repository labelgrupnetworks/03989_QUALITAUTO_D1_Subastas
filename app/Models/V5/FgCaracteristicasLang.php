<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Support\Localization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgCaracteristicasLang extends Model
{
	protected $table = 'fgcaracteristicas_lang';
	protected $primaryKey = null;

	public $timestamps = false;
	public $incrementing = false;
	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_caracteristicas_lang' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_caracteristicas_lang', Config::get("app.emp"));
		});
	}
}
