<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgSubConditions extends Model
{
	protected $table = 'FgSubConditions';
	protected $primaryKey = 'id_subconditions';

	public $timestamps = true;
	public $incrementing = true;

	const CREATED_AT = 'fechacreacion_subconditions';
	const UPDATED_AT = null;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = ['emp_subconditions' => Config::get("app.emp")];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_subconditions', Config::get("app.emp"));
		});
	}

	public function getFechacreacionSubconditionsAttribute($value)
	{
		return date('d/m/Y H:i', strtotime($value));
	}

	public function client()
	{
		return $this->hasOne(FxCli::class, 'cod_cli', 'cli_subconditions');
	}

	public function auction()
	{
		return $this->hasOne(FgSub::class, 'cod_sub', 'cod_subconditions');
	}

	public static function isAcceptedCondtition($cli_subconditions, $cod_subconditions)
	{
		return self::query()
			->where('cli_subconditions', $cli_subconditions)
			->where('cod_subconditions', $cod_subconditions)
			->exists();
	}
}
