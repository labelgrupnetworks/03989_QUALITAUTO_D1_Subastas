<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use App\Http\View\Composers\GlobalComposer;
use App\Models\PageSetting;

class ShareVarsProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$host = Config::get('app.url');
		$theme = Config::get("app.theme");

		if (!empty($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0, 6) == '/admin') {
			$adminTheme = Config::get("app.admin_theme");
			$base_url = asset("/themes_admin/$adminTheme/assets");
			$img_url = $base_url . "/img";
		} else {
			$base_url = asset("/themes/$theme");
			$img_url = $base_url . "/img";
		}

		//solo admin
		$images_url = $base_url . "/images";

		View::share('theme', $theme);
		View::share('base_url', $base_url);
		View::share('img_url', $img_url);
		View::share('images_url', $images_url);
		View::share('host', $host);
		View::share ('page_settings', new PageSetting());

		//para seleccionar a que vistas afecta (* a todas, ['name ruta', 'name ruta'] a varias)
		//se pueden usar la session dentro del composer
		if (config('app.global_auctions_var', true)) {
			View::composer(['includes.header', 'includes.footer', 'content.home', 'includes.tiempo_real_btn'], GlobalComposer::class);
		}
	}

	public function register()
	{
		//
	}
}
