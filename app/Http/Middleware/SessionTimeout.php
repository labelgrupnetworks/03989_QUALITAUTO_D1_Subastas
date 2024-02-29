<?php namespace App\Http\Middleware;

use Closure;
use Session;
use Request;

    class SessionTimeout
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */

        public function handle($request, Closure $next, $timeout)
        {
            if(Session::has('user.lastActivityTime') && time() - Session::get('user.lastActivityTime') > $timeout) {

                Session::flush();

                if (Request::route()->getPrefix() == '/admin') {
                    if (Request::ajax()){
                        $res = array(
                            'status' => 'error',
                            'redirect' => '/admin',
                            'msg' => trans("admin-app.login.session_timeout")
                        );

                        die(json_encode($res));
                    }
                    return redirect('admin/login')->withErrors(trans("admin-app.login.session_timeout"));
                }else{
                    return redirect(\Routing::slug('/'))->withErrors(trans("admin-app.login.session_timeout"));
                }
            }

            Session::put('user.lastActivityTime',time());
            return $next($request);
        }

    }
