<?php

namespace App\Http\Middleware;
use Closure;
use App\Models\webservice\WebServiceUser;
use Request;

class AuthWebservice
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



        if (empty($auth[0]) || empty($auth[1])){

            return response()->json([
                'status' => "ERROR",
                'message' => 'Unauthenticated.'], 401);
        }

		$userFunctions = WebServiceUser::loginWebServiceUser($auth[0], $auth[1]);

		#Si la validación no es correcta devolvemos que no se ha poddo autenticar
		if(count($userFunctions) == 0){
			return response()->json([
                'status' => "ERROR",
                'message' => 'Unauthenticated.'], 401);
        }
		#si no tiene permiso para esta función, avisamos que no tiene y que compruebe si la ha escrito correctamente.
		elseif (!in_array(request("function"), $userFunctions)){
			return response()->json([
                'status' => "ERROR",
                'message' => 'You do not have permission to execute this function, confirm that it is written correctly '], 401);
		}



        return $next($request);

    }

}
