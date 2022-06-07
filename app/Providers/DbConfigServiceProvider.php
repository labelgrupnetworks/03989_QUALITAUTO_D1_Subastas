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
    }

    public static function slug($name)
    {

    }
}