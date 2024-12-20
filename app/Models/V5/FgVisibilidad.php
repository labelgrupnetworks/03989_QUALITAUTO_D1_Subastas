<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Models\V5\Traits\ScopeFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\V5\FxCli;
use Illuminate\Support\Facades\Config;

class FgVisibilidad extends Model
{
	use ScopeFilter;

	protected $table = 'FgVisibilidad';
	protected $primaryKey = 'COD_VISIBILIDAD';
	protected $dateFormat = 'Y-m-d H:i:s';
	protected $attributes = false;

	//public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	const CREATED_AT = 'fecha_visibilidad';
	const UPDATED_AT = 'fecha_visibilidad';

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_visibilidad' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_visibilidad', Config::get("app.emp"));
		});
	}

	public function client()
	{
		return $this->belongsTo(FxCli::class, 'cli_visibilidad', 'cod_cli');
	}

	public function getClientNameAttribute()
	{
		return $this->client->nom_cli ?? '';
	}
}
