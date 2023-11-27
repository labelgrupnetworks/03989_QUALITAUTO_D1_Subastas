<?php

namespace App\Http\Middleware;
use Closure;
use App\Models\apilabel\WebApiUser;

use App\Models\apilabel\WebApiLog;

class AuthApi
{
    public function handle($request, Closure $next)
    {

        $authorization = explode (" " ,$request->header("authorization"));
        if(empty($authorization[1])){
            return response()->json([
                'status' => "ERROR",
                'message' => 'Unauthenticated.'], 401);
        }

        $auth = explode (":",base64_decode($authorization[1]));

		#\Log::info("auth 0= ".$auth[0]." auth 1=". $auth[1]);
        if (empty($auth[0]) || empty($auth[1]) || !WebApiUser::loginApiUser($auth[0], $auth[1])){

            return response()->json([
                'status' => "ERROR",
                'message' => 'Unauthenticated.'], 401);
        }
        #Guardar Log de API
        $webApiLog = array();
        $webApiLog["model_api_log"] = str_replace("apilabel/","", $request->path());
        $webApiLog["method_api_log"] = $request->method();
        $webApiLog["user_api_log"] = $auth[0];
        $webApiLog["info_api_log"] = "";
		
        if($request->isMethod('delete') || env('APP_DEBUG') || ( $request->isMethod('put') && \Config::get("app.api_debug_put")) || ( $request->isMethod('post') && \Config::get("app.api_debug_post"))) { // $request->isMethod('put') || $request->isMethod('post')
            $params = mb_substr(json_encode($request->all()), 0 , 2000);
			$webApiLog["info_api_log"] = $params;
			WebApiLog::create($webApiLog);
        }

        return $next($request);

    }

}
