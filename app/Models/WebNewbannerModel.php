<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class WebNewbannerModel extends Model
{
	protected $table = 'web_newbanner';
	protected $primaryKey = 'ID';
	public $timestamps = false;
	public $incrementing = false;
	//   public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	const UBICACION_EVENTO = 'evento';
	const UBICACION_MUSEO = 'piezas-museo';
	const UBICACION_HOME = 'HOME';
	const UBICACION_BLOG = 'BLOG';
	const WEB_NEWBANNER_TIPO_EVENTO = 1;

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'empresa' => Config::get("app.main_emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('empresa', function (Builder $builder) {
			$builder->where('empresa', Config::get("app.main_emp"));
		});
	}

	static function getActiveBannerWithKey($key)
	{
		return self::with('type', 'activeItems')
			->activo()
			->where('key', $key)
			->first();
	}

	public function scopeActivo($query)
	{
		return  $query->where('WEB_NEWBANNER.ACTIVO', 1);
	}

	public function scopeEmpresa($query, $empresa = 0)
	{
		if (empty($empresa)) {
			$empresa = Config::get("app.main_emp");
		}

		return  $query->where('WEB_NEWBANNER.EMPRESA', $empresa);
	}

	public function scopeJoinBannerItem($query)
	{
		return $query->join("WEB_NEWBANNER_ITEM", "WEB_NEWBANNER_ITEM.ID_WEB_NEWBANNER = WEB_NEWBANNER.ID ");
	}

	public function items()
	{
		return $this->hasMany(WebNewbannerItemModel::class, 'id_web_newbanner', 'id');
	}

	public function activeItems()
	{
		return $this->hasMany(WebNewbannerItemModel::class, 'id_web_newbanner', 'id')
			->active()
			->forLanguage()
			->orderItems();
	}

	public function type()
	{
		return $this->hasOne(WebNewbannerTipoModel::class, 'id', 'id_web_newbanner_tipo');
	}
}
