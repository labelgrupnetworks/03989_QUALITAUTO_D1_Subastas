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

		$userPermissions = WebServiceUser::loginWebServiceUser($auth[0], $auth[1]);

		#Si la validación no es correcta devolvemos que no se ha poddo autenticar
		if(count($userPermissions) == 0){
			return response()->json([
                'status' => "ERROR",
                'message' => 'Unauthenticated.'], 401);
        }
		#la APP tendrá muchas funciones por lo que para que no haya que dar permiso a todas las llamadas se haran con $funcion_APP usaremos la _ para agrupar funciones con permisos,
		#si alguien tiene permiso para APP todas las llamadas que vengan con $funcion_APP serán validadas
		$functionExplode = explode("_", request("function"));
		if(!empty($functionExplode[1])){
			$permission = $functionExplode[1];
		}else{
			#si no es compuesto cogemos la funcion que pasan
			$permission =request("function");
		}


		#si no tiene permiso para esta función, avisamos que no tiene y que compruebe si la ha escrito correctamente.
		if (!in_array($permission, $userPermissions)){
			return response()->json([
                'status' => "ERROR",
                'message' => 'You do not have permission to execute this function, confirm that it is written correctly '], 401);
		}



        return $next($request);

    }

}
