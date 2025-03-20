<?php

namespace App\Services\Content;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BannerService
{
	/**
	 * Get the banner by key name
	 * Metodo para tabla web_banner en desuso.
	 * Solamente lo utilizan Gutinvest, Soler y Bonanova, mirar si se puede eliminar
	 */
	public function getOldBannerByKeyname($keyBanner)
	{
		$theme = Config::get('app.theme');
		$main_emp = Config::get('app.main_emp');
		$emp = Config::get('app.emp');

		$keyCache = "content.slider.{$keyBanner}.{$theme}.{$main_emp}";
		$timeToCache = Config::get('app.time_cache', 180);

		return Cache::remember($keyCache, $timeToCache, function() use ($keyBanner, $emp) {
			return DB::table('WEB_BANNER')
				->join('WEB_RESOURCE_BANNER', 'WEB_RESOURCE_BANNER.ID_WEB_BANNER', '=', 'WEB_BANNER.ID_WEB_BANNER')
				->join('WEB_RESOURCE', 'WEB_RESOURCE.ID_WEB_RESOURCE', '=', 'WEB_RESOURCE_BANNER.ID_WEB_RESOURCE')
				->select('WEB_RESOURCE.*')
				->where('WEB_BANNER.KEY_NAME', $keyBanner)
				->where('WEB_BANNER.ENABLED', 1)
				->where('WEB_RESOURCE.ENABLED', 1)
				->where('WEB_BANNER.ID_EMP', $emp)
				->orderBy('WEB_RESOURCE_BANNER.ORDEN', 'asc')
				->limit(10)
				->get();
		});
	}

	public function getOldBannerWithSliderBlade($key, $html)
	{
		$slidders = $this->getOldBannerByKeyname($key);
		$data = [
			'key' => $key,
			'html' => $html,
			'slidders' => $slidders
		];

		return view('front::content.slider', ['data' => $data])->render();
	}
}
