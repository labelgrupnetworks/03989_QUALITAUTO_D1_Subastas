<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Session;
use Redirect;
use Request;

class AdminAuth
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
        if(Session::has('user.admin') || Session::has('user.adminconfig')) {
            return $next($request);
        }else{

            if (Request::ajax()){
                $res = array(
                    'status' => 'error',
                    'redirect' => '/admin',
                    'msg' => trans(\Config::get('app.theme').'-admin.ajax.session_timeout')
                );

                die(json_encode($res));
            }

            return Redirect::to('/admin/login');
        }
    }
}
