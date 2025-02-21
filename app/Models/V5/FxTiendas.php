<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FxTiendas extends Model
{
	protected $table = 'fxtiendas';
	protected $primaryKey = 'id';
	protected $attributes = false;

	public $timestamps = false;
	public $incrementing = true;

	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp', Config::get("app.emp"));
        });
    }

	public static function getTinedaIdFromAgrsub()
	{
		return self::query()
			->firstWhere('nombre', Config::get('app.agrsub'))
			->id ?? null;
	}

}
