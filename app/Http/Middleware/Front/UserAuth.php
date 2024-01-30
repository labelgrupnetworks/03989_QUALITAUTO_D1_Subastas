<?php

namespace App\Http\Middleware\Front;

use Closure;
use Session;
use Redirect;
use Request;

class UserAuth
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
        if(Session::has('user')) {
            return $next($request);
        }else{

            if (Request::ajax()){
                $res = array(
                    'status' => 'error',
                    'redirect' => \Routing::slug('login'),
                    'msg' => trans("admin-app.login.session_timeout")
                );

                die(json_encode($res));
            }

            if(\Config::get('app.modal_login')){
                $view_login = "?view_login=true";
            }else{
                $view_login="";
            }

            return Redirect::to(\Routing::slug('login').$view_login);
        }
    }
}
