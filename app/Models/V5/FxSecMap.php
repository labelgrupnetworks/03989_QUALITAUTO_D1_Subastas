<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FxSecMap extends Model
{
	protected $table = 'FXSECMAP';
	protected $primaryKey = 'gemp_secmap, codmap_secmap';
	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];
	public $lang;
	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'gemp_secmap' => \Config::get("app.gemp")
		];

		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('gemp', function (Builder $builder) {
			$builder->where('gemp_secmap', \Config::get("app.gemp"));
		});
	}

	public static function getFxSecMapData()
	{
		$data = self::select('CODEXTERNO_SECMAP', 'CODSEC_SECMAP')->get();

		$result = [];
		foreach ($data as $item) {
			$result[mb_strtoupper($item->codexterno_secmap)] = $item->codsec_secmap;
		}

		return $result;
	}

	public function scopeJoinFxSecFxSecMap($query)
	{
		return $query->join("FXSEC", "FXSEC.GEMP_SEC = FXSECMAP.GEMP_SECMAP AND FXSEC.COD_SEC = FXSECMAP.CODSEC_SECMAP");
	}
}
