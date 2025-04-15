<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $emp_param1
 * @property string $cob3web_param1
 * @property string $cob3web2_param1
 * @property string $cob1_param1
 * @property string $cob2_param1
 */
class FxParam1 extends Model
{
	protected $table = 'fxparam1';
	protected $primaryKey = false;
	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];

	#definimos la variable gemp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_param1' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_param1', Config::get("app.emp"));
		});
	}
}
