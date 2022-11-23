<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FxClid extends Model
{
	protected $table = 'FxClid';
	protected $primaryKey = 'GEMP_CLID, CLI_CLID, CODD_CLID';
	protected $dateFormat = 'U';
	protected $attributes = false; // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'gemp_clid' => Config::get("app.gemp"),
			'codd_clid' => 'W1' #valor por defecto de la direccion, significa que es la principal de web
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('gemp', function (Builder $builder) {
			$builder->where('gemp_clid', Config::get("app.gemp"));
		});
	}

	public function scopeWhereUpdateApi($query, $item)
	{
		return $query->where('cli2_clid', $item["cli2_clid"])->where("codd_cliD", 'W1');
	}

	/**
	 *#ordenamos descendientemente codd_clid para que salga primero W1, que es la predeterminada
	 */
	public function getForSelectHTML($cod_user)
	{
		return $this->select('codd_clid', 'nomd_clid', 'dir_clid', 'cp_clid', 'pob_clid')
		->where('cli_clid', $cod_user)->orderBy('codd_clid', 'desc')->get()->pluck('full_direction', 'codd_clid')->toArray();
	}

	public function getFullDirectionAttribute()
	{
		return "{$this->nomd_clid} - {$this->dir_clid} - {$this->cp_clid} - {$this->pob_clid}";
	}
}
