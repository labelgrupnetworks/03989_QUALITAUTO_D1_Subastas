<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Config;
use Log;
use URL;

class DbConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    public function register()
    {
        $emp = $this->app->config->get('app.emp');
        $config     = DB::select("SELECT KEY, VALUE FROM WEB_CONFIG where emp=:EMP",
                array(
                        'EMP' =>$emp,
                    )
                );
        $arr_config = array();

        foreach ($config as $key => $value) {
            $arr_config[$value->key] = $value->value;

            Config::set('app.'.$value->key.'', $value->value);
        }

		#Futuver quiere que se pueda cambiar el password del correo
		/*
		if(!empty(\Config::get('app.mail_password'))){
			Config::set('mail.password', \Config::get('app.mail_password'));
		}
		*/
		
    }

    public static function slug($name)
    {

    }
}
