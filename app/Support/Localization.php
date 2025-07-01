<?php

namespace App\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Localization
{
    /**
     * Verifica si el idioma actual es el idioma predeterminado.
     *
     * @return bool
     */
    public static function isDefaultLocale(): bool
    {
        return Config::get('app.locale') === Config::get('app.fallback_locale');
    }

    /**
     * Obtiene el idioma completo del idioma actual configurado.
     *
     * @return string
     */
    public static function getLocaleComplete(): string
    {
        return self::getLanguageComplete(Config::get('app.locale'));
    }

    /**
     * Obtiene el idioma completo para un idioma específico.
     *
     * @param string $locale El código del idioma (por ejemplo, 'es', 'en').
     * @return string
     */
    public static function getLanguageComplete(string $locale): string
    {
        $languages = Config::get('app.language_complete');
        return data_get($languages, $locale, 'es-ES');
    }

    /**
     * Obtiene el idioma predeterminado en mayúsculas.
     *
     * @return string
     */
    public static function getDefaultUpperLocale(): string
    {
        return Str::upper(Config::get('app.fallback_locale'));
    }

    /**
     * Obtiene el idioma actual en mayúsculas.
     *
     * @return string
     */
    public static function getUpperLocale(): string
    {
        return Str::upper(Config::get('app.locale'));
    }

	/**
	 * Obtiene las locales disponibles en la aplicación.
	 *
	 * @return array<string>
	 */
	public static function getAvailableLocales(): array
	{
		return array_keys(Config::get('app.locales'));
	}
}
