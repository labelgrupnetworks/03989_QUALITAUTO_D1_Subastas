<?php
namespace App\Http\Controllers\V5;


use Config;





use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\V5\AppAutomaticPush;
use App\Models\V5\AppPush;
use App\Models\V5\AppPushToken;
use App\Models\V5\AppUsersToken;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use App\Jobs\PushAppJob;
use App\libs\PushAppLib;


class AppPushController extends Controller
{
	const TYPE_SESSION = 'SESSION';

	const MOTIVE_PUBLISH = 'PUBLISH';
	const MOTIVE_START = 'START';

	/*
		TEST en prueba
		$a = new AppPushController();
		$a->startAuction();


		--buscar usuarios que hayan pujado por lotes con los mismo autores ESTA a medias
			select IDVALUE_CARACTERISTICAS_HCES1, REF from FGASIGL0
			fghces1
			join FGCARACTERISTICAS_HCES1 ON NUMHCES_CARACTERISTICAS_HCES1 = NUM_HCES1 AND LINHCES_CARACTERISTICAS_HCES1 = LIN_HCES1
			--COJEMOS DIRECTAMENTE LA SUBASTA DEL HCES1 POR QUE ES LA ULTIMA Y ASÍ NO INCLUIMOS EL ASIGL0
			where
			SUB_ASIGL0='00000155'
			AND
			IDCAR_CARACTERISTICAS_HCES1 = 1

	*/

	#enviará un push indicando que se ha publicado la subasta
	public function publishAuction(){

		#agrupamos por subasta y cogemos el código de sesión más pequeño de las sessiones que no hayan empezado aun
		$sessions = FgSub::select('min("id_auc_sessions") cod_session, cod_sub')
		->join('"auc_sessions"','"auc_sessions"."company" = FGSUB.EMP_SUB AND "auc_sessions"."auction" = FGSUB.COD_SUB')
		#miramos que aun no esten en la tabla de automatismo para qeu solo se envie una vez
		->leftjoin("APP_AUTOMATIC_PUSH", "EMP_AUTOMATIC_PUSH = EMP_SUB AND TYPE_AUTOMATIC_PUSH = 'SESSION' AND CODE_AUTOMATIC_PUSH = \"id_auc_sessions\" AND MOTIVE_AUTOMATIC_PUSH = '".self::MOTIVE_PUBLISH."'" )
		->where("SUBC_SUB","S")->wherein("TIPO_SUB", ["W", "O"])
		->where('"start"', ">", now())
		->where("ID_AUTOMATIC_PUSH", NULL)
		->groupby("cod_sub")->get();
		$this->pushSessionsAllUsers($sessions, self::MOTIVE_PUBLISH);

	}
	#sesiones que vayan a empezar en menos de 24h
	public function startAuction(){


		#agrupamos por subasta y cogemos el código de sesión más pequeño de las sessiones que no hayan empezado aun
		$sessions = FgSub::select('min("id_auc_sessions") cod_session, cod_sub')
		->join('"auc_sessions"','"auc_sessions"."company" = FGSUB.EMP_SUB AND "auc_sessions"."auction" = FGSUB.COD_SUB')
		#miramos que aun no esten en la tabla de automatismo para qeu solo se envie una vez
		->leftjoin("APP_AUTOMATIC_PUSH", "EMP_AUTOMATIC_PUSH = EMP_SUB AND TYPE_AUTOMATIC_PUSH = 'SESSION' AND CODE_AUTOMATIC_PUSH = \"id_auc_sessions\" AND MOTIVE_AUTOMATIC_PUSH = '".self::MOTIVE_START."'" )
		->where("SUBC_SUB","S")->wherein("TIPO_SUB", ["W", "O"])
		->where('"start"', "<",   date('Y-m-d H:i:s', strtotime('+24 hour')))
		->where('"start"', ">", now())
		->where("ID_AUTOMATIC_PUSH", NULL)
		->groupby("cod_sub")->get();

		$this->pushSessionsAllUsers($sessions, self::MOTIVE_START);

	}



	# envia un push a todos los usuarios sobre la sessión
	private function pushSessionsAllUsers($sessions, $motive){

		if(count($sessions)==0){
			return;
		}
		$allTokens = array();

		$users = AppUsersToken::select("TOKEN_USERS_TOKEN")
		//->wherein("CLI_USERS_TOKEN", ["100","1","2","3"])
		->get();
		$allTokens = $users->pluck("token_users_token")->all();

		foreach($sessions as $session){
			$idAutomaticPush = AppAutomaticPush::select("NVL(MAX(ID_AUTOMATIC_PUSH),0)+1 as id_automatic_push")->first();
			#insertamos la linea de automatismo para que no se pueda volver a repetir
			$automatic = [
							"id_automatic_push" => $idAutomaticPush->id_automatic_push,
							"type_automatic_push" =>self::TYPE_SESSION,
							"code_automatic_push" => $session->cod_session,
							"motive_automatic_push" => $motive,
							"date_automatic_push" => now()];
			AppAutomaticPush::create($automatic);

			#Generamos el push e indicamos que pertenece a un push automático
			#multiidioma????
			$idPush = AppPush::select("NVL(MAX(ID_PUSH),0)+1 as id_push")->first();

			$push = [
					"id_push"	=> $idPush->id_push,
					"idautomatic_push" => $idAutomaticPush->id_automatic_push,
					"title_push" => "traduccion del titulo del push",
					"description_push" => "traducción de la descripción del push",
					"action_push" => "LotDetailScreen",
					"info_push"	=> "{codsession:$session->cod_session}",
					"numtokens_push" => count($allTokens),
					"date_push"	=> now()
			];

			AppPush::create($push);
			$this->pushInQueue($idPush->id_push, $allTokens, $push["title_push"], $push["description_push"], $push["action_push"], $push["info_push"] );

		}



	}

	public function pushInQueue($idPush,$allTokens, $title, $description, $action, $info=[] ){
		$url = "http://www.newsubastas.test/api-ajax/push_app";
		$numTokens = 400;
		$pushLib = new PushAppLib($idPush,$url,$title, $description, $action, $info );
		$arrayTokens = array_chunk($allTokens,$numTokens);
		foreach($arrayTokens as $tokens){
			$pushQueue = clone $pushLib;
			$pushQueue->setTokens( $tokens);
			PushAppJob::dispatch($pushQueue)->onQueue( "pushapp");
		}

	}


	public function pushTestEndPoint(){


		\Log::info("pruebas de end point de push ". print_r(request()->all(),true) . now());
		return ;
	}


}
