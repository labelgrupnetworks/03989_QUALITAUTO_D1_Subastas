<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgLicitRepresentados extends Model
{
	protected $table = 'fglicit_representados';
	protected $primaryKey = false;

	public $timestamps = false;
	public $incrementing = false;
	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_licitrepresentados' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_licitrepresentados', Config::get("app.emp"));
		});
	}


}
