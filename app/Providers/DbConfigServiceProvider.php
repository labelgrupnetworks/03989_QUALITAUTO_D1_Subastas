<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;


class DbConfigServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		# Nombres de espacios
		View::addNamespace('front', [
			resource_path('/views/themes/' . Config::get('app.theme')),
			resource_path('/views/default/' . Config::get('app.default_theme')),
		]);
		View::addNamespace('admin', resource_path('/views/admin/' . Config::get('app.admin_theme')));
	}

	public function register()
	{

		// Configuración de logs dependiendo de si estamos en consola o no
		$this->app->runningInConsole()
			? Config::set('logging.default', 'cli') //colas, comandos y tareas programadas
			: Config::set('logging.default', 'daily'); //web

		$emp = $this->app->config->get('app.emp');
		$config  = DB::select(
			"SELECT KEY, VALUE FROM WEB_CONFIG where emp=:EMP",
			array(
				'EMP' => $emp,
			)
		);
		$arr_config = array();

		foreach ($config as $value) {
			$arr_config[$value->key] = $value->value;

			Config::set('app.' . $value->key . '', $value->value);
		}

		#añadimos ahora la ruta de default así se puede definir por base de datos
		$default = [resource_path('/views/default/' . Config::get('app.default_theme'))];

		Config::set('view.paths', array_merge(Config::get('view.paths'), $default));
	}

	/**
	 * Set the logging channel according to the running environment
	 * Por el momento determinamos el loggin setando el config de logging.default,
	 * pero si diese problemas se podría modificar el canal de loggin directamente
	 */
	private function setConfigLoggingChannel()
	{
		if ($this->app->runningInConsole()) {
			$logManager = $this->app->make('log');
            $logManager->setDefaultDriver('cli');
        }
	}
}
