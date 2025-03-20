<?php

namespace App\Support;

use Illuminate\Support\Facades\Config;

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


}
