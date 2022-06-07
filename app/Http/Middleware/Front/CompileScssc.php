<?php

namespace App\Http\Middleware\Front;

use Closure;
use scssc;
use scss_server;
use Config;

class CompileScssc
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(empty(Config::get('app.bootstrap_version'))) {
            exit('La versión de Bootstrap no está definida en la configuracion config/app.php o desde DB');
        }

        if (Config::get('compile_css') || !empty(\Input::get('compile'))){
            $scss = new scssc();
            $basedir = realpath(base_path('public/themes/'.Config::get('app.theme')));

            $scss->setImportPaths($basedir);
            $compiled =  $scss->compile('@import "main";');
            file_put_contents($basedir.'/'.'default'.'.css', $compiled);

            # Compilar todo el Bootstrap
            $scss           = new scssc();
            $themeDir       = realpath(base_path('public/themes/'.Config::get('app.theme')));
            $BootstrapDir   = realpath(base_path('public/vendor/bootstrap/'.Config::get('app.bootstrap_version').'/sass'));
            $BootstrapSaveDir = realpath(base_path('public/vendor/bootstrap/'.Config::get('app.bootstrap_version').'/css'));

            $scss->setImportPaths($BootstrapDir);
            $compiled   = $scss->compile('@import "_bootstrap";');
            //file_put_contents($BootstrapSaveDir.'/bootstrap.min.css', $compiled);

        }
        
        return $next($request);
    }
}