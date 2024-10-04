<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgEspecial1_Lang extends Model
{
	protected $table = 'fgespecial1_lang';
	protected $primaryKey = 'per_especial1_lang';

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_especial1_lang' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_especial1_lang', Config::get("app.emp"));
		});
	}
}
