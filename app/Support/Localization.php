<?php

namespace App\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Localization
{
	public static function getLocaleComplete()
	{
		return self::getLanguageComplete(Config::get('app.locale'));
	}

	public static function getLanguageComplete($locale)
	{
		$languages = Config::get('app.language_complete');
		return data_get($languages, $locale, 'es-ES');
	}

	public static function getDefaultUpperLocale()
	{
		return strtoupper(Config::get('app.fallback_locale'));
	}

	public static function getUpperLocale()
	{
		return Str::upper(Config::get('app.locale'));
	}

}
