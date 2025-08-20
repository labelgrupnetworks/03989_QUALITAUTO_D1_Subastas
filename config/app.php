<?php

use Illuminate\Support\Facades\Facade;

$localesKeys = array_map('trim', explode(',', env('LOCALES_KEYS', 'es')));
$localesValues = array_map('trim', explode(',', env('LOCALES_VALUES', 'Español')));

$defaultConfig = [

	/*
    |--------------------------------------------------------------------------
    | Subastas
    |--------------------------------------------------------------------------
    |
    */
    'theme' => env('APP_THEME', 'demo'),
	'default_theme' => env('APP_DEFAULT_THEME', 'v1'),
	'locales' => array_combine($localesKeys, $localesValues),
    'language_complete' => ['es' => 'es-ES', 'en' => 'en-GB'],
    'emp'   => env('APP_EMP', '001'),
	'gemp'  => env('APP_GEMP', '01'),
	'main_emp'   => env('APP_MAIN_EMP', env('APP_EMP', '001')),
	'filter_total_shown_options' => array_map('trim', explode(',', env('FILTER_OPTIONS', '12, 24, 36, 48'))),
	'node_url' => env('NODE_URL'),
	'log' => env('APP_LOG', 'daily'),
    'config_general_admin' => array(),
	'config_menu_admin' => array_map('trim', explode(',', env('MENU_ADMIN', 'traducciones, newbanner, content_page'))),
    'admin_session_timeout' => 3600,
    'admin_theme' => 'porto',
	'tmp_upload_folder' => 'uploads/tmp',
	'log_max_files' => env('APP_LOG_MAX_FILES', 30),
	'img_lot'  => '/img',
	'accesstoken' => env('VOTTUN_TOKEN', null),
	'queue_env'   => env('QUEUE_ENV'),
	'force_https' => env('FORCE_HTTPS', false),

	/**
	 * Agrupar subastas por variable de entorno
	 */
	'agrsub' => env('APP_AGRSUB', null),

	'captcha_v3_public' => env('CAPTCHA_SITE_KEY', null),
	'captcha_v3_private' => env('CAPTCHA_SECRET_KEY', null),


	/**
	 * Desarrollo de depositos con representantes y notificaciones con asunto bancario
	 */
	// 'withRepresented' => true,
	// 'withDepositNotification' => true,


	/**
	 * Redsys
	 */
	'MerchandCodeRedsys' => env('REDSYS_MERCHANDCODE', null),
	'KeyRedsys' => env('REDSYS_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Subastas'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => env('TIMEZONE', 'Europe/Madrid'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'es',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'es',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'es_ES',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        /* subastas */
        App\Providers\ShareVarsProvider::class,
        App\Providers\DbConfigServiceProvider::class,
		Maatwebsite\Excel\ExcelServiceProvider::class,
		Barryvdh\DomPDF\ServiceProvider::class,
		Intervention\Image\ImageServiceProvider::class

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        'Tools'         => App\Providers\ToolsServiceProvider::class,
        'Routing'       => App\Providers\RoutingServiceProvider::class,
        'CacheLib'      => \App\libs\CacheLib::class,
        'Controller'      => App\Http\Controllers\Controller::class,
        'BannerLib'         => App\libs\BannerLib::class,
        'Currency'         => App\libs\Currency::class,
		'FormLib'           => App\libs\FormLib::class,
		'Excel' => Maatwebsite\Excel\Facades\Excel::class,
		'PDF' => Barryvdh\DomPDF\Facade\Pdf::class, //Barryvdh\DomPDF\Facade::class,
		'Image' => Intervention\Image\Facades\Image::class
    ])->toArray(),

];

return array_merge(
	$defaultConfig,
	include __DIR__ . '/label/app.php',
);
