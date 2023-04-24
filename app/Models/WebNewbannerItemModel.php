<?php

# Ubicacion del modelo
namespace App\Models;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class WebNewbannerItemModel extends Model
{
	protected $table = 'web_newbanner_item';
	protected $primaryKey = 'ID';
	public $timestamps = false;
	public $incrementing = false;
	//   public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	public function banner()
	{
		return $this->belongsTo(WebNewbannerModel::class, 'id', 'id_web_newbanner');
	}

	public function scopeActive($query)
	{
		return $query->where('activo', 1);
	}

	public function scopeOrderItems($query)
	{
		return $query->orderBy('bloque')
			->orderBy('orden')
			->orderBy('id');
	}

	public function scopeForLanguage($query)
	{
		$query->where('lenguaje', strtoupper(Config::get("app.locale")));
	}

	public function getImagesAttribute()
	{
		$images = [
			'desktop' => $this->imageRoute(false),
			'mobile' => $this->imageRoute(true)
		];
		return $images;
	}

	public function getPublicUrlAttribute()
	{
		$emp = Config::get('app.main_emp');
		$theme = Config::get('app.theme');
		return "/img/banner/$theme/$emp/{$this->id_web_newbanner}/{$this->id}/";
	}

	private function imageRoute($isMobile)
	{
		#añadimos el locale a un array para poder buscar por idimo principal y si n oesta en ES
		$languages[strtoupper(Config::get("app.locale"))] = 1;
		#añadimos el ES despues para que busque primero en el idioma principal, si el principal es ES, esto no hace nada
		$languages["ES"] = 1;

		$route = $this->getPublicUrlAttribute();

		foreach (array_keys($languages) as $locale) {

			foreach (["webp", "jpg", "gif"] as $extension) {
				$pathImg = $isMobile
					? "{$route}{$locale}_mobile.$extension"
					: "{$route}{$locale}.{$extension}";

				if (file_exists(public_path($pathImg))) {
					return ToolsServiceProvider::urlAssetsCache($pathImg);
				}
			}

			//En caso de no existir en _mobile, realizamos la misma iteranción en desktop
			if($isMobile) {
				return $this->imageRoute(false);
			}
		}

		return null;
	}
}
