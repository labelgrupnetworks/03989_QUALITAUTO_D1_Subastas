<?php

namespace App\Http\Controllers;

use Request;
use Requests;
use View;
use Routing;
use Route;
use Illuminate\Support\Facades\Request as Input;

use Illuminate\Support\Facades\Config;
use Session;
use DateTime;

# Modelos
use App\Http\Controllers\MailController;
use App\Http\Controllers\V5\CarlandiaPayController;
use App\Models\SubastaTiempoReal;
use App\Models\Subasta;
use App\Models\User;
use App\Models\Chat;
use App\Models\Favorites;
use App\Models\Subalia;
use App\libs\EmailLib;
use DB;
use Illuminate\Support\Facades\Log;

use App\libs\ImageGenerate;
use App\libs\StrLib;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgHces1;
use App\Models\V5\FgAsigl1_Aux;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Models\V5\FgCreditoSub;
use App\Models\V5\FxCli;
use App\Models\V5\Web_Cancel_Log;
use App\Providers\ToolsServiceProvider as Tools;
use App\Models\V5\FgSub;

class subastaTiempoRealController extends Controller
{
    public $cod;
	public $ref;
	public $cod_original_licit = null;

    # Versión en tiempo real de las subastas
    # ej: es/api/subasta-1A15/6-blabla
    public function index()
    {

       $t= time();
        $js_item = array();

    # Seteamos el parametro cod subasta para el retorno de login de tiempo real
        Session::put('tiempo_real.cod', Route::current()->parameter('cod'));
        $subasta        = new Subasta();
        $subasta->cod   = Route::current()->parameter('cod');
        $subasta->tipo          = "'W'";
        $subasta->texto         = Route::current()->parameter('texto');
		preg_match('#.*-(\d+)$#', $subasta->texto, $matches);
		if (count($matches)<2  ){
            exit (\View::make('front::errors.404'));
        }
        $subasta->id_auc_sessions = $matches[1];
        //si no existe la subasta y la session
        $session = $subasta->get_session($subasta->id_auc_sessions);

        $datos_sub = $subasta->getInfSubasta();
        if (empty($session) || empty($datos_sub) || ($datos_sub->tipo_sub != "W" && $datos_sub->subastatr_sub != "S") || ($datos_sub->subc_sub !="S" && $datos_sub->subc_sub !="A" )){
			if(config('app.redirect_auction_finish_to_home')){
				return redirect()->route('home', [], 301);
			}
            exit (\View::make('front::errors.404'));
        }
        $subasta->session_reference = $session->reference;



        //El tiempo real debe estar ordenado por el orden en vez de por la referencia
        $subasta->order_by_values = "ORDEN_HCES1, REF_asigl0";
        $subasta->page          = 'all';

        $js_item['lang_code'] = strtoupper(\App::getLocale());
        //pongo el idioma en minusculas
       //$js_item['lang_code'] = \App::getLocale();
        # Retornamos la información del usuario


        if(Session::has('user')) {

            # Cogemos la informacion del usuario ya que necesitamos datos de otras tablas como RSOC_CLI
            $user                = new User();

            $user->cod_cli       = Session::get('user.cod');
             //contabilizamos la conexión del usuario

            //solo guardamos a partir del día de la subasta
            if(time() > strtotime(date('Y-m-d',strtotime($session->start)))){
                $userControl = new UserController();
                $ip=$userControl->getUserIP();
                $user->setVisitRealTime($user->cod_cli , $subasta->id_auc_sessions, $ip);
            }



            $usuario             = $user->getUser();


            # Comprobamos si tiene un código de licitador asignado, de lo contrario le asignaremos uno.
            $subasta->cli_licit = Session::get('user.cod');
            $subasta->rsoc      = !empty($usuario->rsoc_cli) ? $usuario->rsoc_cli : $usuario->nom_cli;

            # Check dummy bidder y codigo licitador de subasta
            $subasta->checkDummyLicitador();

			# Si tienen numero de ministerio asignado, creamos ministerio como licitador
			if(Config::get('app.ministeryLicit', false)){
				$subasta->checkOrInstertMinisteryLicitador(Config::get('app.ministeryLicit'), 'Ministerio');
			}

            $res = $subasta->checkLicitador();


            $js_item['user']['cod_licit'] = head($res)->cod_licit;
            //pasamos el token del usuario
            $js_item['user']['tk'] = Session::get('user.tk');
            //$js_item['user']['lang_code'] = strtoupper(\App::getLocale());
            //$js_item['user']['is_gestor'] = $usuario->tipo_cliweb == 'G' ? TRUE : FALSE;
            $js_item['user']['is_gestor'] = $usuario->tipacceso_cliweb == 'S' ? TRUE : FALSE;
            $js_item['user']['cod_div_cli'] = !empty($usuario->cod_div_cli)? $usuario->cod_div_cli : '';
            $js_item['user']['adjudicaciones'] = array();
             //obtener adjudicaciones del usurio

            $adjudicaciones = $user->getAllAdjudicacionesSession($subasta->cod, $subasta->session_reference, $js_item['user']['cod_licit']);
            $strLib = new StrLib();
            //el js espera los campos  ref_asigl1 y imp_asigl1 así que los mantengo
            foreach($adjudicaciones as $adj){
                $adjudicacion= new \stdClass();
                $adjudicacion->ref_asigl1 = $adj->ref_csub;
                $adjudicacion->imp_asigl1 = $adj->himp_csub;


                $js_item['user']['adjudicaciones'][] = $adjudicacion;
            }

            $js_item['user']['favorites'] = array();

            $fav = new Favorites($subasta->cod, $js_item['user']['cod_licit']);
            $favs = $fav->getFavs();

            if (!empty($favs['data'])){
                $js_item['user']['favorites'] = $favs['data'];
            }


            //si el usuario es gestor comprobamos que los lotes esten bien ordenados, si no lo estan los ordenamos
            if( $usuario->tipacceso_cliweb == 'S'){
                if (!$subasta->checkOrderLots()){
                    $subasta->asignOrderToAllLotes();

                }
            }
        }

        # Si tenemos el modulo de chat activado cargaremos los primeros mensajes en sala.
		//todos los clientes lo tienen activado, pero en caso de que no, producia un error.
        $mensajes_chat = ['data' => []];
        if(Config::get('app.tr_show_chat')) {

            $chat       = new Chat();
            //$chat->lang = \App::getLocale();
            $chat->cod  = Route::current()->parameter('cod');

            $mensajes_chat = $chat->getChat();
        }


        //debemos encontrar el lote siguiente, por lo que necesitamos iniciar el orden en 0 ya que el primero tendrá orden 1
        $subasta->orden = 0;
        $subasta->page = 1;
        $subasta->itemsPerPage = 1;

        $lote = $subasta->getNextAvailableLote();

           # Si no hay lote actual esque la subasta ha terminado
        if(empty($lote)) {
            return View::make('front::pages.subasta_tr_finalizada');
        }

		$text_lang = $subasta->getMultilanguageTextLot($lote[0]->num_hces1, $lote[0]->lin_hces1);

        $js_item['text_lang'] = $text_lang;
		#Si el precio salida es c0


		#si el importe de salida es 0 debemos cojer el primer escalado como puja válida
		if($lote[0]->impsalhces_asigl0== 0){
			$lote[0]->impsalhces_asigl0 = head($subasta->AllScales())->scale;
		}


        $escalado_correcto = $subasta->NextScaleBid(0,$lote[0]->impsalhces_asigl0 - 1, false);

        //detectar probleams con el importe de salida si no cumple el escalado, modificamos tambie nel importe de reserva.
        //not_force_correct_price_tiempo_real sirve que en tiempo real no fuerze el escalado correcto en la primer puja
        if($escalado_correcto != $lote[0]->impsalhces_asigl0 && \Config::get('app.force_correct_price') && ( empty(\Config::get('app.not_force_correct_price_tiempo_real'))  || \Config::get('app.not_force_correct_price_tiempo_real') != 1)){
            $lote[0]->impsalhces_asigl0 = $escalado_correcto;
            $lote[0]->impres_asigl0 = $escalado_correcto;
        }
		$subasta_info = new \stdClass();


		$subasta_info->lote_actual = head($subasta->getAllLotesInfo($lote));
		#poner autor del lote en tiempo real
		$subasta_info->lote_actual->autor = "";
		if( \Config::get("app.AutorInTR")){
			$FgCaracteristicas = new 	FgCaracteristicas_Hces1();
			$autor =	$FgCaracteristicas->select("nvl(  VALUE_CARACTERISTICAS_VALUE,  value_caracteristicas_hces1 ) value_caracteristicas_hces1")->JoinCaracteristicas()->JoinCateristicasValue()->where("IDCAR_CARACTERISTICAS_HCES1",\Config::get("app.AutorInTR") )->where("NUMHCES_CARACTERISTICAS_HCES1", $subasta_info->lote_actual->num_hces1)->where("LINHCES_CARACTERISTICAS_HCES1", $subasta_info->lote_actual->lin_hces1)->first();
			if(!empty($autor)){
				$strLib = new StrLib();

				$subasta_info->lote_actual->autor =  $strLib->CleanStr($autor->value_caracteristicas_hces1);

			}

		}


        $refLoteSiguiente = $subasta->getNextPreviousLot("NEXT", head($lote)->orden_hces1, 'order', 'N');


        if (!empty($refLoteSiguiente)){
            $subastaLoteSiguiente = new subasta();
            $subastaLoteSiguiente->cod   = $subasta->cod;
            $subastaLoteSiguiente->lote = $refLoteSiguiente ;
            $subastaLoteSiguiente->id_auc_sessions  = $subasta->id_auc_sessions;

            $LoteSiguiente = $subastaLoteSiguiente->getLote();

            $subasta_info->lote_siguiente = head($subasta->getAllLotesInfo($LoteSiguiente));
			$inf_lot_translate = $subasta->getMultilanguageTextLot( $subasta_info->lote_siguiente->num_hces1,  $subasta_info->lote_siguiente->lin_hces1);
			$subasta_info->lote_siguiente->text_lang = $inf_lot_translate;
			$lang = strtoupper(Config::get('app.locale'));


			$subasta_info->lote_siguiente->titulo_hces1 = $inf_lot_translate[$lang]->titulo_hces1;
			$subasta_info->lote_siguiente->desc_hces1 = $inf_lot_translate[$lang]->desc_hces1;
			$subasta_info->lote_siguiente->descweb_hces1 = $inf_lot_translate[$lang]->descweb_hces1;



        }else{
            //ojo he puesto clone por que si no al cambiar imagen de lote_actual a base 64 cambiaba tambien la de lote siguiente
            $subasta_info->lote_siguiente = clone  $subasta_info->lote_actual;
        }




        // para el lote anterior no debe mirar que esté cerrado
        /* 2017_07_10 creo que no sirve para nada el lote anterior, de momento no lo calculo
        $refLoteAnterior = $subasta->getNextPreviousLot("PREVIOUS", $lote->ref_hces1, 'order' );
         */



        if (!empty($js_item['user']['adjudicaciones'])){
            $js_item['user']['adjudicaciones'] = array_reverse($js_item['user']['adjudicaciones']);
        }


        # Estado de la subasta TR
        $SubastaTR      = new SubastaTiempoReal();
        $SubastaTR->cod = $subasta->cod;
        $SubastaTR->session_reference =  $subasta->session_reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);
        $status  = head($SubastaTR->getStatus());

        # En caso de que no exista estado de pausa
        if(empty($status)) {
            $status = new \stdClass();
            $status->estado      = null;
            $status->reanudacion = null;


        }

        $js_item['subasta']['status']       = $status->estado;
        $js_item['subasta']['reanudacion']  = $status->reanudacion;
        $subasta_info->status               = $status->estado;
        $subasta_info->reanudacion          = $status->reanudacion;



        /* 2017_07_10 Ahora se calcula desde el cambio de estado, para evitar que al redireccionar a todo el mundo a la `´agina se ejecute en paralelo varias veces */
        // Debe estar calculado antes de llegar aquí, ya que se calcula al cambiar de estado, por ejemplo al iniciar la subasta, pero por si acaso lo pongo
        /* Lo Quito definitivamente 2020_02_10
        if ($status->estado == 'in_progress' && Session::has('user') && $usuario->tipacceso_cliweb == 'S' )
        {
             \Log::info("dentro calculateStartbid");
            $subasta_info->lote_actual = $subasta->calculateStartBid($subasta_info->lote_actual);
        }
        */
        # Información de la subasta
        $subasta_info->cod_sub = $subasta->cod;
        $subasta_info->start =  $session->start;
        $subasta_info->reference =  $session->reference;

        /* 2017-07-05
    $desde      = str_replace('00:00:00', '', trim($subasta_info->lote_actual->dfec_sub));
        $desde      = str_replace('-', '/', $desde);
        $fecha_in   = $desde.$subasta_info->lote_actual->dhora_sub;

        $subasta_info->fecha_inicio_subasta = \Tools::euroDate($fecha_in);
        * */
        //a efectos practicos es más sencillo sustituir el valor del importe de salida por el del importe de reserva, así no hay que tratarlos por separado


        //NO tengo claor que esto sirva para algo

        # Asignamos el escalado para el próximo.
        if(isset($subasta_info->lote_actual->max_puja->imp_asigl1) && is_numeric($subasta_info->lote_actual->max_puja->imp_asigl1)) {
           // $subasta->imp = max($subasta_info->lote_actual->max_puja->imp_asigl1,$subasta_info->lote_actual->impres_asigl0);
            $subasta->imp = $subasta_info->lote_actual->max_puja->imp_asigl1;
        } else {
            //$subasta->imp =  max($subasta_info->lote_actual->impres_asigl0, $subasta_info->lote_actual->impsalhces_asigl0 );
            $subasta->imp = $subasta_info->lote_actual->impsalhces_asigl0;
        }
       if(!isset($subasta_info->lote_actual->max_puja->imp_asigl1)){
           $subasta->sin_pujas = true;
       }




         //el importe de salida ha podido ser sustituido por el importe de reserva si este es mayor
        //$la_escalado = $subasta->NextScaleBid( max($subasta_info->lote_actual->impres_asigl0, $subasta_info->lote_actual->impsalhces_asigl0 ),$subasta_info->lote_actual->actual_bid);
        $la_escalado = $subasta->NextScaleBid($subasta_info->lote_actual->impsalhces_asigl0,$subasta_info->lote_actual->actual_bid);

        $subasta_info->lote_actual->importe_escalado_siguiente = $la_escalado;
        /* cambio funcion escalado esto debería sobrar
        if($subasta->imp == $la_escalado) {
            $subasta->imp   = $subasta->imp + 1;
            $siguiente      = $subasta->escalado();
            $subasta_info->lote_actual->importe_escalado_siguiente = $siguiente;
        }

         */






        # Información necesaria para el js
        $js_item['subasta']['cod_sub']  = $subasta_info->cod_sub;

        $js_item['lote_anterior']       = array();
        $js_item['lote_siguiente']      = array();
        $js_item['lotes_pausados']      = self::getLotesPausados($subasta);
        $subasta->estado_lotes = '';

        if (!empty($subasta_info->lote_anterior)) {
            $js_item['lote_anterior']       = $subasta->CleanStrLote($subasta_info->lote_anterior);
        }

        $js_item['lote_actual']         = $subasta->CleanStrLote($subasta_info->lote_actual);

        if (!empty($subasta_info->lote_siguiente)) {
            $js_item['lote_siguiente']      = $subasta->CleanStrLote($subasta_info->lote_siguiente);
        }

        # Obtiene el máximo de lotes de la subasta
        $subasta->page = 'all';
        $js_item['subasta']['last_item']    = $subasta_info->last_item = $session->end_lot;
        $js_item['subasta']['first_item']      = $subasta_info->first_item =  $session->init_lot;
        $js_item['subasta']['currency']       = $subasta->getCurrency();
        $js_item['subasta']['cd_time']        =  Config::get('app.cd_time');
    $js_item['subasta']['max_bids_shown'] =  Config::get('app.pujas_maximas_mostradas');


        # Mensajes de chat cargados al item de js
        $js_item['chat']['mensajes']        = $mensajes_chat['data'];


        /*
        $primerItem = head($lotes);
        $subasta_info->status       = $primerItem->status;
        $subasta_info->reanudacion  = $primerItem->reanudacion;
        */



        if(empty($subasta_info->lote_siguiente)) {
            $subasta_info->lote_siguiente = $subasta_info->lote_actual;
        }

        $subasta->lote = $subasta_info->lote_actual->orden_hces1;
        $subasta->page = 1;
        $subasta->itemsPerPage = Config::get('app.distance_to_play_favs');
        $js_item['subasta']['nextLotes'] = $subasta->getNextAlarmLotes();

        # Añadimos la ultima puja y la ultima orden del usuario, en caso de que tenga.
        if (!empty($js_item['user'])) {
            $subasta->ref           = $subasta_info->lote_actual->ref_asigl0;
            $subasta->page          = 1;
            $subasta->itemsPerPage  = 1;

            $pujaUser  = head($subasta->getPujas($js_item['user']['cod_licit']));
            $ordenUser = head($subasta->getOrden($js_item['user']['cod_licit']));
            if(!empty($ordenUser)) {
                $ordenUser->himp_orlic = \Tools::moneyFormat($ordenUser->himp_orlic);
            }

            $js_item['user']['maxPuja']     = $pujaUser;
            $js_item['user']['maxOrden']    = $ordenUser;
        }

        $js_item['subasta']['dummy_bidder'] = Config::get('app.dummy_bidder');

        # Cargamos todas las ordenes de licitacion del lote actual
        $js_item['lote_actual']->ordenes = $subasta->getOrdenes();


        $js_item['subasta']['id_auc_sessions'] = $subasta->id_auc_sessions;
        //No devolver esta variable en la ficha del lote de tipo O, cuando se haga una puja ara un circuito incorrecto
        $js_item['subasta']['sub_tiempo_real'] = 'S';// $datos_sub->subastatr_sub;
        $js_item['subasta']['rewrite_url_session'] = $subasta->texto;

        $js_item['subasta']['cod_div_cli'] =!empty($js_item['user'])? $js_item['user']['cod_div_cli'] : '';

        $cedentes = $subasta->getCedentesSub();
        $licitadores = array();
        if(Session::has('user') && Session::get('user.admin')) {
            $user->cod_sub = $subasta->cod;
            $licitadores_temp = $user->getSubLicits();
            foreach($licitadores_temp as $licit){
                $licitadores[$licit->cod_licit] = !empty($licit->rsoc_licit)? $strLib->CleanStr($licit->rsoc_licit):'-';
            }
		}

		$credit_info = [
			'current_credit' => 0,
			'credit_used' => 0,
			'available_credit' => 0,
			'current_credit_format' => '0',
			'credit_used_format' => '0',
			'available_credit_format' => '0'
		];
		//Schema::hasTable('FGCREDITOSUB'); //existe tambla?
		if(Session::has('user') && Config::get('app.use_credit', 0)){

			//credito max en este momento
			$currentCredit = FgCreditoSub::getCurrentCredit($usuario->cod_cli, $subasta_info->cod_sub);

			//Si no tiene credito por subasta, su credito minimo lo establece ries_cli
			$currentCredit = $currentCredit ?? $usuario->ries_cli ?? 0;

			//credito usado adjudicacion + maxpuja loteactual si es de usuario
			$creditUsed = 0;
			if(!empty($subasta_info->lote_actual->max_puja) && !empty($pujaUser) && $subasta_info->lote_actual->max_puja->cod_licit == $pujaUser->cod_licit){
				$creditUsed += $subasta_info->lote_actual->max_puja->imp_asigl1;
			}

			foreach($adjudicaciones as $adj){
                $creditUsed += $adj->himp_csub;
			}

			//usuarios con más credito /usuarios con mas credito a que mas hayan pedido??
			//$usersCredit = FgCreditoSub::where('SUB_CREDITOSUB', $subasta_info->cod_sub)->orderBy();

			$credit_info = [
				'current_credit' => intval($currentCredit),
				'credit_used' => $creditUsed,
				'available_credit' => $currentCredit - $creditUsed,
				'current_credit_format' => \Tools::moneyFormat(intval($currentCredit)),
				'credit_used_format' => \Tools::moneyFormat($creditUsed),
				'available_credit_format' => \Tools::moneyFormat($currentCredit - $creditUsed)
			];
		}

        $imageGenerate = new ImageGenerate();


        //ponemos la imagen en base 64
        $subasta_info->lote_actual->imagen = $imageGenerate->resize_img( "lote_medium_large", $subasta_info->lote_actual->imagen, Config::get('app.theme'),true);


        # Asigna los datos al tpl
        $data = array(
                    'text_lang'     => $text_lang,
                    'name'          => $subasta_info->lote_actual->des_sub,
                    'subasta_info'  => $subasta_info,
					//'chat'          => $mensajes_chat,
					'credit_info' => $credit_info,
                    'cedentes'  => $cedentes,
                    'licitadores'=>$licitadores,
                    'node'          => array(
                                            'action_url'    => Config::get('app.url')."/api/action/subasta",
                                            'comprar'       => Config::get('app.url').\Routing::slug('api')."/comprar/subasta",
                                            'status_url'    => Config::get('app.url')."/api/status/subasta",
                                            'chat'          => Config::get('app.url')."/api/chat",
                                            'end_lot'       => Config::get('app.url')."/api/end_lot",
                                            'pause_lot'     => Config::get('app.url')."/api/pause_lot",
                                            'resume_lot'    => Config::get('app.url')."/api/resume_lot",
                                            'cancel_bid'    => Config::get('app.url')."/api/cancel_bid",
                                            'cancel_order'  => Config::get('app.url')."/api/cancel_order",
                                            ),
                    'js_item'       => $js_item
					);


          if(!Route::current()->parameter('proyector')){
              return View::make('front::pages.ficha_tiempo_real', array('data' => $data));
         }else{
              return View::make('front::pages.ficha_tiempo_real_proyector', array('data' => $data));
         }

	}

	public function creditPanel($cod_sub, $name, $id_auc_sessions){

		if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
            return View::make('front::pages.not-logged', array('data' => $data));
		}

		#usuario nuevo

		$userCod = Session::get('user.cod');

		//credito actual en la subasta
		# null
		$currentCredit = FgCreditoSub::getCurrentCredit($userCod, $cod_sub);

		//credito minimo y maximo del usuario
		#riesmax= 0, ries = 10000
		$user_ries = FxCli::select('riesmax_cli', 'ries_cli')->where('COD_CLI', $userCod)->first();

		//Si no tiene credito en subasta, su credito minimo lo establece ries_cli
		#10000
		$currentCredit = $currentCredit ?? $user_ries->ries_cli ?? 0;

		//secciones de credito
		#0

		/**
		 * Eloy: 25/11/2021
		 * Sección de código temporal.
		 * Como ahora el riesmax por defecto es 0, se realiza un bucle infinito que provoca error
		 * Para solucionarlo, hasta que implementen el desarrollo de credito, podemos igualar
		 * ries_cli a riesmax_cli
		 *
		 * Eloy: 22/06/2022
		 * antes if(true)
		 * Simplemente si el riesmax_cli es 0, se iguala a ries_cli.
		 * Por lo que los que lo tengan a 0 no podrán solicitar más crédito.
		 *
		 */
		if(empty($user_ries->riesmax_cli)){
			$user_ries->riesmax_cli = $user_ries->ries_cli;
		}

		$creditDivion = ($user_ries->riesmax_cli) / 2;

		$creditIncreases = [];
		for ($i = $creditDivion; $i <= $user_ries->riesmax_cli; $i += $creditDivion) {
			if($i > $currentCredit){
				$creditIncreases[] = $i;
			}
		}

		//url del tiempo real
		$urlTiempoReal=\Routing::translateSeo('api/subasta').$cod_sub."-".$name."-".$id_auc_sessions;

		return View::make('front::pages.credit', compact('currentCredit', 'creditIncreases', 'cod_sub', 'urlTiempoReal'));
	}


	public function getClientsCreditBySub(){
		$clients = FgCreditoSub::getCreditBySub(Request::input('cod_sub'));
		return $clients;
	}

	public function increaseCredit(){

		$credit = request('credit', 0);
		$cod_sub = request('cod_sub', 0);

		if(!Session::has('user') || empty($credit) || empty($cod_sub)){
			exit (\View::make('front::errors.404'));
		}

		$userCod = Session::get('user.cod');

		$currentCredit = FgCreditoSub::getCurrentCredit($userCod, $cod_sub);

		//credito minimo y maximo del usuario
		$user_ries = FxCli::select('riesmax_cli', 'ries_cli')->where('COD_CLI', $userCod)->first();

		//Si no tiene credito en subasta, su credito minimo lo establece ries_cli
		$currentCredit = $currentCredit ?? $user_ries->ries_cli ?? 0;

		FgCreditoSub::create([
			'CLI_CREDITOSUB' => $userCod,
			'SUB_CREDITOSUB' => $cod_sub,
			'ACTUAL_CREDITOSUB' => $currentCredit,
			'NUEVO_CREDITOSUB' => $credit,
			'FECHA_CREDITOSUB' => new DateTime("now")
		]);

		return redirect(request('urlTiempoReal'));

	}

    public function siguienteEscalado($importe)
    {
            $subasta        =  new Subasta();
            $subasta->imp   = $importe + 1;
            $siguiente      = $subasta->escalado();

            return $siguiente;
    }

    # Obtenemos el rango de la escala
    public function escala($importe)
    {
        $subasta         =  new Subasta();
        $subasta->imp    = $importe;
        $subasta->escala = 1;
        return $subasta->escalado();
    }

	/**
	 * Si no se ha realizado el login, miramos el resultado de la contrapuja para las siguientes acciones.
	 */
	public function preContraOfertar()
	{
		$cod_sub = request("cod_sub");
		$ref_asigl0 = request("ref");
		$counterOffer = request("imp", false);
		$theme = config('app.theme');

		$subasta = new subasta();
        $subasta->cod = $cod_sub;
		$subasta->lote = $ref_asigl0;
        $lote_temp = $subasta->getLote();

		$lote = head($lote_temp);

		// si no es de venta directa o esta cerrado o no tiene precio minimo para realizar contraoferta
		$notCounterOfferPermision = ($lote->imptas_asigl0 ?? 0) <= 0;
		if($lote->tipo_sub != FgSub::TIPO_SUB_VENTA_DIRECTA || $lote->cerrado_asigl0 == 'S' || $notCounterOfferPermision){
            return response()->json([
				'status' => 'error',
				'message' => trans("$theme-app.msg_error.counteroffer")
			]);
        }

		//Con último desarrollo en el que ya no esta el botón comprar esto deja de tener sentido
		//El valor de la contraoferta es superior al precio de salida
		/* if($counterOffer >= $lote->impsalhces_asigl0){
			return response()->json([
				'status' => 'error',
				'message' => trans("$theme-app.msg_error.counteroffer_toobig")
			]);
		} */

		$subasta->imp = $counterOffer;
		$subasta->ref = $ref_asigl0;

		$urlSimilar = (new EmailLib(''))->getUrlGridLots($lote->num_hces1, $lote->lin_hces1, 'V', 0, $counterOffer * 1.25);

		$response = [
			'status' => 'success',
			'message' => '¡Enhorabuena! El Vendedor ha aceptado tu Oferta. Por favor, danos tus datos para tramitarla.',
			'messageToCancel' => '¿Estás seguro de que no quieres tramitar tu Oferta?',
			'counterofferType' => FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA,
			'amountOverOriginalValue' => true,
			'counterofferValue' => $counterOffer,
			'urlSimilar' => $urlSimilar,
		];

		if($counterOffer < $lote->imptas_asigl0){

			#recuperamos el % por el cual esta dispuesto a recibir email, por ejemplo todas las ofertas por que esten por encima de un 10% menos del precio
			$owner = FgHces1::select('dtoc_cli')->getOwner()->where([
				['num_hces1',$lote->num_hces1],
				['lin_hces1',$lote->lin_hces1]
			])->first();

			//por defecto Si el valor de la contraoferta, llega al 90% del importe minimo, enviamos email al vendedor
			$percentMinOffer = 0.9;
			#si tenemos datos del concesionario calculamos el % que debe alcanzar
			if(!empty($owner) && !empty($owner->dtoc_cli) &&  $owner->dtoc_cli > 0){
				$percentMinOffer = (100 - $owner->dtoc_cli) /100;
			}

			//Si el valor de la contraoferta, llega al 90% del importe minimo, debemos saverlo de alguna manera
			$amountOverOriginalValue = $counterOffer / $lote->imptas_asigl0;

			$isAmountOverOriginalValue = $amountOverOriginalValue >= $percentMinOffer;

			$response['message'] = "Gracias por tu Oferta. Por favor, danos tus datos para tramitarla.";
			$response['messageToCancel'] = '¿Seguro que no quieres tramitar tu Oferta?';
			$response['counterofferType'] = FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA;
			$response['amountOverOriginalValue'] = $isAmountOverOriginalValue;
		}

		return response()->json($response);
	}

	public function contraOfertar()
	{
		$cod_sub = request("cod_sub");
		$ref_asigl0 = request("ref");
		$cod_licit = request('cod_licit');
		$counterOffer = request("imp", false);
		$mailController = new MailController();
		$cod_user = session('user.cod');
		$theme = config('app.theme');

		//usuario no existe o no ha iniciado sesion
		if(!$this->checkUser($cod_user)){
			return $this->error_puja(trans("$theme-app.subastas.mustLogin"), NULL, FALSE);
		}

		$subasta = new subasta();
        $subasta->cod = $cod_sub;
		$subasta->lote = $ref_asigl0;
        $lote_temp = $subasta->getLote();

		//el lote no existe o no tiene precio minimo para realizar contraoferta
        if(count($lote_temp) == 0 || empty($counterOffer)){
            return $this->error_puja(trans("$theme-app.msg_error.counteroffer"), NULL, FALSE);
        }

        $lote = head($lote_temp);

		// si no es de venta directa o esta cerrado o no tiene precio minimo para realizar contraoferta
		$notCounterOfferPermision = ($lote->imptas_asigl0 ?? 0) <= 0;
		if($lote->tipo_sub != FgSub::TIPO_SUB_VENTA_DIRECTA || $lote->cerrado_asigl0 == 'S' || $notCounterOfferPermision){
            return $this->error_puja(trans("$theme-app.msg_error.counteroffer"), NULL, FALSE);
        }

		//Con último desarrollo en el que ya no esta el botón comprar esto deja de tener sentido
		//El valor de la contraoferta es superior al precio de salida
		/* if($counterOffer >= $lote->impsalhces_asigl0){
			return $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.counteroffer_toobig'), NULL, FALSE);
		} */

		$subasta->licit = $cod_licit;
		$subasta->imp = $counterOffer;
		$subasta->ref = $ref_asigl0;

		//El valor de la contraoferta no llega al minimo para ser aceptada
		if($counterOffer < $lote->imptas_asigl0){

			$result = $subasta->addPujaAux(FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA);
			if ($result['status'] != 'success'){
				return $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.'.$result['msg']), NULL, FALSE);
			}

			//$mailController->sendCounterofferRejected($cod_licit, $cod_sub, $ref_asigl0, $counterOffer);
			#recuperamos el % por el cual esta dispuesto a recibir email, por ejemplo todas las ofertas por que esten por encima de un 10% menos del precio
			$owner = FgHces1::select('dtoc_cli')->getOwner()->where([
				['num_hces1',$lote->num_hces1],
				['lin_hces1',$lote->lin_hces1]
			])->first();

			$result['amountOver'] = false;

			//por defecto Si el valor de la contraoferta, llega al 90% del importe minimo, enviamos email al vendedor
			$percentMinOffer = 0.9;
			#si tenemos datos del concesionario calculamos el % que debe alcanzar
			if(!empty($owner) && !empty($owner->dtoc_cli) &&  $owner->dtoc_cli > 0){
				$percentMinOffer = (100 - $owner->dtoc_cli) /100;
			}

			//Si el valor de la contraoferta, llega al 90% del importe minimo, enviamos email al vendedor
			$amountOverOriginalValue = $counterOffer / $lote->imptas_asigl0;
			if($amountOverOriginalValue >= $percentMinOffer){

				$mailController->sendCounterofferToOwner($cod_user, $cod_sub, $ref_asigl0, $counterOffer, $lote->num_hces1, $lote->lin_hces1, false, $result['bid']->lin_asigl1, $result['bid']->licit_asigl1, $lote->imptas_asigl0);

				$mailController->sendCounterofferAmountOverToLicit($cod_user, $cod_sub, $ref_asigl0, $counterOffer);

				$result['amountOver'] = true;
				$result['msg'] = 'Tu Oferta está cerca del precio solicitado por el Vendedor y la está considerando. Para evitar que se venda a otro comprador, asegura su compra al precio indicado o incrementa tu oferta.';

			}

			$mailController->sendCounterofferToAdmin($cod_user, $cod_sub, $ref_asigl0, $counterOffer, $lote->imptas_asigl0);

			return $result;
		}

		//El valor es correcto
		$result = $subasta->addPujaAux(FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA);

		if ($result['status'] != 'success'){
			return $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.'.$result['msg']), NULL, FALSE);
		}

		$link = (new CarlandiaPayController())->getPayLink($cod_sub, $ref_asigl0, $cod_licit, $result['bid']->lin_asigl1, 'A');

		//mail a usuario confirmando el envio de la contraoferta

		$email = new EmailLib('COUNTEROFFER_LICIT');
		if(!empty($email->email)){
			$email->setUserByLicit($cod_sub, $cod_licit, true);
			$email->setLot($cod_sub, $ref_asigl0);
			$email->setAtribute('PRICE_COUNTEROFFER', Tools::moneyFormat($counterOffer, trans(\Config::get('app.theme').'-app.subastas.euros'),2));
			$carlandiaCommission = \Config::get("app.carlandiaCommission");
			$impreserva = $counterOffer - ($counterOffer / (1 + $carlandiaCommission));
			$email->setAtribute("IMPORTE_RESERVA", \Tools::moneyFormat($impreserva,trans(\Config::get('app.theme').'-app.subastas.euros'),2));
			$email->setAtribute('PAY_LINK', $link);

			if(config('app.emailOwnerInformation', 0)){
				$email->setPropInfo($cod_sub, $ref_asigl0);
			}
			$email->send_email();

		}

		#enviar a vista con datos de la aceptación de contraoferta
		#recuperar propiedades del email para rellenar la página
		$data["typePuja"] ="C"; //Código contraoferta
		$data["prop_name"] = $email->getAtribute("PROP_NAME");
		$data["lot_descweb"] = $email->getAtribute("LOT_DESCWEB");
		$data["lot_ref"] = $email->getAtribute("LOT_REF");
		$data["price_counteroffer"] = $email->getAtribute("PRICE_COUNTEROFFER");
		$data["importe_reserva"] = $email->getAtribute("IMPORTE_RESERVA");
		$data["prop_contact"] = $email->getAtribute("PROP_CONTACT");
		$data["prop_name"] = $email->getAtribute("PROP_NAME");
		$data["prop_tel"] = $email->getAtribute("PROP_TEL");
		$data["prop_email"] = $email->getAtribute("PROP_EMAIL");
		$data["pay_link"] = $email->getAtribute("PAY_LINK");
		$result["msg"] =   View::make('front::pages.pagarVehiculo', $data)->render();

		$result["payLink"] = $link;
		$result["messageToCancel"] = "¿Seguro que no quieres depositar la señal para reservar tu vehículo?";
		//mail a propietario con copia a admin

		//como se envía email de pago de señal, ya no hace falta enviar email de contraoferta acepatada ni a vendedor ni a admin
		//$mailController->sendCounterofferToOwner($cod_user, $cod_sub, $ref_asigl0, $counterOffer, $lote->num_hces1, $lote->lin_hces1, true);


		#mostramos la vista que envia al pago, coger texto de COUNTEROFFER_LICIT

		return $result;
	}

	private function checkUser($cod_user)
	{
		if(empty($cod_user)){
			return false;
		}

		$user = new User();
		$user->cod_cli = $cod_user;
		$exist_user = $user->getUserByCodCli();

		if(empty($exist_user)){
            return false;
        }

		return true;
	}

	/**
	 * Comprar VD, Comprar Online de Carlandia
	 */
	public function comprarAux()
	{
		$user = new User();
        $cod_sub = request('cod_sub');
        $ref = request('ref');
        $cod_user = session('user.cod');
		$typePuja = request('type-puja');
		$theme = config('app.theme');

		//usuario no existe o no ha iniciado sesion
		if(!$this->checkUser($cod_user)){
			return $this->error_puja(trans(\Config::get('app.theme').'-app.subastas.mustLogin'), NULL, FALSE);
		}

		$subasta = new subasta();
        $subasta->cod = $cod_sub;
        $subasta->cli_licit = $cod_user;

		$checklicit = $subasta->checkLicitador();

		//si no ha devuelto ningun codigo de licitador
		if (count($checklicit) == 0){
			return $this->error_puja(trans("$theme-app.msg_error.buying"), NULL, FALSE);
		}

		$licit = head($checklicit)->cod_licit;

		$subasta->lote = $ref;
        $l = $subasta->getLote();

        //el lote no existe
        if(count($l) == 0){
            return $this->error_puja(trans("$theme-app.msg_error.buying"), NULL, FALSE);
        }

        $lote = head($l);

        //La subastas de tipo V tienen que tener el lote abierto y no hace falta que tengan el campo compra_asigl0 como S,
		//en cambio las otras el lote debe estar cerrado y los deben tener el campo comprar a S
		$cerrado = $lote->cerrado_asigl0 != 'N';
		$loteRetirado = $lote->retirado_asigl0 != 'N';
		$subataNoActiva = !in_array($lote->subc_sub, [FgSub::SUBC_SUB_ACTIVO, FgSub::SUBC_SUB_ADMINISITRADOR]);

        if( $cerrado || $loteRetirado || $subataNoActiva ){
            return $this->error_puja(trans("$theme-app.msg_error.buying"), NULL, FALSE);
        }

		$importe = $lote->impsalhces_asigl0;
		if($lote->tipo_sub == FgSub::TIPO_SUB_ONLINE){
			$importe = $lote->imptash_asigl0;
		}

		//En VD el importe es el de salida o el de reserva si este último es más alto
		elseif(!empty($lote->impres_asigl0) && $lote->impres_asigl0 > $lote->impsalhces_asigl0){
			$importe =  $lote->impres_asigl0;
		}

		//datos para hacer la puja
        $subasta->licit = $licit;
        $subasta->imp = $importe;
        $subasta->ref = $ref;

		$result = $subasta->addPujaAux($typePuja);

		if ($result['status'] == 'error'){
			return $this->error_puja($result['msg'], NULL, FALSE);
		}

		$link = (new CarlandiaPayController())->getPayLink($cod_sub, $ref, $licit, $result['bid']->lin_asigl1, 'A');

		//Mail a usuario y a admin
		$email = new EmailLib('PURCHASE_INTENT');
		if(!empty($email->email)){
			$email->setUserByLicit($cod_sub, $licit, true);
			$email->setLot($cod_sub, $ref);
			$email->setPrice(\Tools::moneyFormat($importe,trans(\Config::get('app.theme').'-app.subastas.euros'),2));
			$carlandiaCommission = \Config::get("app.carlandiaCommission");
			$impreserva = $importe- ($importe / (1 + $carlandiaCommission));

			$email->setAtribute("IMPORTE_RESERVA", \Tools::moneyFormat(round($impreserva, 2),trans(\Config::get('app.theme').'-app.subastas.euros'),2));

			$email->setPropInfo($cod_sub, $ref);
			$email->setAtribute('PAY_LINK', $link);
			$email->send_email();
		}
		#enviar a vista con datos de la aceptación PURCHASE_INTENT
		#recuperar propiedades del email para rellenar la página getAtribute($atribute)
		$data["typePuja"] = $typePuja;
		$data["prop_name"] = $email->getAtribute("PROP_NAME");
		$data["lot_descweb"] = $email->getAtribute("LOT_DESCWEB");
		$data["lot_ref"] = $email->getAtribute("LOT_REF");
		$data["price"] = $email->getAtribute("PRICE");
		$data["importe_reserva"] = $email->getAtribute("IMPORTE_RESERVA");
		$data["prop_contact"] = $email->getAtribute("PROP_CONTACT");
		$data["prop_name"] = $email->getAtribute("PROP_NAME");
		$data["prop_tel"] = $email->getAtribute("PROP_TEL");
		$data["prop_email"] = $email->getAtribute("PROP_EMAIL");
		$data["pay_link"] = $email->getAtribute("PAY_LINK");
		$result["msg"] =   View::make('front::pages.pagarVehiculo', $data)->render();

		$result["messageToCancel"] = "¿Seguro que no quieres depositar la señal para reservar tu vehículo?";
		$result["payLink"] = $link;

		return $result;

	}
	public function makeOffer(){
        $user = new User();
        $mail = new MailController();
        //$subasta = new subasta();
        $cod_sub            = request('cod_sub');
        $ref            = request('ref');
        $imp           = request('imp');
        $cod_licit = request('cod_licit');
        $cod_user = request('user.cod');
        $gestor = request('user.admin');



        if (empty($cod_user)){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.subastas.mustLogin'),NULL, FALSE);
            return $res;

        }

        $user->cod_cli = $cod_user;
        $exist_user = $user->getUserByCodCli();
        if(empty($exist_user)){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.subastas.mustLogin'),NULL, FALSE);
            return $res;
        }

        $subasta = new subasta();
        $subasta->cod = $cod_sub;
        $subasta->cli_licit = $cod_user;
        //si es gestor puede haber indicado un id de licitador para realizar la compra en su nombre
        if($gestor && !empty($cod_licit) ){
            $licit = $cod_licit;
        }
        //si no buscamos el id de licitaodr dle usuario.
        else{

            $checklicit = $subasta->checkLicitador();

            //si no ha devuelto ningun codigo de licitador
            if (count($checklicit) == 0){
                $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.makeOffer'),NULL, FALSE);
                return $res;
            }
            $licit = head($checklicit)->cod_licit;
        }


        $subasta->lote = $ref;
        $l = $subasta->getLote();

        //el lote no existe
        if(count($l)== 0 ){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.makeOffer'),NULL, FALSE);
            return $res;
        }
        $lote = head($l);

        //comprobamos que se puede realizar una oferta por el lote

        if( ($lote->tipo_sub=='M'  && $lote->cerrado_asigl0!='N'  )   || $lote->retirado_asigl0!='N'  || ( $lote->subc_sub!='S' && $lote->subc_sub!='C' && $lote->subc_sub!='A')  ){

            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.makeOffer'),NULL, FALSE);
            return $res;
        }

		$subasta->ref =  $ref;
		$subasta->page      =1;
		$subasta->itemsPerPage = 1;
		$pujas=	$subasta->getpujas();
		\Log::info(print_r($pujas,true));

		if(count($pujas) >0){
			$maxPuja = head($pujas);

			if($maxPuja->imp_asigl1 >= $imp){
				$subasta->page      ="all";
				$allPujas =$subasta->getpujas();
				#es info y no error porque se tiene que recargar la página para ver las pujas actuales
				$res = array(
					'status' => 'info',
					'msg_1' => trans(\Config::get('app.theme').'-app.msg_error.makeOfferLower'),
					'pujas' =>$allPujas
				);
				\Log::info("lower");
				return $res;
			}
		}





        //datos para hacer la puja
        $subasta->licit = $licit;
        $subasta->type_bid = 'W';
        $subasta->imp = $imp;
        $subasta->ref = $ref;




        $result = $subasta->addPuja();
		if ($result['status'] == 'success'){

			$importeLote =  $lote->impsalhces_asigl0;
			if(!empty($lote->impres_asigl0) && $lote->impres_asigl0 >  $lote->impsalhces_asigl0 ){
				$importeLote =  $lote->impres_asigl0;
			}
			#si se queda
			if($imp>= $importeLote){

				$subasta->cerrarLote();

				if(Config::get('app.WebServiceCloseLot')){

					$theme  = Config::get('app.theme');
					$rutaCloseLotcontroller = "App\Http\Controllers\\externalws\\$theme\CloseLotController";

					$closeLotController = new $rutaCloseLotcontroller();

					$closeLotController->createCloseLot($cod_sub,$ref);
				}
				if (Config::get('app.enable_email_buy_user')){
					$mail->sendEmailCerradoGeneric(Config::get('app.emp'),$cod_sub,$ref);
				}

					# Opciones de envio de email
					if(!empty(Config::get('app.accounting_email_admin'))){
						$admin_email = Config::get('app.accounting_email_admin');
					}else{
					$admin_email = Config::get('app.admin_email');
					}

						$email = new EmailLib('LOT_SOLD_ADMIN');
						if(!empty($email->email)){
							$email->setUserByLicit($cod_sub, $licit,false);
							$email->setAuction_code($cod_sub);
							$email->setLot_ref($ref);
							$email->setPrice($imp);
							$email->setTo($admin_email);
							$email->send_email();
						}

				$res = array(
						'status' => 'success',
						'msg' => trans(\Config::get('app.theme').'-app.msg_success.makeOfferBuyLot',['lot' => $lote->descweb_hces1]),
						'location' => '/'.\Config::get("app.locale")."/user/panel/allotments"


					);
				return $res ;
				}else{
					$subasta->page      ="all";
					$allPujas =$subasta->getpujas();
					#es info y no error porque se tiene que recargar la página
					$res = array(
						'status' => 'info',
						'msg_1' => trans(\Config::get('app.theme').'-app.msg_error.makeOfferLose',['lot' => $lote->descweb_hces1]),
						'pujas' => $allPujas


					);
					return $res;
				}
        }else{
            $res = $this->error_puja($result['msg'],NULL, FALSE);
            return $res ;
        }



    }
	public function comprar(){

        $cod_sub            = request('cod_sub');
        $ref            = request('ref');
        $cod_licit = request('cod_licit');
        $cod_user = Session::get('user.cod');
        $gestor = Session::get('user.admin');

		return $this->comprarLote($cod_sub, $ref, $cod_user, $cod_licit, $gestor );
	}




	public function comprarLote($cod_sub, $ref, $cod_user, $cod_licit = null, $gestor = false)
	{
		$user = new User();
		$mail = new MailController();

		if (empty($cod_user)) {
			$res = $this->error_puja(trans(Config::get('app.theme') . '-app.subastas.mustLogin'), NULL, FALSE);
			return $res;
		}

		$user->cod_cli = $cod_user;
		$exist_user = $user->getUserByCodCli();
		if (empty($exist_user)) {
			$res = $this->error_puja(trans(Config::get('app.theme') . '-app.subastas.mustLogin'), NULL, FALSE);
			return $res;
		}
		if ($exist_user[0]->blockpuj_cli == "S") {
			$res = $this->error_puja(trans(Config::get('app.theme') . '-app.msg_error.usuario_pendiente_revision'), NULL, False);
			return $res;
		}

		$subasta = new subasta();
		$subasta->cod = $cod_sub;
		$subasta->cli_licit = $cod_user;
		//si es gestor puede haber indicado un id de licitador para realizar la compra en su nombre
		if ($gestor && !empty($cod_licit)) {
			$licit = $cod_licit;
		}
		//si no buscamos el id de licitaodr dle usuario.
		else {

			$checklicit = $subasta->checkLicitador();

			//si no ha devuelto ningun codigo de licitador
			if (count($checklicit) == 0) {
				$res = $this->error_puja(trans(Config::get('app.theme') . '-app.msg_error.buying'), NULL, FALSE);
				return $res;
			}
			$licit = head($checklicit)->cod_licit;
		}

		$subasta->lote = $ref;
		$l = $subasta->getLote();

		//el lote no existe
		if (count($l) == 0) {
			$res = $this->error_puja(trans(Config::get('app.theme') . '-app.msg_error.buying'), NULL, FALSE);
			return $res;
		}
		$lote = head($l);
		//comprobamos que el lote se puede comprar, la subastas de tipo V tienen que tener el lote abierto y no hace falta que tengan el campo compra_asigl0 como S, en cambio las otras el lote debe estar cerrado y los deben tener el campo comprar a S

		if (($lote->tipo_sub == 'V'  && $lote->cerrado_asigl0 != 'N') || ($lote->tipo_sub != 'V' && $lote->cerrado_asigl0 != 'S')  || ($lote->tipo_sub != 'V' && $lote->compra_asigl0 != 'S') || $lote->retirado_asigl0 != 'N'  || $lote->lic_hces1 != 'N' || ($lote->subc_sub != 'S' && $lote->subc_sub != 'C' && $lote->subc_sub != 'A' && !(Config::get('app.buy_historic') && $lote->subc_sub == 'H'))) {

			$res = $this->error_puja(trans(Config::get('app.theme') . '-app.msg_error.buying'), NULL, FALSE);
			return $res;
		}

		$importe =  $lote->impsalhces_asigl0;
		if (!empty($lote->impres_asigl0) && $lote->impres_asigl0 >  $lote->impsalhces_asigl0) {
			$importe =  $lote->impres_asigl0;
		}

		//comprobar si tenemos credito disponible en la sesión? para realizar la compra
		if(Config::get('app.use_credit', false)) {
			$hasAvailableCredit = Subasta::allowBidCredit($cod_sub, $lote->reference, $licit, $importe);
			if(!$hasAvailableCredit){
				return $this->error_puja(trans(Config::get('app.theme') . '-app.subastas.not_have_credit'), null, false);
			}
		}

		//datos para hacer la puja
		$subasta->licit = $licit;
		$subasta->type_bid = 'W';
		$subasta->imp = $importe;
		$subasta->ref = $ref;

		//debe ir a true para que no compruebe que este cerrado
		$result = $subasta->addPuja(TRUE);
		//no se envia de momento para compra directa
		//$this->email_bid_confirmed($subasta,$result,'compra');
		if ($result['status'] == 'success') {

			$subasta->cerrarLote();

			if (Config::get('app.WebServiceCloseLot')) {

				$theme  = Config::get('app.theme');
				$rutaCloseLotcontroller = "App\Http\Controllers\\externalws\\$theme\CloseLotController";

				$closeLotController = new $rutaCloseLotcontroller();

				$closeLotController->createCloseLot($cod_sub, $ref);
			}
			if (Config::get('app.enable_email_buy_user')) {
				$mail->sendEmailCerradoGeneric(Config::get('app.emp'), $cod_sub, $ref);
			}

			# Opciones de envio de email
			if (!empty(Config::get('app.accounting_email_admin'))) {
				$admin_email = Config::get('app.accounting_email_admin');
			} else {
				$admin_email = Config::get('app.admin_email');
			}

			$email = new EmailLib('LOT_SOLD_ADMIN');
			if (!empty($email->email)) {
				$email->setUserByLicit($cod_sub, $licit, false);
				$email->setAuction_code($cod_sub);
				$email->setLot_ref($ref);
				$email->setPrice($importe);
				$email->setTo($admin_email);
				$email->send_email();
			}

			$res = array(
				'status' => 'success',
				'msg' => trans(Config::get('app.theme') . '-app.msg_success.buying_lot', ['lot' => $subasta->ref]),
				'ref' => $subasta->ref,
				'imp' => $subasta->imp

			);
			return $res;
		} else {
			$res = $this->error_puja($result['msg'], NULL, FALSE);
			return $res;
		}
	}


    public function ordenLicitacion ()
    {

        //$subasta = new subasta();
        $cod_sub        = request('cod_sub');
        $ref            = request('ref');
        $imp            = request('imp');
		$cod_user = Session::get('user.cod');
		$tel1 = request('tel1');
		$tel2 = request('tel2');
		$ortherphone = filter_var(request('ortherphone'), FILTER_VALIDATE_BOOLEAN);
		return $this->crearOrdenLicitacion($cod_sub, $ref,  $imp, $cod_user, $tel1, $tel2, $ortherphone);
	}

	public function crearOrdenLicitacion ($cod_sub, $ref,  $imp, $cod_user, $tel1 = null, $tel2 = null, $ortherphone = null)
    {
		\Log::info("Orden en subasta $cod_sub lote $ref importe: $imp, por el usuario $cod_user ");
		if($ortherphone){
			\Log::info("La orden es telefónica, telefonos: $tel1 , $tel2 ");
		}

		$importeOrdenes = 0;



        if (empty($cod_user)){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.subastas.mustLogin'),NULL, FALSE);
            return $res;

        }

        $aux_user = DB::table("FXCLI")->addSelect("ries_cli,blockpuj_cli")->where("GEMP_CLI",\Config::get("app.gemp"))->where("COD_CLI",$cod_user)->where("BAJA_TMP_CLI","N")->first();
		if(empty($aux_user)){
			$res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.activacion_casa_subastas'), NULL, False);
			return $res;
		}elseif (   $aux_user->blockpuj_cli == "S") {
			$res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.usuario_pendiente_revision'), NULL, False);
			return $res;
		}

        if ($aux_user->ries_cli > 0) {

			if(Config::get('app.max_orders_ries_cli', false)){
				$importeOrdenes = FgOrlic::getTotalOrdersInAuction($cod_sub, $ref, $cod_user);
			}

            $supera_riesgo = (($imp + $importeOrdenes) < $aux_user->ries_cli);
            if (!$supera_riesgo) {
                $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.imp_max_licitador'),NULL, FALSE);
                return $res;
            }
        }

        //comprobamos que no este vacio y que solo contenga numeros.
        if (empty($imp) || !ctype_digit($imp)){
           $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.int_value'),NULL, FALSE);
            return $res;
        }
        $subasta = new subasta();
        $subasta->cod = $cod_sub;
        $subasta->cli_licit = $cod_user;
        $subasta->lote = $ref;
        $subasta->ref = $ref;
        $subasta->imp = $imp;
		$subasta->type_bid = "O";
        $subasta->tel1 = $tel1;
		$subasta->tel2 = $tel2;
		#la variable viene de js y no se puede enviar un booleano, lo envia como texto
		if ( $ortherphone == "true"  || !empty($tel1) || !empty($tel2) ) {

			if(\Config::get('app.diferenciarOrdenTelefonicaWeb')){
				$subasta->type_bid = "X";
			}else{
				$subasta->type_bid = "T";
			}

		}

        $checklicit = $subasta->checkLicitador();
        //si no ha devuelto ningun codigo de licitador
        if (count($checklicit) ==0){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.buying'),NULL, FALSE);
            return $res;
        }
        $licit = head($checklicit)->cod_licit;
        $subasta->licit = $licit;

        $l = $subasta->getLote();
        //el lote no existe
        if(count($l)== 0 ){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.buying'),NULL, FALSE);
            return $res;
        }
        $lote = head($l);


        //comprobamos que se pueda hacer una orden de licitación en el lote,
        //que el lote no este cerrado && no esté facturado && que haya empezado el periodo de ordenes de licitacion && que no haya acabado el periodod de ordenes de licitación && que la subasta esté activa

        if($lote->cerrado_asigl0!='N'   ||  $lote->fac_hces1 == 'R' || $lote->fac_hces1 == 'D' || time() < strtotime($lote->orders_start) || time() > strtotime($lote->orders_end)  || ($lote->subc_sub != 'S' && $lote->subc_sub != 'A') ){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.generic'),NULL, FALSE);
            return $res;
        }

        if($imp < $lote->impsalhces_asigl0 ){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.small_order'),NULL, FALSE);
            return $res;
        }

		if($lote->impsalhces_asigl0 == 0){
			#Cogemos el primer escalado
			$escalas = $subasta->allScales();
			$primerEscalado = head($escalas)->scale;
			#si el lote no tiene precio la puja debe superar el primer escalado
			if($imp < $primerEscalado){
				$res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.small_order_zero',["escalado" =>$primerEscalado]),NULL, FALSE);
				return $res;
			}

		}


        $ordenes_licit = $subasta->getOrdenes("AND licitadores.COD_LICIT = ".intval($subasta->licit));

        if (count($ordenes_licit) > 0 && $ordenes_licit[0]->himp_orlic >= $imp && $lote->opcioncar_sub == 'N' && !config('app.edit_orders', 0))
        {
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.order_lower'),NULL, FALSE);
            return $res;
        }

        $subasta->impsal = $lote->impsalhces_asigl0;
        $escalado_libre = \Config::get('app.escalado_libre');

        if ($subasta->imp != $subasta->impsal && $subasta->validateScale($subasta->impsal,$subasta->imp ) == false &&   ( empty($escalado_libre) || !$escalado_libre )  ){
            $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.bid_scaling'),NULL, FALSE);
            return $res;
        }
        //cargamos todas las ordenes para comparar luego la nueva del usuario
        $ordenes = $subasta->getOrdenes();

        //si es una subasta de carrito y el usuario ya tenia una orden, guardamos el log de la orden substituida
		//si tiene el config también guardamos en log (servihabitat)
        if(!empty($lote->opcioncar_sub) && $lote->opcioncar_sub == 'S' || config('app.edit_orders', 0)){
            foreach($ordenes as $orden){
                if($orden->cod_licit == $subasta->licit ){
                    $subasta->cancelarLog($orden->tipop_orlic,$subasta->licit,$orden->himp_orlic, $orden->fec_orlic, Web_Cancel_Log::ACCION_MODIFICAR);
                    break;
                }
            }
        }

        $ord = $subasta->addOrden();

        if ($ord['status'] == 'success')
        {

			#si la casa de subastas tiene webService de cliente
			if(Config::get('app.WebServiceOrder')){

				$theme  = Config::get('app.theme');
				$rutaOrdercontroller = "App\Http\Controllers\\externalws\\$theme\OrderController";

				$orderController = new $rutaOrdercontroller();

				$resOrden = $orderController->createOrder($cod_user, $cod_sub, $ref, $imp);
				#si el webservice puede rechazar la orden, y la orden ha sido rechazada
				if(Config::get('app.WebServiceRejectOrder') && !$resOrden){
					$res = array(
						'status' => 'error',
						'msg_1' => trans(\Config::get('app.theme').'-app.msg_error.inserting_bid_order') ,
						'cod_licit_actual' => $subasta->licit
					);
					return $res;
				}
			}

            //cogemos los datos de nuevo para saber si tiene precio abierto, es necesario coger los datos con la nueva orden
            $l = $subasta->getLote();
			$lote = head($subasta->getAllLotesInfo($l,false,false,false));

			if($lote->ministerio_hces1 == 'S' ){
				$email = new EmailLib('CONFIRMATION_BID_MINISTRY');
			}
			if(empty($email) || empty($email->email)){
				/*Mail de confirmación de puja por escrito (orden)*/
				if($subasta->type_bid == "T" || $subasta->type_bid == "X" ){
					$email = new EmailLib('CONFIRMATION_PHONE_BID');
					#SI NO TIENE EMAIL PERSONALIZADO PARA LAS TELEFÓNICAS, PONEMOS LA NORMAL
					if(empty($email->email)){
						$email = new EmailLib('CONFIRMATION_BID');
					}
				}else {
					$email = new EmailLib('CONFIRMATION_BID');
				}
			}

			if(!empty($email->email)){
				$email->setUserByCod($cod_user, true);
				$email->setLot_img(Config::get('app.url').'/img/load/lote_small/'.Config::get('app.emp').'-'.$lote->num_hces1.'-'.$lote->lin_hces1.'.jpg');
				$email->setSession_name($lote->des_sub);
				$email->setLot_ref($subasta->lote);
				$email->setPrice(\Tools::moneyFormat($lote->impsalhces_asigl0));
				$email->setLot_title($lote->titulo_hces1);
				$email->setLot_description($lote->desc_hces1);
				$email->setBid(\Tools::moneyFormat($subasta->imp));
				/**Era para carlandia y creo que no es necesario en este caso */
/* 				$email->setLot_link(\Tools::url_lot($cod_sub, $lote->id_auc_sessions, $lote->des_sub, $lote->ref_asigl0, $lote->num_hces1,$lote->webfriend_hces1,$lote->titulo_hces1));
				$email->setCloseDate($lote->close_at);
				$email->setAtribute('LOT_DESCWEB', $lote->descweb_hces1); */

				if(config('app.notifyIfYouNotWinner', false)){

					$text = trans(\Config::get('app.theme').'-app.emails.saved_success_orden');
					if (count($ordenes) > 0 && $ordenes[0]->himp_orlic >= $imp) {
						$text = trans_choice(\Config::get('app.theme').'-app.msg_error.your_order_lose', 1, ['imp' => $imp]);
					}

					$email->setAtribute('TEXT', $text);
				}

				if(Config::get('app.email_order_to_admin')){

					//Servihabitat, necesita que en las subastas presenciales llegue el email a otro buzon
					if($lote->tipo_sub == 'W' && config('app.alternative_admin_email', false)){
						$email->setBcc(config('app.alternative_admin_email'));
					}
					else{
						$email->setBcc(Config::get('app.admin_email'));
					}

				}
				$email->send_email();
			}

           //miramos si su orden no supera las anteriores, no hace falta comprobar si la orden máxima es suya por que para llegar aquí ha debido superar su orden máxima
            if (count($ordenes) > 0 && $ordenes[0]->himp_orlic >= $imp )
            {
                $winner = false;
                if(Config::get('app.notice_over_bid')){

                    $imp_actual = $subasta->sobre_puja_orden($lote->impsalhces_asigl0, $ordenes[0]->himp_orlic, $imp);
                   //notificamos al usuario que no es el ganador, su orden esta por debajo de la mayor, o en caso de igualarla pierde por que la ha hecho despues
                     $res = array(
                        'status' => 'success',
                        'msg' => trans_choice(\Config::get('app.theme').'-app.msg_error.your_order_lose', 1, ['imp' => $imp_actual]),
                        'imp' => $imp,
                        'imp_actual' => $imp_actual,
                        'winner' =>$winner,
                        'open_price' => $lote->open_price
                     );

					 if(config('app.notice_over_bid_email', false)){
						$this->sendEmailSobrepuja($subasta->cod, $subasta->licit, $subasta->ref, "orden");
					 }


                    return $res;
                }
            }else{
                $winner = true;
                $this->sendEmailSobrepuja($subasta->cod, $subasta->licit, $subasta->ref, "orden");
            }



           $res = array(
                    'status' => 'success',
                    'msg' => trans(\Config::get('app.theme').'-app.msg_success.correct_bid'),
                    'imp' => $imp,
                    'imp_actual' => $imp,
                    'winner' =>$winner,
                    'open_price' => $lote->open_price

                );

            return $res ;
        }elseif ($ord['status'] == 'error')
                                {
                                    $res = array(
                                       'status' => 'error',
                                       'msg_1' => $ord['msg'],
                                       'cod_licit_actual' => $subasta->licit
                                   );

                                   if (!empty($is_gestor))
                                   {
                                       $res['is_gestor'] = TRUE;
                                   }

                                   return $res;
                                }
        else
        {
             $res = $this->error_puja(trans(\Config::get('app.theme').'-app.msg_error.generic'),NULL, FALSE);
            return $res;
        }

    }

	#funcion de hacer puja pero en version subasta inversa
	public function executeActionInversa($subasta,  $lote, $is_gestor){
		#guardamos los valores originales de la puja
		$impOriginalFormatted = \Tools::moneyFormat($subasta->imp);
		$impOriginal = $subasta->imp;
		$licitOriginal = $subasta->licit;
		$typeBidOriginal = $subasta->type_bid;
		\Log::info("Subasta inversa LICIT: ". $subasta->licit." SUBASTA: ". $subasta->cod." lote: ".$subasta->ref ." importe_validate " .$subasta->impsal." subasta.imp ".  $subasta->imp);

		$pujas  = $subasta->getPujasInversas();

		#La puja debe ser menor o igual que el importe de salida
		if($subasta->imp > $subasta->impsal   )
        {
			\Log::info("greater_bid_reverse importe introducido" . $subasta->imp." > ".$subasta->impsal . " importe salida" );
            $res = $this->error_puja('greater_bid_inverse', $subasta->licit, $is_gestor);
            return $res;
        }

		#Las pujas deben ser menores que las actuales
		if(count($pujas)>0 && $subasta->imp >= $pujas[0]->imp_asigl1){
			\Log::info("small_bid_inverse numero pujas ".count($pujas). " importe introducido". $subasta->imp ." > ".$subasta->impsal );
			$res = $this->error_puja('small_bid_inverse', $subasta->licit, $is_gestor);
            return $res;
		}


		# si el que puja ya es el ganador actual
		if(count($pujas)>0 && $subasta->licit == $pujas[0]->cod_licit){

			#comprobamos que no sea una orden, si es ese el caso la guardamos
			if( $subasta->imp  <  $pujas[0]->imp_asigl1){
				$subasta->addOrden();
				$res = array(
					'status'            => 'success',
					'msg_2'             => 'add_bidding_order',
					'cod_licit_actual'  => $subasta->licit,
					'can_do'            => "orden",
					'himp_formatted'    => \Tools::moneyFormat($subasta->imp), //resultado para ficha subasta normal
					'sobreorden'    => true,
					'imp_original_formatted' => $impOriginalFormatted,
					'imp_original'      => $impOriginal,
					  'imp'               =>  $impOriginal
				);

				#es posible que haya precio de reserva, si aun no se ha alcanzado debemos llevar la puja lo más proximo al precio de reserva
				if($lote->impres_asigl0 > 0 && $lote->impres_asigl0 < $pujas[0]->imp_asigl1){

					$subasta->imp = max($lote->impres_asigl0,$subasta->imp);
					#Realizamos la puja
					$addPuja = $subasta->addPuja();
					$subasta->page      = 'all';
					$res['pujasAll']    = $subasta->getPujasInversas();
					$res['actual_bid']  = $subasta->imp ;

					$res['formatted_actual_bid'] = \Tools::moneyFormat($subasta->imp);

					$res['siguiente']  = $subasta->NextScaleInverseBid($subasta->impsal,$subasta->imp );

				}


				return $res;

			}
			//si no es una orden damos un error
			\Log::info("same_bidder numero pujas ".count($pujas). " licitador". $subasta->licit ." > ".$pujas[0]->cod_licit. "antiguo licitador ganador" );
			$res = $this->error_puja('same_bidder', $subasta->licit, $is_gestor);
            return $res;
		}

		$escalado_libre = \Config::get('app.escalado_libre', false);


		 //al pasar el error en el mensaje 2 no se le muestra al admin, todos los mensajes de msg1 los ve l admin
		if ($subasta->validateScaleInverse($subasta->impsal,$subasta->imp ) == false  &&  !$escalado_libre ){

			$res = array(
                    'status' => 'error',
                    'msg_2' => 'bid_scaling',
                    'cod_licit_actual'  => $subasta->licit,
					'no_interrupt_cd_time' => 'true',
					'is_gestor' => $is_gestor

                    );

			if($is_gestor){
				#queremos que el admin reciba el error de escalado solo si la puja la ha hecho el
				$res['msg_1']='bid_scaling';
			}
            return $res;
        }


		if(count($pujas)>0 ){
			$actualBid =  $pujas[0]->imp_asigl1;
		}else{
			$actualBid = $subasta->impsal;
		}


		#cargamos las ordenes previas
		$ordenes = $subasta->getOrdenesInversa();

		$previousOrder = null;
		if(count($ordenes) > 0 && $ordenes[0]->himp_orlic < $actualBid){
			$previousOrder =$ordenes[0];
		}
		$nextBid = $subasta->NextScaleInverseBid($subasta->impsal,$actualBid );


		#si no hay ordenes pendientes de ejecutarse
		if (empty($previousOrder)){
			#revisamos si el usuario ha realizado una orden, en ese caso realizamos la orden
			if( $subasta->imp < $nextBid){
					$subasta->addOrden();
					#si hay precio de reserva miramos si se puede hacer una puja por  el precio de reserva
					if($subasta->imp  <= $lote->impres_asigl0 ){
						$subasta->imp = $lote->impres_asigl0;
					}else{
						$subasta->imp =  $nextBid;
					}

			}
				#Realizamos la puja
				$addPuja = $subasta->addPuja();
		}
		#si hay ordenes pendientes de realizarse
		else{
			#revisamos si el usuario ha realizado una orden,
			if( $subasta->imp < $nextBid){
				#creamos la orden del usuario
				$subasta->addOrden();
			}
#Revisado
				#si la puja actual es la ganadora hacemos una puja con la orden de base de datos
				if ($subasta->imp < $previousOrder->himp_orlic){
					\Log::info("puja actual ganadora");
					$subasta->licit = $previousOrder->licit_orlic;

					$subasta->imp   = $previousOrder->himp_orlic;
					$subasta->type_bid = $previousOrder->tipop_orlic??  "W" ;

					$addPuja = $subasta->addPuja(FALSE, 'A');
					$nextBid =  $subasta->NextScaleInverseBid($subasta->impsal,$subasta->imp );

                    $this->email_bid_confirmed($subasta,$addPuja,'AUTOMATICA', $previousOrder->himp_orlic);

					#ponemos los valores originales de la puja
					$subasta->licit = $licitOriginal;
					$subasta->type_bid = $typeBidOriginal;

					#calculamos cual debería ser el siguiente importe
						#si aun no se ha llegado al precio de reserva

					if($lote->impres_asigl0 > 0 && $previousOrder->himp_orlic >  $lote->impres_asigl0  ){
						$subasta->imp = max($lote->impres_asigl0, $impOriginal );
					}else{

						#si no hay que tener en cuenta el importe de reserva, tendremos en cuenta que puede venir una orden o estar fuera de escalado
						$subasta->imp = max($impOriginal, $nextBid);
					}

					#Realizamos la puja
					$addPuja = $subasta->addPuja();

				}
#fin revisado
				#si la ganadora es la orden de base de datos
				else{
					#Realizamos la puja
					$addPuja = $subasta->addPuja();
					#Ponemos los datos de la orden de base de datos
					$subasta->licit = $previousOrder->licit_orlic;

					$subasta->type_bid = $previousOrder->tipop_orlic??  "W" ;



					$nextBid =  $subasta->NextScaleInverseBid($subasta->impsal,$subasta->imp );
					$subasta->imp =max($previousOrder->himp_orlic, $nextBid);


					#Realizamos la puja
					$addPuja = $subasta->addPuja(FALSE, 'A');
				}


		}






		  $actualBid = $subasta->imp;
		  $siguiente = $subasta->NextScaleInverseBid($subasta->impsal,$actualBid );

		  $formatted_actual_bid = \Tools::moneyFormat($actualBid);

		  $cod_licit_db = "";
		  $resultado = array();
		  array_push($resultado, 'addPuja');
		  $res = array(
				  'status'            => 'success',
				  'msg_1'             => 'higher_bid',
				  'msg_2'             => 'correct_bid',
				  'cod_licit_actual'  => $subasta->licit,
				  'cod_licit_db'      => $cod_licit_db,
				  'actual_bid'        => $actualBid,
				  'formatted_actual_bid' => $formatted_actual_bid,

				  'siguiente'         => $siguiente,
				  'test'              => $resultado,
				  'type_bid'          => $subasta->type_bid,
				  'winner'            => $subasta->licit,
				  'sobrepuja'         => true,
				  'imp_original_formatted' => $impOriginalFormatted,
				  'imp_original'      => $impOriginal,
				  'imp'               => $subasta->imp
			  );
		  # Consultamos todas las pujas para las ordenes, para poder mostrar la lista entera en la lista de pujas
		  $subasta->page      = 'all';
		  $res['pujasAll']    = $subasta->getPujasInversas();
		  # Fin listado de pujas

		  if (!empty($is_gestor)){
			  $res['is_gestor'] = TRUE;
		  }

		  return json_encode($res);





	}


	# Funcion de pujas y ordenes de licitacion en tiempo real mediante node
    public function action()
    {
		$codSub = request('params.cod_sub');
        $ref = request('params.ref');
        $licit = request('params.cod_licit');

		//si el usuario es administrador debemos mirar este código ya que elcod_licit se machaca
		$cod_original_licit = request('params.cod_original_licit');
		$imp = intval(request('params.imp'));
        $type_bid = request('params.type_bid');
        $can_do = request('params.can_do');
        $hash_user = request('params.hash');
        $tipo_puja_gestor = request('params.tipo_puja_gestor');

		\Log::info ("antiguo circuito action");
		return $this->executeAction($codSub, $ref, $licit, $cod_original_licit, $imp, $type_bid, $can_do, $hash_user,  $tipo_puja_gestor  );
	}

    # Funcion de pujas y ordenes de licitacion en tiempo real mediante node
    public function executeAction($codSub, $ref, $licit, $cod_original_licit, $imp, $type_bid, $can_do, $hash_user,  $tipo_puja_gestor  )
    {
		$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
		Log::debug('Inicio de la puja: ', ['codSub' => $codSub, 'ref' => $ref, 'licit' => $licit, 'imp' => $imp, 'time' => $now->format("Y-m-d H:i:s.u")]);

        $subasta = new subasta();
        $subasta->cod = $codSub;
        $subasta->ref =  $ref;
        $subasta->lote =  $ref ;
        $subasta->licit = $licit;
        //si el usuario es administrador debemos mirar este código ya que elcod_licit se machaca
		$this->cod_original_licit = $cod_original_licit;
		//08-04-2021: forzamos que el valor recibido no tenga decimales
		$subasta->imp = $imp;
        $subasta->type_bid = $type_bid;
        //2017-10-10 lo cojemos del lote directamente
        //$subasta->impsal         = Input::get('params.impsal');


        $is_gestor = false;
       //para evitar que falte esta variable la inicializamos a false indicando que se interrumpira la cuenta atras
        $no_interrupt_cd_time='false';
        $lote_tmp = $subasta->getLote();


        //comprobamos que exista un lote con esos datos
        if (count($lote_tmp) == 0){
            $res = $this->error_puja('generic', $subasta->licit, $is_gestor);
            return $res;
        }

        //cogemos el precio inicial del lote
        $lote = head($lote_tmp);
		#si el importe de salida es 0 debemos cojer el primer escalado como puja válida
		if($lote->impsalhces_asigl0== 0){
			$lote->impsalhces_asigl0 = head($subasta->AllScales())->scale;
		}

        //si el usuario es gestor puede pujar por otra gente y por eso el codigo que envia es diferente al original, pero puede que en algun lugar de la web aun no se envie el parametro $cod_original_licit por lo que usaremso el codigo de licitador ya que no viene de un gestor.
        if (empty($cod_original_licit)){
           $cod_original_licit = $subasta->licit;
        }



        //Comprobamos si el usuario es gestor
        $gestor = new User();
        $gestor->cod = $subasta->cod;
        $gestor->licit = $cod_original_licit;
        $g = $gestor->getUserByLicit();
        $user  = new User();
        $u = NULL;

        if(count($g) > 0){
            if ($g[0]->tipacceso_cliweb == 'S'){
                $is_gestor = true;

            }
            //si el código original es el mismo  que el licitador usamos el mismo usuario, es un usuario normal o un gestor que usa su propio código
            if ($subasta->licit == $cod_original_licit){
                $user  = $gestor;
                $u = $g;
            }
            //si no coincide el original con el licitador el usuario debe ser un gestor, si no es así se quedará a NULL el objeto $u
            elseif($is_gestor){
                $user  = new User();
                $user->cod   = $subasta->cod;
                $user->licit = $subasta->licit;
                $u = $user->getUserByLicit();
            }

            if (count($u)> 0 && isset($u[0]->blockpuj_cli) && $u[0]->blockpuj_cli == "S") {
                $res = $this->error_puja('usuario_pendiente_revision', $subasta->licit, $is_gestor);
                return $res;
			 }

			#Credito- SOLER- comprobación de puja , solo subasta presenciales,
			#no deja pujar si la puja más lo que tienes adjudicado supera tu credito
			if( (\Config::get('app.use_credit'))  && count($u) > 0 && $lote->tipo_sub == 'W' ){
				$puedePujar =Subasta::allowBidCredit($subasta->cod, $lote->reference, $subasta->licit,$subasta->imp  );
				if(!$puedePujar ){
					$res = $this->error_puja('imp_max_licitador', $subasta->licit, $is_gestor);
                    return $res;
				}


			}
            //Limite que puede un cliente tener adjudicaciones en una subastas
            elseif( !(\Config::get('app.use_credit')) && \Config::get('app.disabled_ries_cli') == false && ($lote->tipo_sub == 'W' || $lote->tipo_sub == 'O') && !$is_gestor && count($u)> 0 && !empty($u[0]->max_adj) && $u[0]->max_adj > 0){




                $id_auc_sessions = $subasta->getIdAucSessionslote($subasta->cod, $subasta->lote);
                $get_session = $subasta->get_session($id_auc_sessions);

                //Miramos todas las pujas de esta subastas
                $adjudic=$gestor->getAllAdjudicacionesSession($subasta->cod, $get_session->reference, $subasta->licit);

                 $imp_adjudic = 0;
                 foreach($adjudic as $price){
                     $imp_adjudic = $price->himp_csub + $imp_adjudic;
                 }
				 $total_imp_adju = $imp_adjudic + $subasta->imp;

				 $importeOrdenes = 0;
				 if(Config::get('app.max_orders_ries_cli', false)){
					$importeOrdenes = FgOrlic::getTotalOrdersInAuction($subasta->cod, $subasta->ref, $u[0]->cli_licit, true);
				}

                 //total precios adjudicaciones es mas grande que el importe maximo del cliente devolvemos error.
                 if(($total_imp_adju + $importeOrdenes) > $u[0]->max_adj){
                    $res = $this->error_puja('imp_max_licitador', $subasta->licit, $is_gestor);
                    return $res;
                 }
            }

        }



        //si no es puja en firme, o en el caso de que lo sea el usuario no sea gestor, un usuario normal no puede hacer pujas en firme
        //un gestor haciendo una puja en firme puede pujar por el valor que quiera apartir del precio de salida, y este preci ode salida no se corregirá auqnue este fuera de escala
        if( $tipo_puja_gestor != 'firme' || $is_gestor == false){

            $escalado_correcto = $subasta->NextScaleBid(0,$lote->impsalhces_asigl0 - 1, false);

            //detectar probleams con el importe de salida si no cumple el escalado, modificamos tambie nel importe de reserva si en la configuración tenenmos que se fuerce
            //not_force_correct_price_tiempo_real sirve que en tiempo real no fuerze el escalado correcto en la primer puja
            if($lote->tipo_sub == 'W'
				&& $escalado_correcto != $lote->impsalhces_asigl0
				&& Config::get('app.force_correct_price')
				&& (empty(Config::get('app.not_force_correct_price_tiempo_real')) || Config::get('app.not_force_correct_price_tiempo_real') != 1)
			){
                $lote->impsalhces_asigl0 = $escalado_correcto;
                $lote->impres_asigl0 = $escalado_correcto;
            }

			//si es una subasta de tipo O y el importe de salida no es correcto, lo corregimos
			if($lote->tipo_sub == 'O'
				&& $escalado_correcto != $lote->impsalhces_asigl0
				&& Config::get('app.force_correct_price'))
			{
				$lote->impsalhces_asigl0 = $escalado_correcto;
                $lote->impres_asigl0 = $escalado_correcto;
			}

        }
        $subasta->impsal = $lote->impsalhces_asigl0;
        /* 2017-10-10 DE MOMENTO LO COMENTO PENDIENTE DE VER COMO FUCNIONA
        //las subastas de tipo W solo podran hacerse pujas y ordenes que superen el precio de reserva
        //para simplificar sustituimos el impsal por el impres_asigl0 esto facilita mucho el código
        if ($lote->tipo_sub == 'W' && $lote->impres_asigl0 > 0 ){
           $subasta->impsal = max($lote->impres_asigl0,$lote->impsalhces_asigl0);
        }else{
             $subasta->impsal = $lote->impsalhces_asigl0;
        }
       */

        if(empty($u) && !$is_gestor){
            \Log::info('No licit subasta cod:'.$subasta->cod.' licit:'.$cod_original_licit);
            $res = $this->error_puja('no_licit', $subasta->licit, $is_gestor);
            return $res;
        }
        //ponemos el código de token que corresponda, el del user o el del gestor
        //el gestor usa su token pero asigna la licitacion a otro código
        $licitSubalia   = !empty(Config::get('app.subalia_min_licit'))? Config::get('app.subalia_min_licit') : 100000;
        if($cod_original_licit >= $licitSubalia){
            $subalia = new Subalia();

            $tk_cliweb = $subalia->getTokenByLicit($cod_original_licit);

        }else if($is_gestor){
            $tk_cliweb = $g[0]->tk_cliweb;
        }else{
             $tk_cliweb = $u[0]->tk_cliweb;
        }
        $user->tk_CLIWEB =$tk_cliweb;
        $hash = hash_hmac("sha256",$subasta->licit ." ".$subasta->cod." ". $subasta->ref . " " .$subasta->imp, $user->tk_CLIWEB);

        if($hash != $hash_user){
           \Log::info("licit:".$subasta->licit ." cod_sub:".$subasta->cod." ref:". $subasta->ref . " imp:" .$subasta->imp);
           $res = $this->error_puja('session_end', $subasta->licit, $is_gestor);
           return $res;
        }


        /* COMPROBACIÓN PARA PUJAS MUY ELEVADAS QUE PUEDEN PROVOCAR ERROR, por eso se hace lo primero!!! */
        # Buscamos la púja más alta
            $subasta->page          = 1;
            $subasta->itemsPerPage  = 1;
            $pujas                  = $subasta->getPujas();

            if(!empty($pujas)) {
                $puja               = $pujas[0];
                $max_puja           = $puja->imp_asigl1;
                $subasta->sin_pujas = false;

            } else {
                $puja               = false;
                $max_puja           = $subasta->impsal; // precio de salida
                $subasta->sin_pujas = true;
                 \Log::info("Sin pujas");
            }
        //si la puja es demasiado alta devuelve error
        $limit_max_bid =\Config::get('app.limit_max_bid');
        if(!empty($limit_max_bid) && $max_puja >0){
            if($subasta->imp >=  ($limit_max_bid  * $max_puja ) )
                {
                 $res = $this->error_puja('not_accept_bid', $subasta->licit, $is_gestor);
                 return $res;
                }
        }
        /* FIN COMPROBACION DE PUJAS QUE PROBOCAN ERROR */


         //debemos poner le limite de pujas que se van a cargar, es necesario ya que más arreiba se ponen estos valores a 1 para hacer unos calculos.
            $subasta->page          = 1;
            $subasta->itemsPerPage  = 100;


		#PUJA EN SUBASTA INVERSA
		if(!empty($lote->inversa_sub) &&  $lote->inversa_sub == "S"){
			\Log::info("subasta inversa");
			return $this->executeActionInversa($subasta, $lote, $is_gestor);
		}




        # Escalado de la puja automático
        /*
         * ¿si el importe de la subasta respeta el escalado o es posible hacer puja libre?
         */
       // if($subasta->imp == $subasta->escalado() || \Config::get('app.escalado_libre') )
        $escalado_libre = \Config::get('app.escalado_libre');
      //las ordenes pueden poner cualquier valor si escalado_libre is true

        /*
         * VAMOS A MIRAR EL LOTE Y TENER EN CUENTA SI LA SUBASTA ES DE TIPO  'W', 'O', 'P' Y MIRAR EL PRECIO DE SALIDA
         */




        \Log::info("LICIT: ". $subasta->licit." SUBASTA: ". $subasta->cod." lote: ".$subasta->ref ." importe_validate " .$subasta->impsal." subasta.imp ".  $subasta->imp);
        #para una subasta abierta P, si un usuario notifica a la casa de subastas una puja inferior o igual a la actual
        if($tipo_puja_gestor == 'abiertaP' && $is_gestor && !empty($pujas) &&  $subasta->imp <= $pujas[0]->imp_asigl1){

           $mail = new MailController();
           $mail->emailPujaInferior($subasta->cod, $subasta->ref, $subasta->licit, $pujas[0]->imp_asigl1);
           $res = $this->error_puja('small_bid', $subasta->licit, $is_gestor);
            return $res;
        }



        if($subasta->imp < $subasta->impsal)
        {
            $res = $this->error_puja('small_bid', $subasta->licit, $is_gestor);
            return $res;
        }
        /* escalado libre para pujas */
            $escalado_libre_pujas = \Config::get('app.escalado_libre_pujas');
            //si esta marcado que puedan hacer pujas libres antes de que empiece subasta
            if($escalado_libre_pujas == 1 && strtotime($lote->start_session) > time() ){
                $permitir_puja_libre = true;
            }elseif($escalado_libre_pujas == 2){
                $permitir_puja_libre = true;
            }else{
                $permitir_puja_libre = false;
            }

        //al pasar el error en el mensaje 2 no se le muestra al admin, todos los mensajes de msg1 los ve l admin
        if ($subasta->validateScale($subasta->impsal,$subasta->imp ) == false  && !($tipo_puja_gestor == 'firme' && ($is_gestor || Config::get('app.pujas_enfirme', 0)) )  && (($can_do != 'orders' && $permitir_puja_libre == false ) || ($can_do == 'orders' && ( empty($escalado_libre) || !$escalado_libre) )) ){

			$res = array(
                    'status' => 'error',
                    'msg_2' => 'bid_scaling',
                    'cod_licit_actual'  => $subasta->licit,
					'no_interrupt_cd_time' => 'true'
                    );
            if (!empty($is_gestor)){
                $res['is_gestor'] = TRUE;
            }

            return $res;
        }

        # Solo el gestor y los clientes con la configuración pujas_enfirme puede hacer pujas en firme.
        elseif ($tipo_puja_gestor == 'firme' && ($is_gestor || Config::get('app.pujas_enfirme', 0) ))
        {
			\Log::info("puja en firme");
			$imp_original_formatted = \Tools::moneyFormat($subasta->imp);
           	$cod_licit_db = "";
            //la puja en firme debe superar al precio actual,
            if ( ($subasta->imp <= $max_puja && $max_puja!=$subasta->impsal)  )
            {
                $res = $this->error_puja('small_bid', $subasta->licit, $is_gestor);
                return $res;
            }else{

                /* codigo nuevo */
                $ordenes = $subasta->getOrden();
                $orden = NULL;
                $cod_licit_actual = $subasta->licit;
                if(count($ordenes) > 0){

                    $orden = head($subasta->getOrden());
                    $type_bid_orlic = (empty($orden->tipop_orlic))?  "W" :   $orden->tipop_orlic;
                    //si hay ordenes y son mayores a la puja actual
                    if( !empty($orden->himp_orlic) && $orden->himp_orlic > $max_puja && $orden->licit_orlic != $cod_licit_actual) {

                        if($orden->himp_orlic >= $subasta->imp){
                            //puja en firme

							$addPuja = $subasta->addPuja();

							if ($addPuja['status'] == 'error'){
								$res = $this->error_puja($addPuja['msg'],  $subasta->licit, $is_gestor );
								return $res;
							}

							#email de confirmar puja
							if(Config::get('app.pujas_enfirme', 0)){
								$this->email_bid_confirmed($subasta, $addPuja);
							}

                            $cod_licit_actual = $subasta->licit;
                            $subasta->licit = $orden->licit_orlic;
							$subasta->type_bid = $type_bid_orlic;
                            //si es mayor buscamos le nuevo importe
                            if($orden->himp_orlic > $subasta->imp){
                               $subasta->imp = min($orden->himp_orlic, $subasta->NextScaleBid($subasta->impsal,$subasta->imp));
                            }
							//orden que aguanta la puja

							#Añadimos puja auto, y enviamos email de confirmación
							$addPuja = $subasta->addPuja(FALSE, 'A');
							if(Config::get('app.pujas_enfirme', 0)){
								$this->email_bid_confirmed($subasta, $addPuja, 'AUTOMATICA',$orden->himp_orlic);
								$this->sendEmailSobrepuja($subasta->cod, $cod_licit_actual, $subasta->ref, "puja");
							}

                            $cod_licit_db = $subasta->licit;
                        }else{

                            //guardamso los datos para usarlos luego
                            $cod_licit_actual = $subasta->licit;
                            $imp_actual = $subasta->imp;
                            $type_bid_actual = $subasta->type_bid;

                            //ponemos los datos de la orden
                            $subasta->licit = $orden->licit_orlic;
                            $subasta->imp = $orden->himp_orlic;
                            $subasta->type_bid = $type_bid_orlic;
							$cod_licit_db =  $orden->licit_orlic;

							//puja de la orden
							$addPuja = $subasta->addPuja(FALSE, 'A');

							if ($addPuja['status'] == 'error'){
								$res = $this->error_puja($addPuja['msg'],  $subasta->licit, $is_gestor );
								return $res;
							}

							if(Config::get('app.pujas_enfirme', 0)){
								$this->email_bid_confirmed($subasta, $addPuja, 'AUTOMATICA', $orden->himp_orlic);
							}

                            $subasta->licit = $cod_licit_actual;
                            $subasta->imp = $imp_actual;
							$subasta->type_bid =$type_bid_actual;

                            //puja en firme
							$addPuja = $subasta->addPuja();
							if ($addPuja['status'] == 'error'){
								$res = $this->error_puja($addPuja['msg'],  $subasta->licit, $is_gestor);
								return $res;
							}

							#email de confirmar puja
							if(Config::get('app.pujas_enfirme', 0)){
								$this->email_bid_confirmed($subasta, $addPuja);
								#enviamos email de sobrepuja
								$this->sendEmailSobrepuja($subasta->cod, $cod_licit_actual, $subasta->ref, "puja");
							}
                        }
                    }else{

                        $addPuja = $subasta->addPuja();

						if ($addPuja['status'] == 'error'){
							$res = $this->error_puja($addPuja['msg'],  $subasta->licit, $is_gestor);
							return $res;
						}

						if ($addPuja['status'] == 'close'){
							$status = 'close';
						}

                        $cod_licit_db = "";
						$cod_licit_actual = $subasta->licit;

						#email de confirmar puja
						if(Config::get('app.pujas_enfirme', 0)){
							$this->email_bid_confirmed($subasta, $addPuja);
							$this->sendEmailSobrepuja($subasta->cod,$cod_licit_actual, $subasta->ref, "puja");
						}
                    }

                }else{

                    $addPuja = $subasta->addPuja();
                    $cod_licit_db = "";
					$cod_licit_actual = $subasta->licit;

					if ($addPuja['status'] == 'error'){
						$res = $this->error_puja($addPuja['msg'],  $subasta->licit, $is_gestor);
						return $res;
					}

					#email de confirmar puja
					if(Config::get('app.pujas_enfirme', 0)){
						$this->email_bid_confirmed($subasta, $addPuja);
						$this->sendEmailSobrepuja($subasta->cod,$cod_licit_actual, $subasta->ref, "puja");
					}
					if ($addPuja['status'] == 'close'){
						$status = 'close';
					}

                }

                /* fin codigo nuevo */
                $actual_bid = $subasta->imp;
                $siguiente = $subasta->NextScaleBid($subasta->impsal,$actual_bid );
				//$imp_original_formatted =  \Tools::moneyFormat($subasta->impsal);
            	//$imp_original_formatted = \Tools::moneyFormat($subasta->imp);
                $formatted_actual_bid = \Tools::moneyFormat($actual_bid);
                $resultado = array();
                array_push($resultado, 'addPuja');
				if(empty($status)){
					$status = 'success';
				}
                $res = array(
                        'status'            => $status,
                        'msg_1'             => 'higher_bid',
                        'msg_2'             => 'correct_bid',
						'msg_close'			=>  trans(\Config::get('app.theme').'-app.msg_success.makeOfferBuyLot',['lot' => $lote->descweb_hces1]),
                        'cod_licit_actual'  => $cod_licit_actual,
                        'cod_licit_db'      => $cod_licit_db,
                        'actual_bid'        => $actual_bid,
                        'formatted_actual_bid' => $formatted_actual_bid,
                        'imp_original_formatted' => $imp_original_formatted,
                        'siguiente'         => $siguiente,
                        'test'              => $resultado,
                        'type_bid'          => $subasta->type_bid,
                        'winner'            => $subasta->licit,
                        'sobrepuja'         => true,
                        'imp_original'      => $subasta->impsal,
                        'imp'               =>  $subasta->imp
                    );
                # Consultamos todas las pujas para las ordenes, para poder mostrar la lista entera en la lista de pujas
                $subasta->page      = 'all';
                $res['pujasAll']    = $subasta->getPujas();
                # Fin listado de pujas

                if (!empty($is_gestor)){
                    $res['is_gestor'] = TRUE;
                }

                return json_encode($res);






            }
        }
        else
        {
            /*
            # Buscamos la púja más alta
            $subasta->page          = 1;
            $subasta->itemsPerPage  = 1;
            $pujas                  = $subasta->getPujas();

            if(!empty($pujas)) {
                $puja               = $pujas[0];
                $max_puja           = $puja->imp_asigl1;
            } else {
                $puja               = false;
                $max_puja           = $subasta->impsal; // precio de salida
            }
            */

            //comentado el 20170512
            //$siguiente              = $this->siguienteEscalado($subasta->imp);
            //modificado 2017-05_11
            $siguiente_puja_max     = $subasta->NextScaleBid($subasta->impsal,$max_puja);
           // $siguiente_puja_max     = $this->siguienteEscalado($max_puja);

            $resultado = array();

			# Comprobamos si hay ordenes
			if( \Config::get('app.use_credit') && $lote->tipo_sub == 'W'){
				#se cojera las orden segun criteriso de credito , por lo que se pueden llegar a descartar ordenes
				$orden = $subasta->getOrdenCredit($lote->reference, $max_puja, $subasta->imp,  $subasta->licit);
			}else{
				$orden = head($subasta->getOrden());
			}

			#
			#EN EL MOMENTO DE RECOGER LAS ORDENES COMPROBAMOS SI SE PUEDE COGER ESA ORDEN O AL COJERLA SE GENERARÍA UNA PUJA QUE EL USUARIO NO PODRIA ASUMIR

            # Solo pueden entrar órdenes de licitación (order por importe de un lote que va por delante al lote actual)
            if (!empty($can_do) && $can_do == 'orders')
            {

                # Obtiene las órdenes del usuario para el lote
                $ordenes = $subasta->getOrdenes("AND licitadores.COD_LICIT = ".intval($subasta->licit));

                # Si el importe de la puja es mas grande o igual al importe de salida del lote
                if ($subasta->imp >= $subasta->impsal)
                {

                    # Siempre que hayan ordenes de licitación
                    if (!empty($ordenes))
                    {
                        $orden = head($ordenes);

                        # Si importe de la puja es más grande que la orden de licitación más grande actual.
                        if ($subasta->imp > $orden->himp_orlic)
                        {

                            $ord = $subasta->addOrden();//$this->addOrden($subasta);//

                            # Si el estado de la orden es correcta
                            if ($ord['status'] == 'success')
                            {

                                # Cogemos la nueva orden
                                $orden = head($subasta->getOrden($subasta->licit));
                                $this->sendEmailSobrepuja($subasta->cod, $subasta->licit, $subasta->ref, "orden");
                                $res = array(
                                    'status'            => 'success',
                                    'msg_2'             => 'add_bidding_order',
                                    'cod_licit_actual'  => $subasta->licit,
                                    'can_do'            => $can_do,
                                    'himp_formatted'    => \Tools::moneyFormat($orden->himp_orlic), //resultado para ficha subasta normal
                                    'sobreorden'    => true
                                );

                                # Fusionamos el resultado para la ficha de subasta normal "sin tiempo real"
                                $res = array_merge((array) $orden, $res);

                            } elseif ($ord['status'] == 'error')
                                {
                                    $res = array(
                                       'status' => 'error',
                                       'msg_1' => $ord['msg'],
                                       'cod_licit_actual' => $subasta->licit
                                   );

                                   if (!empty($is_gestor))
                                   {
                                       $res['is_gestor'] = TRUE;
                                   }

                                   return $res;
                                }
                            else
                            {

                                 $res = array(
                                    'status' => 'error',
                                    'msg_2' => 'order_lower',
                                    'cod_licit_actual' => $subasta->licit
                                );
                            }

                        }
                        else
                        {
                            $res = array(
                                'status' => 'error',
                                'msg_2' => 'order_lower',
                                'cod_licit_actual' => $subasta->licit
                            );
                        }

                    }
                    else
                    {

                        # Añadimos la orden
                        $ord = $subasta->addOrden();//$this->addOrden($subasta);//$subasta->addOrden();
                         if ($ord['status'] == 'error')
                        {
                            $res = array(
                               'status' => 'error',
                               'msg_1' => $ord['msg'],
                               'cod_licit_actual' => $subasta->licit
                           );

                           if (!empty($is_gestor))
                           {
                               $res['is_gestor'] = TRUE;
                           }

                           return $res;
                        }
                        # Cogemos la nueva orden
                        $orden = head($subasta->getOrden($subasta->licit));
                        $this->sendEmailSobrepuja($subasta->cod, $subasta->licit, $subasta->ref, "orden");
                        # Primera OL añadida desde subastas tipo W, solo OL
                        if ($ord['status'] == 'success')
                        {
                            $res = array(
                                'status'            => 'success',
                                'msg_2'             => 'add_bidding_order',
                                'cod_licit_actual'  => $subasta->licit,
                                'himp_formatted'    => \Tools::moneyFormat($orden->himp_orlic), //resultado para ficha subasta normal
                                'can_do'            => $can_do,
                                'opt'               => 'XFGN'
                            );
                        }
                    }

                }
                else
                {

                    $res = array(
                        'status' => 'error',
                        'msg_2' => 'order_lower',
                        'cod_licit_actual' => $subasta->licit
                    );

                }

                if (!empty($is_gestor))
                {
                    $res['is_gestor'] = TRUE;
                }

                return $res;
            }

            $imp_original           = $subasta->imp;
            $imp_original_formatted = \Tools::moneyFormat($subasta->imp);



            # Orden de Licitacion si la puja es mayor que el siguiente escalado (automáticamente lo convertimos en Orden), o si el ganador actual puja por el escalado tambien se convierte en orden
         //   if($subasta->imp > $siguiente_puja_max || (isset($puja->cod_licit) && $subasta->imp == $siguiente_puja_max && $subasta->licit == $puja->cod_licit)) {
              if($subasta->imp > $siguiente_puja_max) {
                array_push($resultado, 'Entramos en OL');

                # Existen OL anteriores
                # Comprobamos que el importe de la orden actual sea mas grande que la puja maxima
                $mensaje_1  = "";
                $mensaje_2  = "";
                $status     = "";


                # Si la orden de licitacion actual es más grande que la puja máxima actual
                if( !empty($orden->himp_orlic) && $orden->himp_orlic > $max_puja)
                {

                    # Comprobamos que sean licitadores diferentes
                    if($orden->licit_orlic != $subasta->licit)
                    {

                        array_push($resultado, 'Insertamos OL actual mas grande que puja maxima');
                        # Añadimos una OL
                         $ord = $subasta->addOrden();//$this->addOrden($subasta);//$subasta->addOrden();
                         if ($ord['status'] == 'error')
                         {
                             $res = array(
                                'status' => 'error',
                                'msg_1' => $ord['msg'],
                                'cod_licit_actual' => $subasta->licit
                            );

                            if (!empty($is_gestor))
                            {
                                $res['is_gestor'] = TRUE;
                            }

                            return $res;
                         }

                        array_push($resultado, 'usuariodb != actual');

                        $debug  = 0;





                        //BUCLE QUE CALCULA LAS SOBREPUJAS CUANDO HAY UNA ORDEN Y LLEGA UNA NUEVA ORDEN

                        //datos del usuario que hizo la orden
                        $imp_orlic = $orden->himp_orlic;
                        $licit_orlic =  $orden->licit_orlic;
                        $type_bid_orlic = (empty($orden->tipop_orlic))?  "W" :   $orden->tipop_orlic;
                        //datos del usuario actual
                        $imp_actual = $subasta->imp;
                        $licit_actual =  $subasta->licit;
                        $type_bid_actual =  $subasta->type_bid;

                        //sirve para que la web detecte que hay una orden nueva, forzamoa a un valor mayor seguro patra que lo haga
                        $imp_resp = max($imp_orlic,$imp_actual)+1;
                        //siguiente puja
                        $siguiente_puja = $subasta->NextScaleBid($subasta->impsal,$max_puja);
                        $anterior_puja = $max_puja;
                        //cuando se supere la orden o la puja actual finalizamos la competencia

                        while ($siguiente_puja <= $imp_orlic && $siguiente_puja <= $imp_actual ){

                            //Si hay ordenes es que tiene que haber pujas, si las han borrado esto daría un bucle infinito, por eso forzamos a que ponga sin pujas
                            $subasta->sin_pujas = false;
                            $anterior_puja = $siguiente_puja;
                            $siguiente_puja = $subasta->NextScaleBid($subasta->impsal,$siguiente_puja);

                            $siguiente = $siguiente_puja;
                        }

                         //hacemos ultima puja de la orden de base de datos si supera la siguiente puja
                        if($imp_orlic  > $imp_actual ){
                            \Log::info("CASO 1 ");
                            //ponemos la puja del usuario con el escalado anterior
                            $subasta->licit = $licit_actual;
                            //2018_04_10 debemos coger el precio de la puja actual, ya que ahora puede haber caso de que se permitan pujas libres
                            $subasta->imp   = $imp_actual;      //$subasta->imp   = $anterior_puja;
                            $subasta->type_bid = $type_bid_actual;
                            $addPuja = $subasta->addPuja();
                            $this->email_bid_confirmed($subasta,$addPuja);
                            //ponemos  la puja actual para la orden de base de datos
                            $subasta->licit = $licit_orlic ;
                            $subasta->imp   = min($siguiente_puja,$imp_orlic) ;
                            $subasta->type_bid = $type_bid_orlic;
                            //marcamso la puja como automatica
                            $addPuja = $subasta->addPuja(FALSE, 'A');
                            $this->email_bid_confirmed($subasta,$addPuja,'AUTOMATICA',$imp_orlic);


                            //mostramos mensaje de sobrepuja al usuario actual
                            $status     = 'success';
                            $mensaje_2  = 'higher_bid';
                            $mensaje_1  = 'correct_bid';

                            $siguiente = $subasta->NextScaleBid($subasta->impsal,$subasta->imp );
                            $winner = $licit_orlic;
                        }
                        //al quedar empates primero se pone la de bbdd y luego la otra
                        elseif ($imp_orlic == $imp_actual){

                            \Log::info('CASO 2');

                            $type_anterior = $type_bid_actual;
                            //ponemos la puja del usuario con el escalado anterior
                            $subasta->licit = $licit_actual;
                            $subasta->imp   = $imp_orlic;
                            $subasta->type_bid = $type_bid_actual;
                            $addPuja = $subasta->addPuja();
                            $this->email_bid_confirmed($subasta,$addPuja);

                            //ponemos la puja anterior para el usuario de base de datos
                            $subasta->licit = $licit_orlic ;
                            $subasta->imp   = $imp_orlic;
                            $subasta->type_bid = $type_bid_orlic;
                            $addPuja = $subasta->addPuja(FALSE, 'A');
                            $this->email_bid_confirmed($subasta,$addPuja,'AUTOMATICA',$imp_orlic);


                            //mostramos mensaje de sobrepuja al usuario actual
                            $status     = 'success';
                            $mensaje_2  = 'higher_bid';
                            $mensaje_1  = 'correct_bid';
                            $siguiente =$siguiente_puja;
                           // $siguiente = $subasta->NextScaleBid($subasta->impsal,$siguiente_puja);
                            $winner = $licit_orlic;
                        }
                        elseif ( $imp_actual > $imp_orlic){

                            \Log::info('CASO 3');

                            //ponemos la puja anterior para el usuario de base de datos
                            $subasta->licit = $licit_orlic ;
                            //puede que el importe en bbdd sea de una orden de licitación y no mantenga el escalado por eso cogemos el imp-orlic
                            $subasta->imp   = $imp_orlic; //$subasta->imp   = $anterior_puja;
                            $subasta->type_bid = $type_bid_orlic;
                            $addPuja = $subasta->addPuja(FALSE, 'A');
                            $this->email_bid_confirmed($subasta,$addPuja,'AUTOMATICA', $imp_orlic);
                            //ponemos la puja del usuario con el escalado anterior
                            $subasta->licit = $licit_actual;

                            if($lote->impres_asigl0 > 0 && $imp_orlic <  $lote->impres_asigl0  ){
                                //cogemos el minimo de la puja actual o del precio de reserva

                                $siguiente_puja  = min($imp_actual,$lote->impres_asigl0);
                            }
                            //2018_04_10 ahora puede haber pujas fuera de escala
                            else{
                                $siguiente_puja = min($siguiente_puja,$imp_actual);
                            }

                            $subasta->imp   =$siguiente_puja;
                            $subasta->type_bid = $type_bid_actual;
                            $addPuja = $subasta->addPuja();
                            $this->email_bid_confirmed($subasta,$addPuja);
                            $siguiente =$siguiente_puja;

                            //mostramos mensaje de sobrepuja al usuario de bbdd
                            $status     = 'success';
                            $mensaje_1  = 'higher_bid';
                            $mensaje_2  = 'correct_bid';


                            $siguiente = $subasta->NextScaleBid($subasta->impsal,$siguiente_puja);
                            $winner = $licit_actual;
                        }

                           //enviamos email de sobrepuja
                            $this->sendEmailSobrepuja($subasta->cod, $licit_actual, $subasta->ref, "puja");

                        //FIN BUCLE QUE CALCULA LAS SOBREPUJAS CUANDO HAY UNA ORDEN Y LLEGA UNA NUEVA ORDEN


                        if ($addPuja['status'] == 'error'){
                            $res = $this->error_puja($addPuja['msg'],  $subasta->licit, $is_gestor );

                            return $res;
                        }

                        if (!isset($winner)){
                            $winner = $subasta->licit;
                        }

                        if (!isset($type_anterior)){
                            $type_anterior = $subasta->type_bid;
                        }
                        $res =  array(
                            'status'            => $status,
                            'msg_1'             => $mensaje_1,
                            'msg_2'             => $mensaje_2,
                            'cod_licit_actual'  => $licit_actual,
                            'cod_licit_db'      => $licit_orlic,
                            'actual_bid'        => $addPuja['actual_bid'],
                            'formatted_actual_bid' => $addPuja['formatted_actual_bid'],
                            'siguiente'         => $siguiente,
                            'winner'            => $winner ,
                            'type_bid'          => $type_anterior,
                            'test'              => $resultado,
                            'imp_original'      => $imp_original,
                            'imp_original_formatted'      => $imp_original_formatted,
                            'imp'               =>  $imp_resp
                        );

                        # Consultamos todas las pujas para las ordenes, para poder mostrar la lista entera en la lista de pujas
                        $subasta->page      = 'all';
                        $res['pujasAll']    = $subasta->getPujas();
                        # Fin listado de pujas

                        if (!empty($is_gestor)){
                            $res['is_gestor'] = TRUE;
                        }

                        return $res;

                    }else{
                             \Log::info("CASO 0 himp_orlic " .$orden->himp_orlic." subasta.imp ".  $subasta->imp);
                        # Si la orden de licitación máxima actual es mayor o igual que el importe de la puja
                        if ($orden->himp_orlic >= $subasta->imp){
                            $res = $this->error_puja('order_lower',  $subasta->licit, $is_gestor );

                            return $res;
                        }


                        $ord = $subasta->addOrden();//$this->addOrden($subasta);//$subasta->addOrden();

                        if ($ord['status'] == 'error'){
                            $res = $this->error_puja($ord['msg'],  $subasta->licit, $is_gestor );
                            return $res;
                        }
                        //no interrumpimos a no ser que entre en el siguiente if
                        $no_interrupt_cd_time='true';
                        //si hay importe de reserva, y la orden anterior no lo iguala osupera y la actual si
                        if(  $lote->impres_asigl0 > 0 && $orden->himp_orlic < $lote->impres_asigl0 ){

                            $subasta->imp   =  min($lote->impres_asigl0,$subasta->imp);
                            $addPuja       = $subasta->addPuja();
                            $this->email_bid_confirmed($subasta,$addPuja);
                            $max_puja = $addPuja['actual_bid'];
                            $siguiente_puja_max = $subasta->NextScaleBid($subasta->impsal,$subasta->imp);
                            //interrumpimos por que ha habido una puja
                            $no_interrupt_cd_time='false';
                        }







                        if (!isset($winner)){
                            $winner = $subasta->licit;
                        }
                        $res = array(
                            'status'            => 'success',
                            'msg_2'             => 'add_bidding_order',
                            'msg_1'             => "",
                            'cod_licit_actual'  => $subasta->licit,
                            'cod_licit_db'      => $subasta->licit,
                            'formatted_actual_bid'  => \Tools::moneyFormat($max_puja),
                            'actual_bid'        => $max_puja,
                            'siguiente'         => $siguiente_puja_max,
                            'winner'            => $winner,
                            'type_bid'          => $subasta->type_bid,
                            'test'              => $resultado,
                            'imp_original'      => $imp_original,
                            'imp_original_formatted'      => $imp_original_formatted,
                            'imp'               =>  $subasta->imp,
                            'no_interrupt_cd_time' => $no_interrupt_cd_time
                        );
                        $res['pujasAll']    = $subasta->getPujas();
                        if (!empty($is_gestor)){
                            $res['is_gestor'] = TRUE;
                        }

                        return $res;
                    }

                }
                else
                {

                    /*
                    |--------------------------------------------------------------------------
                    | Caso en que la orden OL actual es inferior a la puja maxima actual
                    |--------------------------------------------------------------------------
                    */

                    array_push($resultado, 'Insertamos OL actual inferior a puja maxima');
                    # Añadimos una OL
                    $ord = $subasta->addOrden();

                     if ($ord['status'] == 'error'){
                         $res = $this->error_puja($ord['msg'],  $subasta->licit, $is_gestor );
                        return $res;
                     }


                    # La ultima puja no es mia
                    if(isset($puja->cod_licit) && $puja->cod_licit != $subasta->licit) {
                        \Log::info('CASO 4');
                         if(  $lote->impres_asigl0 > 0 &&   $puja->imp_asigl1 < $lote->impres_asigl0){
                                $siguiente_puja_max  = min ($subasta->imp, $lote->impres_asigl0);
                            }
                        $subasta->imp   = $siguiente_puja_max;

                        $addPuja       = $subasta->addPuja();
                        $this->email_bid_confirmed($subasta,$addPuja);
                        if ($addPuja['status'] == 'error'){
                             $res = array(
                                'status' => 'error',
                                'msg_1' => $addPuja['msg'],
                                'cod_licit_actual' => $subasta->licit
                            );

                            if (!empty($is_gestor)){
                                $res['is_gestor'] = TRUE;
                            }

                            return $res;
                        }
                        //enviamos email de sobrepuja
                        $this->sendEmailSobrepuja($subasta->cod, $subasta->licit, $subasta->ref, "puja");
                        $actual_bid = $addPuja['actual_bid'];
                        $formatted_actual_bid = $addPuja['formatted_actual_bid'];
                        $cod_licit_db = $puja->cod_licit;
                        array_push($resultado, 'addPuja');
                    }
                    else{
                        \Log::info("CASO 10 " );
                        if (!empty($puja)){

                            $actual_bid = $puja->imp_asigl1;//$siguiente_puja_max;
                            $formatted_actual_bid = \Tools::moneyFormat($puja->imp_asigl1);
                            if (!empty($puja->cod_licit)){
                                $cod_licit_db = '';//$puja->cod_licit;
                            }else{
                                $cod_licit_db = '';
                            }
                            //debe coger el valor de la ultima puja, ya que no se hace pujas, is no que se guarda una orden nueva por el importe pasado
                             $siguiente = $subasta->NextScaleBid($subasta->impsal,$puja->imp_asigl1);
                             $rewrite_siguiente = false;
                            //si el usuario tiene pujas anteriorm pero estas no han superado el preci ode reserva
                             //no se envia emai lde sobre puja por que es el mismo usuario que se sobrepuja
                              if($puja->imp_asigl1 < $lote->impres_asigl0){
                                $subasta->imp = min( $subasta->imp,$lote->impres_asigl0 );
                                $addPuja       = $subasta->addPuja();
                                $this->email_bid_confirmed($subasta,$addPuja);
                                $rewrite_siguiente = true;
                                $actual_bid = $addPuja['actual_bid'];
                                $formatted_actual_bid = $addPuja['formatted_actual_bid'];
                                $cod_licit_db = '';
                                }else{
                                    //no interrumpiremos la cuenta atras
                                    $no_interrupt_cd_time='true';
                                }
                        }else{
                            \Log::info('CASO 5');
                            //primera puja;
                            # Orden de licitación cuando no hay ninguna puja ni orden previos
                            if($lote->impres_asigl0 > 0 && $subasta->imp>= $lote->impres_asigl0){
                                $siguiente_puja_max  = max($siguiente_puja_max, $lote->impres_asigl0);
                            }//si hay importe de reserva pero la puja no llega hacemos por el valor de la puja
                            elseif($lote->impres_asigl0 > 0){
                                \Log::info('importe por puja');
                               $siguiente_puja_max = $subasta->imp;
                            }
                            $subasta->imp   = $siguiente_puja_max;
                            $addPuja       = $subasta->addPuja();
                            $this->email_bid_confirmed($subasta,$addPuja);

                            if ($addPuja['status'] == 'error'){
                                 $res = $this->error_puja($addPuja['msg'],  $subasta->licit, $is_gestor );

                                return $res;
                            }

                            $actual_bid = $addPuja['actual_bid'];
                            $formatted_actual_bid = $addPuja['formatted_actual_bid'];
                            $cod_licit_db = '';
                            array_push($resultado, 'addPuja');
                            array_push($resultado, 'Orden de licitación cuando no hay ninguna puja ni orden previos');
                        }
                    }

                    array_push($resultado, 'paso 6');
                    if (!isset($rewrite_siguiente) || $rewrite_siguiente == true){
                        $siguiente = $subasta->NextScaleBid($subasta->impsal,$subasta->imp);
                    }
                    if (!isset($winner)){
                            $winner = $subasta->licit;
                    }
                    $res = array(
                        'status'            => 'success',
                        'msg_1'             => 'higher_bid',
                        'msg_2'             => 'add_bidding_order',
                        'cod_licit_actual'  => $subasta->licit,
                        'cod_licit_db'      => $cod_licit_db,
                        'actual_bid'        => $actual_bid,
                        'formatted_actual_bid' => $formatted_actual_bid,
                        //'siguiente'         => $this->siguienteEscalado($siguiente_puja_max),
                        'siguiente'         => $siguiente,
                        'winner'            => $winner,
                        'type_bid'          => $subasta->type_bid,
                        'test'              => $resultado,
                        'imp'               => $imp_original,
                        'imp_original_formatted'      => $imp_original_formatted,
                        'imp_original'      => $imp_original,
                        'no_interrupt_cd_time' => $no_interrupt_cd_time
                    );
                    $res['pujasAll']    = $subasta->getPujas();
                    if (!empty($is_gestor)){
                        $res['is_gestor'] = TRUE;
                    }

                    return $res;

                }

            }


            # Insertamos una puja siempre y cuando el importe de la puja sea mayor que la puja máxima actual
            //o la puja actual sea igual al precio de salida y no haya pujas
            else if($subasta->imp > $max_puja || ($max_puja == $subasta->impsal && $subasta->imp == $subasta->impsal  )) {

                array_push($resultado, 'paso 7');

                # Evitamos que un mismo usuario puje dos veces, solo puede pujar dos veces si la puja que tenia hecha era menor al importe de reserva
                if(!empty($puja->cod_licit) && ($subasta->licit == $puja->cod_licit && $puja->imp_asigl1 >= $lote->impres_asigl0 ) && $subasta->licit != Config::get('app.dummy_bidder')) {

                    $res = $this->error_puja('same_bidder',  $subasta->licit, $is_gestor );
                    return $res;
                }

                if (!empty($orden)){
                    $usuario_1  = $orden->licit_orlic;
                }else{
                    $usuario_1 = "";
                }
                $usuario_2  = $subasta->licit;
                $mensaje_1  = "";
                $mensaje_2  = "";
                $status     = "";

                # Comprobamos que el importe de la orden actual sea mas grande que la puja maxima
                if (!empty($orden) && $orden->himp_orlic > $max_puja) {
                    array_push($resultado, 'paso 8');

                    # Coincide el importe de la puja con el de la orden de licitación máxima
                    # El usuario de la base de datos (usuario 1) gana la puja.
                    if ($orden->himp_orlic == $subasta->imp) {
                         \Log::info('CASO 6');
                    array_push($resultado, 'paso 9');

                        $type_anterior = $subasta->type_bid;


                        //generamos la puja del actual usuario
                        $licit_online = $subasta->licit;
                        $puja_res       = $subasta->addPuja();
                        $this->email_bid_confirmed($subasta,$puja_res);
						$siguiente_puja_max = min($orden->himp_orlic, $siguiente_puja_max);
                         //guardamos despues la puja del de bbdd para que gane
                        $subasta->licit = $orden->licit_orlic;
                        $subasta->imp   =$siguiente_puja_max ;
                        $subasta->type_bid = $orden->tipop_orlic;
                        $puja_res       = $subasta->addPuja(FALSE, 'A');
                        $this->email_bid_confirmed($subasta,$puja_res,'AUTOMATICA',$orden->himp_orlic);
                        if ($puja_res['status'] == 'error'){
                            $res = $this->error_puja($puja_res['msg'],  $subasta->licit, $is_gestor );
                            return $res;
                        }

                        $status         = "error";
                        //$mensaje_2      = 'higher_bid';
                        $mensaje_1      =  'higher_bid';
                       // $siguiente      = $this->siguienteEscalado($siguiente_puja_max);
                        $siguiente = $subasta->NextScaleBid($subasta->impsal,$subasta->imp);
                        $siguiente_formatted_puja_max = \Tools::moneyFormat($siguiente_puja_max);
                        //winner no l oesta cogiendo el json poer eso pactualizo el licit de Subasta
                        $winner = $orden->licit_orlic;
                        $subasta->licit = $orden->licit_orlic;
                        //avisar que vamos a mostrar el mensaje que és la ultima orden
                        $match_maxorder_bid = true;
                    # El usuario 1 gana la puja pero ambos realizan una puja.
                    }
                    elseif($orden->himp_orlic > $subasta->imp)  {
                        array_push($resultado, 'paso 10');
                         \Log::info('CASO 8');
                        $puja_res       = $subasta->addPuja();
                        $this->email_bid_confirmed($subasta,$puja_res);

                        $type_anterior = $subasta->type_bid;

                        if ($puja_res['status'] == 'error'){
                             $res = $this->error_puja($puja_res['msg'],  $subasta->licit, $is_gestor );

                            return $res;
                        }
                        $licit_anterior = $subasta->licit;
                        $licit_online = $licit_anterior;
                        $subasta->licit = $orden->licit_orlic;

                        // cogemos el siguiente importe si en base de datos no es más pequeño
                        $imp_anterior=$subasta->imp;
                        $new_imp = min($orden->himp_orlic, $subasta->NextScaleBid($subasta->impsal,$subasta->imp));

                        $subasta->imp = $new_imp;
                        $subasta->type_bid = $orden->tipop_orlic;
                        $puja_res       = $subasta->addPuja(FALSE, 'A');
                        $this->email_bid_confirmed($subasta,$puja_res,'AUTOMATICA',$orden->himp_orlic);



                        if ($puja_res['status'] == 'error'){
                            $res = $this->error_puja($puja_res['msg'],  $subasta->licit, $is_gestor );
                            return $res;
                        }



                        $status         = "error";
                        //$mensaje_2      =  trans(\Config::get('app.theme').'-app.tr.error.higher_bid');
                        $mensaje_1      =  'higher_bid';
                        //$siguiente      = $this->siguienteEscalado($this->siguienteEscalado($siguiente_puja_max));
                        //el siguiente sera el maximo de puja que se haya hecho.


                        $siguiente = $subasta->NextScaleBid($subasta->impsal,max($imp_anterior, $new_imp ));
                        $siguiente_puja_max = $puja_res['actual_bid'];
                        $siguiente_formatted_puja_max = $puja_res['formatted_actual_bid'];
                    }
                    //si el importe actual es mas grande que la orden
                    else {


                         \Log::info('CASO 88');



                        $licit_anterior = $subasta->licit;
                        $imp_anterior=$subasta->imp;
                        $type_anterior = $subasta->type_bid;

                        //la orden guardada debe superer el importe de salida (el importe de salida ha podido ser modificado al principio por el precio de reserva)
                        if ($orden->himp_orlic >= $subasta->impsal){
                            $subasta->licit = $orden->licit_orlic;

                            // cogemos el siguiente importe si en base de datos no es más pequeño

                            $new_imp = min($orden->himp_orlic, $subasta->NextScaleBid($subasta->impsal,$subasta->imp));

                            $subasta->imp = $new_imp;
                            $subasta->type_bid = $orden->tipop_orlic;
                            $puja_res       = $subasta->addPuja(FALSE, 'A');
                            $this->email_bid_confirmed($subasta,$puja_res,'AUTOMATICA',$orden->himp_orlic);


                            if ($puja_res['status'] == 'error'){
                                $res = $this->error_puja($puja_res['msg'],  $subasta->licit, $is_gestor );
                                return $res;
                            }
                        }
                        //hacemos la puja actual
                        $subasta->licit = $licit_anterior;
                        $subasta->imp = $imp_anterior;
                        $subasta->type_bid = $type_anterior;
                        $puja_res       = $subasta->addPuja();
                        $this->email_bid_confirmed($subasta,$puja_res);

                        if ($puja_res['status'] == 'error'){
                            $res = $this->error_puja($puja_res['msg'],  $subasta->licit, $is_gestor );
                            return $res;
                        }



                        $status         = "success";
                        $mensaje_2 = 'correct_bid';
                        $mensaje_1      =  'higher_bid';
                        //$siguiente      = $this->siguienteEscalado($this->siguienteEscalado($siguiente_puja_max));
                        //el siguiente sera el maximo de puja que se haya hecho.
                       $siguiente = $subasta->NextScaleBid($subasta->impsal,max($imp_anterior, $new_imp ));
                        $siguiente_puja_max = $puja_res['actual_bid'];
                        $siguiente_formatted_puja_max = $puja_res['formatted_actual_bid'];

                    }

                }
                else
                {
                        \Log::info("CASO 7 imp salida ".$subasta->impsal ."imp actual ".$subasta->imp);
                    #Puja normal
                    $puja_res = $subasta->addPuja();
                    $this->email_bid_confirmed($subasta,$puja_res);

                    if ($puja_res['status'] == 'error')
                    {
                        $res = $this->error_puja($puja_res['msg'],  $subasta->licit, $is_gestor );
                        return $res;
                    }
                    //lo marcamos a false ya que ahora está la puja  que acabamos de hacer
                   // $subasta->sin_pujas = false;
                    $siguiente=$subasta->NextScaleBid($subasta->impsal,$subasta->imp);
                    $siguiente_puja_max = $puja_res['actual_bid'];
                    $siguiente_formatted_puja_max = \Tools::moneyFormat($siguiente_puja_max);
                    $status = "success";
                    $mensaje_2 = 'correct_bid';
                    $mensaje_1 = 'higher_bid';
                    //si el que puja es diferente al que tenia la anterior puja, ahora puede una persona hacer varias pujas hasta que supera el preci ode reserva
                    if (!empty($puja->cod_licit) && $puja->cod_licit !=$subasta->licit ){
                        $usuario_1 = $puja->cod_licit;
                    }
                    else{
                        $usuario_1 = "";
                    }

                    $usuario_2 = $subasta->licit;

                }
                //hay que `pasar a send email sobrepuja el licitador actual de la web, y se estaba pasando el ultimo que hacia puja, es decir que fallaba cuando habia sobrepuja automática
                if(!isset($licit_online)){
                    $licit_online = $subasta->licit;
                }

                $this->sendEmailSobrepuja($subasta->cod,$licit_online, $subasta->ref, "puja");
                if (!isset($type_anterior)){
                    $type_anterior = $subasta->type_bid;
                }
                $res = array(
                        'status'            => $status,
                        'msg_1'             => $mensaje_1,
                        'msg_2'             => $mensaje_2,
                        'cod_licit_actual'  => $usuario_2,
                        'cod_licit_db'      => $usuario_1,
                        'actual_bid'        => $siguiente_puja_max,
                        'formatted_actual_bid' => $siguiente_formatted_puja_max,
                        'imp_original_formatted' => $imp_original_formatted,
                        'siguiente'         => $siguiente,
                        'test'              => $resultado,
                        'type_bid'          => $type_anterior,
                        'winner'            => $subasta->licit,
                        'sobrepuja'         => true,
                        'imp_original'      => $imp_original,
                        'imp'               =>  $subasta->imp,
                        'match_maxorder_bid' => (!empty($match_maxorder_bid))?true:false
                    );


                # Consultamos todas las pujas para las ordenes, para poder mostrar la lista entera en la lista de pujas
                $subasta->page      = 'all';
                $res['pujasAll']    = $subasta->getPujas();
                # Fin listado de pujas

                if (!empty($is_gestor)){
                    $res['is_gestor'] = TRUE;
                }

                return json_encode($res);

            } else {
                $res = $this->error_puja('small_bid',  $subasta->licit, $is_gestor );
                return $res;
            }

        }

	}


	public function addLowerBid(){

		$is_gestor = request('is_gestor');
		$subasta = new Subasta();
		$subasta->cod = request('sub_asigl0');
		$subasta->ref = request('ref_asigl0');
		$subasta->imp = request('imp_asigl0');
		$subasta->type_bid = 'W';
		$type_asigl0 = 'I';

		if($is_gestor && !empty(request('ges_cod_licit'))){
			$subasta->licit = request('ges_cod_licit');
		}
		else{
			$subasta->licit = request('licit_asigl0');
		}

		return  $subasta->addLowerBid(false, $type_asigl0);

	}


	#conservo la función por si hubiera que usar sockets desde js en algun cliente
	public function endLot()
	{
		$cod_sub  = request('params.cod_sub');
		$lot = request('params.lot');
		$cod_licit = request('params.cod_licit');
		$hash_user = request('params.hash');
		$jump_lot = request('params.jump_lot');

		return $this->endLotV2($cod_sub, $lot, $cod_licit, $hash_user, $jump_lot);
	}

	 public function endLotV2($cod_sub, $lot, $cod_licit, $hash_user, $jump_lot)
	 {
		\Log::info("end lot V2" );

        $gestor = new User();
        $gestor->cod = $cod_sub;
        $gestor->licit = $cod_licit;
        $g = $gestor->getUserByLicit();
         //si no se encuentra el licitador o el licitador no es gestor
        if(count($g) == 0 || $g[0]->tipacceso_cliweb != 'S'){
            $res = $this->error_puja('generic', $cod_licit, FALSE);
            return $res;

        }
        $hash = hash_hmac("sha256",$lot." ".$cod_sub." ". $cod_licit, $g[0]->tk_cliweb);

        if ($hash != $hash_user)
        {
             \Log::info("$hash == $hash_user" );
            return $this->error_puja('generic', $cod_licit, FALSE);
        }


        $subasta = new subasta();
        $subasta->cod   = $cod_sub;
        $subasta->lote = $lot;

        //se puede llamar a end lot sin querer cerrar el lote, ya que es un salto de lote
        if($jump_lot == 1){
            \Log::info("dentro de jump, lote actual: $lot" );
             $data['jump_lot']  = 1;
        }else{
            \Log::info("dentro de cerrar, lote actual:  $lot" );
			$subasta->cerrarLote();
			if(Config::get('app.WebServiceCloseLot')){

				$theme  = Config::get('app.theme');
				$rutaCloseLotcontroller = "App\Http\Controllers\\externalws\\$theme\CloseLotController";

				$closeLotController = new $rutaCloseLotcontroller();

				$closeLotController->createCloseLot($cod_sub,$lot);
			}
        }
        # Determinamos si cerramos el lote
        $id_auc_sessions = $subasta->getIdAucSessionslote($subasta->cod, $subasta->lote);
        $subasta->id_auc_sessions  = $id_auc_sessions;
        $lote = $subasta->getLote();
		$lote = head($lote);




        //Obtenemos el lote actual, el siguiente y el anterior.
        $subasta->lote = $subasta->orden = $lote->orden_hces1;

        $subasta->page = 1;
        $subasta->itemsPerPage = 1;

        $data['lote_anterior']  = $subasta->getLote('ORDEN_HCES1');

        $data['lote_anterior']  = head($subasta->getAllLotesInfo($data['lote_anterior']));

          /* saber session reference */

        $session_reference  = $subasta->get_reference_auc_session($id_auc_sessions);


        $subasta->lote = $subasta->orden = $lote->orden_hces1;
        $subasta->page = 1;
        $subasta->itemsPerPage = 1;
        $subasta->session_reference = $session_reference;

        $data['lote_actual']    = $subasta->getNextAvailableLote();

       //si ya no quedan lotes miramso de activar los que esten saltados ,J en cerrado_asigl0
        if(empty($data['lote_actual'])) {

            $SubastaTR      = new SubastaTiempoReal();
            $SubastaTR->cod = $cod_sub;
            $SubastaTR->reloadSaltarLotes(0);
            //volvemos a cargar el lote siguiente a ver si ahora hay alguno
            $data['lote_actual']    = $subasta->getNextAvailableLote();
        }

        if(!empty($data['lote_actual'])) {

				#si el importe de salida es 0 debemos cojer el primer escalado como puja válida
				if( $data['lote_actual'][0]->impsalhces_asigl0== 0){
					$data['lote_actual'][0]->impsalhces_asigl0 = head($subasta->AllScales())->scale;
				}


                //detectar probleams con el importe de salida si no cumple el escalado, modificamos tambien el importe de reserva.
                $escalado_correcto = $subasta->NextScaleBid(0,$data['lote_actual'][0]->impsalhces_asigl0 - 1, false);
                //not_force_correct_price_tiempo_real sirve que en tiempo real no fuerze el escalado correcto en la primer puja
                if($escalado_correcto != $data['lote_actual'][0]->impsalhces_asigl0 && \Config::get('app.force_correct_price') && ( empty(\Config::get('app.not_force_correct_price_tiempo_real'))  || \Config::get('app.not_force_correct_price_tiempo_real') != 1)){
                   $data['lote_actual'][0]->impsalhces_asigl0 = $escalado_correcto;
                    $data['lote_actual'][0]->impres_asigl0 = $escalado_correcto;
                }

            $data['lote_actual']    = head($subasta->getAllLotesInfo($data['lote_actual']));
            $data['lote_actual']->text_lang = $subasta->getMultilanguageTextLot($data['lote_actual']->num_hces1, $data['lote_actual']->lin_hces1);
            $data['lote_actual']    = $subasta->calculateStartBid($data['lote_actual']);

        }

        if (!empty($data['lote_actual']))
        {

			#poner autor del lote en tiempo real
			$data['lote_actual']->autor = "";
			if( \Config::get("app.AutorInTR")){

				$FgCaracteristicas = new 	FgCaracteristicas_Hces1();
				$autor =	$FgCaracteristicas->select("nvl(  VALUE_CARACTERISTICAS_VALUE,  value_caracteristicas_hces1 ) value_caracteristicas_hces1")->JoinCaracteristicas()->JoinCateristicasValue()->where("IDCAR_CARACTERISTICAS_HCES1",\Config::get("app.AutorInTR") )->where("NUMHCES_CARACTERISTICAS_HCES1", $data['lote_actual']->num_hces1)->where("LINHCES_CARACTERISTICAS_HCES1", $data['lote_actual']->lin_hces1)->first();
				if(!empty($autor)){
					$strLib = new StrLib();
					$data['lote_actual']->autor =  $strLib->CleanStr($autor->value_caracteristicas_hces1);
				}

			}


             $imageGenerate = new ImageGenerate();
             $data['lote_actual']->imagen = $imageGenerate->resize_img( "lote_medium_large", $data['lote_actual']->imagen, Config::get('app.theme'),true);

             # Asignamos el escalado para el próximo.
            if(isset($data['lote_actual']->max_puja->imp_asigl1) && is_numeric($data['lote_actual']->max_puja->imp_asigl1)) {
                $imp = $data['lote_actual']->max_puja->imp_asigl1;
            } else {
                $imp = $data['lote_actual']->impsalhces_asigl0;
            }
             if(!isset($data['lote_actual']->max_puja->imp_asigl1)){
                $subasta->sin_pujas = true;
            }
            $subasta->imp = $imp;

            $data['lote_actual']->formatted_actual_bid = \Tools::moneyFormat($data['lote_actual']->actual_bid);
           // $escalado = $subasta->escalado();

            //$data['lote_actual']->importe_escalado_siguiente = $escalado;
            $data['lote_actual']->importe_escalado_siguiente  = $subasta->NextScaleBid($data['lote_actual']->impsalhces_asigl0,$subasta->imp);
            $data['lote_actual']->siguiente  = $data['lote_actual']->importe_escalado_siguiente ;

           /*
            if($subasta->imp == $escalado)
            {
                $subasta->imp   = $subasta->imp + 1;
                $siguiente      = $subasta->escalado();
                $data['lote_actual']->importe_escalado_siguiente = $siguiente;
            }
            */

            $subasta->lote = $subasta->orden = $data['lote_actual']->orden_hces1;
            $subasta->page = 1;
            $subasta->itemsPerPage = 1;
            $subasta->session_reference = $session_reference;
            $refLoteSiguiente = $subasta->getNextPreviousLot("NEXT", $data['lote_actual']->orden_hces1, 'order','N');  // $subasta->getNextAvailableLote();
            if(!empty($refLoteSiguiente)){
                $subasta->lote = $refLoteSiguiente ;

                $lote_siguiente = $subasta->getLote();
               if (!empty($lote_siguiente)){

                   $data['lote_siguiente'] = head($subasta->getAllLotesInfo($lote_siguiente));

				   $data['lote_siguiente']->text_lang = $subasta->getMultilanguageTextLot( $data['lote_siguiente']->num_hces1,  $data['lote_siguiente']->lin_hces1);

                   //añadir funcion que envíe mail a usuario segun su favorito y posicion de este
                   //parametro que indica el numero de lotes que faltan para enviar mail
                   if (!empty(\Config::get('app.loteFavoritoProximo')) && \Config::get('app.loteFavoritoProximo') >= 0 ){

                       //obtener la referencia del lote
                       $refFavLote = $subasta->getNextPreviousLot("NEXT", $data['lote_anterior']->orden_hces1 + \Config::get('app.loteFavoritoProximo'), 'order','N');

                       \Log::info("Emails lotes favoritos:");
                       if(!empty($refFavLote)){

                           //si existe lote, buscar lo clientes que lo tienen como favorito
                           $cod_clies = Favorites::getCliFavs($cod_sub, $refFavLote);

                           if( !empty($cod_clies) && count($cod_clies) > 0){

                               foreach ($cod_clies as $cli) {

                                    $email = new EmailLib('LOT_FAVORITE');
                                    if (!empty($email->email)) {

                                        $email->setUserByCod($cli->cod_cli);
                                        $email->setLot($cod_sub, $refFavLote);
                                        $email->send_email();

                                        \Log::info("Mail enviado a : $cli->cod_cli");

                                    } else {
                                        \Log::info("email de lote favorito, No enviado, no existe o está deshabilitadio");
                                    }
                                }
                           }
                       }
                   }
               }
            }

        }
        else
        {
            $session_reference =  $subasta->get_reference_auc_session($id_auc_sessions);
            # Cerramos la subasta
            $SubastaTR          = new SubastaTiempoReal();
            $SubastaTR->cod     = $subasta->cod;
            $SubastaTR->estado  = 'ended';
            $SubastaTR->session_reference  = $session_reference;
            $SubastaTR->setStatus();

            $session = $subasta->get_session($id_auc_sessions);
            if (!empty($session)){
                $init_lot = $session->init_lot;
                $end_lot = $session->end_lot;
            }else{
                $init_lot = NULL;
                $end_lot = NULL;
            }
            # Cerramos todos los lotes de la subasta
            $SubastaTR->cerrarSubasta($init_lot, $end_lot);

            $data['subasta_finalizada'] = 1;
            return json_encode($data);
        }

    $subasta->lote = $data['lote_actual']->orden_hces1;
        $subasta->page = 1;
        $subasta->itemsPerPage = Config::get('app.distance_to_play_favs');

    $data['nextLotes'] = $subasta->getNextAlarmLotes();


        return json_encode($data);
        // return $data;

    }

     //lo dejo por si balclis se queja y lo quiere como estaba 09/02/2018
    public function calculateAvailableBids_copia($actual_bid, $new_bid)
    {
        $subasta = new subasta();
        $scaleRanges = $subasta->AllScales();

        $end = false;
        $scales = array();
        //marco un limite superior para no realizar calculos innecesarios
        $max_limit = max($new_bid * 1000, $actual_bid * 1000) ;

        $num_digits = strlen($new_bid);
        $i=0;
        $val = $actual_bid;
        // si ponene un rango muy grande no mostramos nada:
        if($new_bid > ($actual_bid * 1000) ){
            $end = true;
        }
        while (!$end){
            if($i >= count($scaleRanges)){
                $end = true;
            }
            elseif($val >= $scaleRanges[$i]->max){
                 $i++;
            }else{
                while ($val < $scaleRanges[$i]->max  && !$end){


                    $val +=$scaleRanges[$i]->scale;
                     //si el valor empieza por el mismo número lo recogemos
                    if ($new_bid == substr($val,0,$num_digits)) {
                       $scales[] = $val;
                    }
                    if(count($scales) >= 5 || $val >= $max_limit ){
                        $end = true;
                    }

                }
            }
        }

         return json_encode($scales);

    }

    #Calcula las posibles pujas para mostrar al usuario mediante el autocomplete.
	#02-08-2022 OJO, HE MODIFICADO LA FUNCION PARA QUE RECIBA LA PUJA SIGUIENTE EN VEZ DE ACTUAL_BID, YA QUE DABA FALLOS CON LA PRIMERA PUJA DE LOTE SI YA ESTABA PUJADO
    public function calculateAvailableBids($next_bid, $new_bid, $cod_sub = null)
    {
        $subasta = new subasta();
		if(empty($cod_sub)){
			$cod_sub = Input::get('cod_sub');
		}


        if(!empty($cod_sub)){
            $subasta->cod = $cod_sub;
        }

		if(!empty($cod_sub)){
			$fgsub = Fgsub::select("inversa_sub")->where("cod_sub",$cod_sub)->first();

			if( !empty($fgsub) && $fgsub->inversa_sub == 'S'){
				return $this->calculateAvailableInverseBids($next_bid, $new_bid,$cod_sub);
			}
		}

		$scaleRanges = $subasta->allScales();
        $end = false;
        $scales = array();
        //marco un limite superior para no realizar calculos innecesarios
        $max_limit = max($new_bid * 1000, $next_bid * 1000) ;

        $num_digits = strlen($new_bid);
        $i=0;
        $val = $next_bid;
        $seleccionado = NULL;
        // si ponene un rango muy grande no mostramos nada:
        if($new_bid > ($next_bid * 1000) ){
            $end = true;
        }
        while (!$end){
            if($i >= count($scaleRanges)){
                $end = true;
            }
            elseif($val >= $scaleRanges[$i]->max){
                 $i++;
            }else{
                while ($val < $scaleRanges[$i]->max  && !$end){


					if ($new_bid <= $val ) {
						$seleccionado = $val;
						$end = true;
					}else{
						$val = $subasta->NextScaleBid($next_bid,$val);

						if($val >= $max_limit ){
							$end = true;
						}
					}

                }
            }
        }
        if(!empty($seleccionado)){
            $propuesto = $seleccionado;
            $y=5;
            if($new_bid < $propuesto){
                $scales[] =$propuesto;
                $y=4;
            }
            for($i=0;$i<$y;$i++){
                $propuesto = $subasta->NextScaleBid($next_bid,$propuesto);
                $scales[] =$propuesto;

            }
        }
         return json_encode($scales);

    }


	public function calculateAvailableInverseBids($next_bid, $new_bid, $cod_sub = null)
    {
		\Log::info("inverse bids");
        $subasta = new subasta();


        if(!empty($cod_sub)){
            $subasta->cod = $cod_sub;
        }
		#si ponen un importe por encima del actual, devolvemos el listado de como máximo el actual
		if($new_bid > $next_bid){
			return  $this->calculateAvailableInverseBids($next_bid, $next_bid, $cod_sub);
		}

        $scaleRanges = $subasta->AllScales();

        $end = false;
        $scales = array();



        $i=count($scaleRanges)-1;
		#cogemos minimo po que el precio no puede ser mayor que el precio de salida, y si no lo hicieramos haría un buvle infinto
        $val = $new_bid;
        $seleccionado = NULL;
        // si ponene un rango muy grande no mostramos nada:
        if($new_bid > ($next_bid * 1000) ){
            $end = true;
        }
        while (!$end){

            if($i < 0){
                $end = true;
            }

            elseif($val <= $scaleRanges[$i]->min){

                 $i--;
            }else{

				$val = $scaleRanges[$i]->max;
                while ($val > $scaleRanges[$i]->min  && !$end){


					if ($new_bid >= $val ) {
						$seleccionado = $val;
						$end = true;
					}else{
						$LastVal = $val;
						$val = $subasta->NextScaleInverseBid($next_bid,$val);

						#si el ultimo numero se repite es por que nunca podemos llegar a 0
						if($val <0 || $LastVal == $val){
							$end = true;
						}
					}

                }
            }
        }

        if(!empty($seleccionado)){
            $propuesto = $seleccionado;
            $y=5;
            if($new_bid > $propuesto){
                $scales[] =$propuesto;
                $y=4;
            }
			$anterior = 0;
            for($i=0;$i<$y;$i++){
                $propuesto = $subasta->NextScaleInverseBid($next_bid,$propuesto);
				/*
				if($propuesto == 0){
					break;
				}
				*/
				#cuando llega a 0 devuelve siempre la última puja, por lo que si viene varias veces la ultima puja no hay que ponerla
				if($propuesto != $anterior){
					$scales[] =$propuesto;
				}
				$anterior = $propuesto;

            }
        }
         return json_encode($scales);

    }

	public function setStatus()
    {


        $cod_sub        = request('params.cod_sub');
        $status         = request('params.status');
        $reanudacion    = request('params.reanudacion');
        $minutes    = request('params.minutesPause');
        $cod_licit      = request('params.cod_licit');
        $hash_user      = request('params.hash');
        $id_auc_sessions =  request('params.id_auc_sessions');

		return $this->setStatusv2($cod_sub, $status, $reanudacion, $minutes, $cod_licit, $hash_user, $id_auc_sessions );
	}

    # SUBASTA
    # Seteamos el estado de la subasta y su fecha de reanudacion
    public function setStatusv2($cod_sub, $status, $reanudacion, $minutes, $cod_licit, $hash_user, $id_auc_sessions )
    {

        $SubastaTR      = new SubastaTiempoReal();

        $gestor = new User();
        $gestor->cod = $cod_sub;
        $gestor->licit = $cod_licit;
        $g = $gestor->getUserByLicit();

        //si no se encuentra el licitador o el licitador no es gestor
        if(count($g) == 0 || $g[0]->tipacceso_cliweb != 'S'){
            $res = $this->error_puja('generic', $cod_licit, FALSE);
            return $res;

        }
        if(!empty($reanudacion)){
            $hash = hash_hmac("sha256",$status." ".$cod_sub." ". $cod_licit . " " .$reanudacion, $g[0]->tk_cliweb);
        }else{
             $hash = hash_hmac("sha256",$status." ".$cod_sub." ". $cod_licit . " " .$minutes, $g[0]->tk_cliweb);
             $reanudacion = date("Y-m-d  H:i:s",time() + $minutes *60);
        }

        if ($hash == $hash_user)
        {
            $subasta = new subasta();
            $subasta->cod = $cod_sub;


            $SubastaTR->cod = $cod_sub;
            $SubastaTR->estado = $status;
            $SubastaTR->reanudacion = $reanudacion;

            $SubastaTR->session_reference  = $subasta->get_reference_auc_session($id_auc_sessions);
            //comprobamos si se ha iniciado la subasta, si no esta iniciada es que la estamos iniciando y el estado que se pasa es 'in_progress' modificamos todos los preciso a 0 de la subasta por el primer escalado
            if( $status == 'in_progress'){
                $estatus_actual = $SubastaTR->getStatus();
                if(count($estatus_actual) == 0){
                    $subasta->update_zero_price();
                }
            }


            $res = $SubastaTR->setStatus();

            if ( count($res) > 0)
            {
                //calculamos la puja inicial del siguiente lote
                $subasta->orden = 0;
                $subasta->page = 1;
                $subasta->itemsPerPage = 1;
                $subasta->session_reference = $SubastaTR->session_reference;
                $lote    = $subasta->getNextAvailableLote();

                if(!empty($lote)) {

                $lote   = head($subasta->getAllLotesInfo($lote));
                $lote    = $subasta->calculateStartBid($lote);
                if(count($lote->pujas)>0){
                    $res[0]->pujas = $lote->pujas;
                    $res[0]->importe_escalado_siguiente= $subasta->NextScaleBid($lote->impsalhces_asigl0,head($lote->pujas)->imp_asigl1);
                }

                }

                if( !empty($res[0]->reanudacion) ) {
                    $res[0]->reanudacion = strtotime($res[0]->reanudacion) - getdate()[0];
                }

               return json_encode($res[0]);
            }
            else
            {
               return "error";
            }

        }
        else
        {
             \Log::info("$hash == $hash_user" );
            return "error";
        }
    }


    # LOTE
    # Pausar un lote, únicamente en subasta en tiempo real
	public function pausarLote()
    {

        $cod_sub        = request('params.cod_sub');
        $cod_licit      = request('params.cod_licit');
        $hash_user      = request('params.hash');
        $ref            = request('params.ref');
		$ref_new_pos_lot                = request('params.ref_lot');

		$status = request('params.status');
		$this->pausarLoteV2( $cod_sub, $cod_licit,  $hash_user, $ref, $status, $ref_new_pos_lot);
	}

    public function pausarLoteV2( $cod_sub, $cod_licit,  $hash_user, $ref, $status, $ref_new_pos_lot = NULL)
    {
        $gestor = new User();
        $gestor->cod = $cod_sub;
        $gestor->licit = $cod_licit;
        $g = $gestor->getUserByLicit();

        //si no se encuentra el licitador o el licitador no es gestor
        if(count($g) == 0 || $g[0]->tipacceso_cliweb != 'S'){
            $res = $this->error_puja('generic', $cod_licit, FALSE);
            return $res;

        }
        $hash = hash_hmac("sha256",$ref." ".$cod_sub." ". $cod_licit, $g[0]->tk_cliweb);
        if ($hash != $hash_user)
        {
            \Log::info("$hash == $hash_user". "  datos: $ref ".$cod_sub." ". $cod_licit );
            return "error";
        }


        $SubastaTR                  = new SubastaTiempoReal();
        $subasta = new Subasta();
        $SubastaTR->cod             = $cod_sub;
        $SubastaTR->ref             = $ref;



        $SubastaTR->ref = $ref;
        if(empty($ref_new_pos_lot)) {
            return $SubastaTR->changeStatusLot($status);
        }else{

            $subasta->lote = $ref_new_pos_lot;
            $subasta->cod = $cod_sub;
            $exist_lot = $subasta->getLoteLight();

            if(empty($exist_lot)){
                 $result = array(
                            'status'    => 'error',
                            'msg_1'       => 'lot_not_found'
                            );
                 return $result;
            }

            $res_activeNext = $this->ActiveNext($cod_sub,$ref_new_pos_lot,$ref);
            $res = $res_activeNext['destino'];
            return $SubastaTR->changeStatusLot($status,$res);
        }

    }

    # Obtenemos la lista de lotes pausados solo para tiempo real
    public static function getLotesPausados($subasta)
    {
        /* 2017_10_30
         * Falta hacer una funcion que devuelva los lotes pausados ya que se ha eliminado la funcion getsubasta, este funcion debe devolver la información necesaria y nada mas.
         *
         */
        /*
        $subasta->estado_lotes = 'P';
         //El tiempo real debe estar ordenado por el orden en vez de por la referencia
        $subasta->order_by_values = "lotes.ORDEN_HCES1, lotes.REF_HCES1";
        $sub = $subasta->getSubasta();
        return $subasta->getAllLotesInfo($sub);

        */
        $subasta->order_by_values = "ASIGL0.REF_ASIGL0";
        $subasta->where_filter .= "AND \"id_auc_sessions\" =  $subasta->id_auc_sessions AND cerrado_asigl0='P'";

        $sub = $subasta->getLots();
        return $subasta->getAllLotesInfo($sub);
    }
    /* no se esta comprobando que el usuario sea gestor por si se le ha cerrado la sesion, pero habría que vuscar una manera de verificar que el usuario es gestor */
    public function setLicitLot()
    {
         $subasta = new Subasta();
         $subasta->licit =request('licit');
         $subasta->cod = request('cod_sub');

		$ministeryLicit = config('app.ministeryLicit', false);

        if ($subasta->checkLicitador(true)){

            $SubastaTR           = new SubastaTiempoReal();
            $SubastaTR->cod      = request('cod_sub');
            $SubastaTR->ref      = request('ref');
            $SubastaTR->licit    = request('licit');

			if($ministeryLicit && $SubastaTR->licit == $ministeryLicit){
				return response($this->assignToMinistery($SubastaTR->cod, $SubastaTR->ref), 200);
			}

            $SubastaTR->setLicitLot();
            return 'success';
        }else{
            return 'error';
        }
    }

	private function assignToMinistery($cod_sub, $ref) {

		$ministeryLicit = config('app.ministeryLicit', false);
		$actualWinner = FgAsigl1::where([
			['sub_asigl1', $cod_sub],
			['ref_asigl1', $ref]
		])->orderBy('lin_asigl1', 'desc')
		->first();

		if(!$actualWinner){
			return 'error-notbid';
		}

		$minsteryBid = $actualWinner->replicate()->fill([
			'licit_asigl1' => $ministeryLicit,
			'lin_asigl1' => $actualWinner->lin_asigl1 + 1,
			'fec_asigl1' => now()->format('Y-m-d H:i:s'),
			'date_update_asigl1' => now()->format('Y-m-d H:i:s'),
			'hora_asigl1' => now()->format('H:i:s'),
			'usr_update_asigl1' => 'WEB',
		]);

		FgAsigl1::create($minsteryBid->toArray());

		$email = new EmailLib('MINISTERY_AWARDED');
		if(!empty($email->email)){
			$email->setUserByLicit($cod_sub, $actualWinner->licit_asigl1, true);
			$email->setLot($cod_sub, $ref);
			$email->send_email();
		}

		return 'ministery';
	}

    public function openLot()
    {
        $cod_user = Session::get('user.cod');
        $admin_user = Session::get('user.admin');

        if (empty($cod_user) || empty($admin_user) ){
			$res = array(
				'status' => 'error',
				'msg' => 'session_end'
				);
				return $res;
        }
        $SubastaTR           = new SubastaTiempoReal();
        $SubastaTR->cod      = request('cod_sub');
        $SubastaTR->ref      = request('ref');
        $SubastaTR->orden      = request('orden');
        $deleteBids     = request('deletBids');

        $res_open = $SubastaTR->openLot();
        if ($res_open && !empty($deleteBids) && $deleteBids == 1 ){
           $res_open = $SubastaTR->deletBids();
        }

        if($res_open){

            $res = array(
            'status' => 'success',
            'msg' => 'open_lot_success'
            );
            return $res;
        }else{

            $res = array(
            'status' => 'error',
            'msg' => 'open_lot_error'
            );
            return $res;
        }


    }




    # Envio de emails de sobrepuja
    public function sendEmailSobrepuja($cod, $licit, $ref, $orden_o_puja )
    {

        $cod_email="";
        Log::info('dentro de send_email');
        //solo enviar el email de sobrepuja si así está definido.


        /*$cod      = Request::input('cod');
        $licit      = Request::input('licit');
        $lote       = Request::input('lote');
        */
        $subasta = new Subasta();

        $subasta->cod = $cod;
        $subasta->lote  = $ref;
        //cogemos los datos del lote para comprobar las fechas
        $lote = $subasta->getLote()[0];

        $no_enviar= false;

        $start_session = strtotime($lote->start);



        if(!isset($start_session) || empty($start_session) ){
            $no_enviar= true;
             Log::info('-----No hay sesión, no enviar email: ');

        }
		//Si la session ya ha empezado y no es de tipo "On line" o "Permanente" no debería enviar ya que estamos en tiempo real o la sesión ya ha acabado.
		//Si tienen el config send_email_tr, omitimos el resto
        elseif( time() > $start_session && (strtoupper($lote->tipo_sub) != 'O' && strtoupper($lote->tipo_sub) != 'P' ) && !Config::get('app.send_email_tr', 0)){
			$no_enviar= true;
			Log::info('-----La session ya ha empezado, no enviar email: ');
        } else {

           if($orden_o_puja == "orden")
            {

                $cod_email="OVER_ORDER";
                $params = array(
                    'emp'       =>  Config::get('app.emp'),
                    'numlote'   =>  $ref,
                    'subasta' => $cod
                    );
              //las ordenes telefonicas no se tienen en cuenta
                $sql = "select himp_orlic, sub_orlic, licit_orlic,tipop_orlic  FROM fgorlic
                    where sub_orlic=:subasta
                    and ref_orlic=:numlote
                    and emp_orlic=:emp
                    and tipop_orlic != 'T'
                    ORDER BY himp_orlic DESC,
                    fec_orlic ASC, hora_orlic ASC" ;
                $ordenes = DB::select($sql, $params);
                if(Count($ordenes)>1)
                {
                    // Si el primer licitador soy yo y el segundo no lo soy, y el segundo no hizo la licitación por telefono entonces enviamos
                    if($ordenes[0]->licit_orlic == $licit
                            && $ordenes[1]->licit_orlic!= $licit
                            && $ordenes[0]->himp_orlic >= $ordenes[1]->himp_orlic
                            )
                    {

                      $licit_envio = $ordenes[1]->licit_orlic;
                    }
                    elseif ($ordenes[0]->licit_orlic != $licit){
                        $licit_envio  = $licit;
                    }

                    else
                    {
                      $no_enviar= true;
                      $opt = print_r($ordenes, true);
                      Log::info('-----1 EMAIL SOBREORDEN NO ENVIAR: '.$opt);
                    }
                    $importe = $subasta->sobre_puja_orden($lote->impsalhces_asigl0, $ordenes[0]->himp_orlic,$ordenes[1]->himp_orlic);

                }
                else
                {
                  $no_enviar= true;
                      $opt = print_r($ordenes, true);
                      Log::info('-----2 EMAIL SOBREORDEN NO ENVIAR: '.$opt);
                }
            }
            else
            {

                $cod_email="OVER_BID";
                $params = array(
                    'emp'       =>  Config::get('app.emp'),
                    'numlote'   =>  $ref,
                    'subasta' => $cod
                    );
              //las puja telefonicas no se tienen en cuenta
                $sql = "select * from FGASIGL1
                    where SUB_ASIGL1 = :subasta
                    AND REF_ASIGL1 = :numlote
                    AND EMP_ASIGL1 = :emp
                    AND PUJREP_ASIGL1 != 'T'
                    ORDER BY IMP_ASIGL1 DESC,
                    FEC_ASIGL1 DESC, HORA_ASIGL1 DESC, LIN_ASIGL1 DESC" ;
                $pujas = DB::select($sql, $params);
                if(Count($pujas)>1)
                {
					// Si el primer licitador soy yo y el segundo no lo soy, y el segundo no hizo la licitación por telefono entonces enviamos
					#Añadida una segunda condicion, para que en caso de ser el admin quien realiza la puja por un licitador, ha este le llegue el email de sobrepuja en las ordenes automaticas.
					if(	($pujas[0]->licit_asigl1 == $licit && $pujas[1]->licit_asigl1!= $licit && $pujas[0]->imp_asigl1 > $pujas[1]->imp_asigl1)
						|| (!empty($this->cod_original_licit) && $this->cod_original_licit != $licit && $pujas[0]->imp_asigl1 > $pujas[1]->imp_asigl1)
                    )
                    {
                       $importe = $pujas[0]->imp_asigl1;
					  $licit_envio = $pujas[1]->licit_asigl1;
					  $pujaPerdedor = $pujas[1]->imp_asigl1;
                    }
                    /* este caso no tiene sentido en pujas
                    elseif ($pujas[0]->licit_asigl1 != $licit){
                        $licit_envio  = $licit;
                    }
					*/
					#Comentado y substituido por la segunda condicion del if superior
					/**Tauler quiere que en las sobrepujas automaticas también llegue correo al usuario, aunque este en pantalla en ese momento (es por las pujas hechas como administrador)
					elseif(config::get('app.email_sobrepuja_auto', 0) &&  $pujas[0]->imp_asigl1 >= $pujas[1]->imp_asigl1){
						$importe = $pujas[0]->imp_asigl1;
						$licit_envio = $pujas[1]->licit_asigl1;
					}
					*/
                    else
                    {
                      $no_enviar= true;
                      $opt = print_r($pujas, true);
                      Log::info('EMAIL SOBREPUJA NO ENVIAR: usuario esta actualmente online');
                    }
                }
                else
                {
                  $no_enviar= true;
                      $opt = print_r($pujas, true);
                      Log::info('-----2 EMAIL SOBREPUJAS NO ENVIAR: '.$opt);
                }

            }
        }


        if($no_enviar )
        {
            Log::info('-----4 no enviar: ');
          return;
        }
        $licitSubalia   = !empty(Config::get('app.subalia_min_licit'))? Config::get('app.subalia_min_licit') : 100000;
        Log::info("cod_licit: $licit_envio cod subalia: $licitSubalia");
        // si el licitador pertenece a subalia no enviamso el email.
        if($licit_envio >= $licitSubalia){
            Log::info('-----4 no enviar Licitador de Subalia: ');
          return;
        }
            if($orden_o_puja == "orden"){
                $theme_email = 'OVER_ORDER';
            }else{
                 $theme_email = 'OVER_BID';
            }
            $email = new EmailLib($theme_email);
            if(!empty($email->email)){
                $email->setUserByLicit($cod,$licit_envio,true);
                $email->setLot($cod, $ref);
				$email->setBid(\Tools::moneyFormat($importe));
				if(!empty($pujaPerdedor)){
					$email->setAtribute("PUJA_PERDEDOR", $pujaPerdedor);
				}
                $email->send_email();
            }


    }

	public function  cancelarOrden()
    {
		return $this->cancelarOrdenV2(request('params.cod_sub'), request('params.cod_licit'), request('params.ref'), request('params.hash'));
	}

	public function  cancelarOrdenV2($codSub, $licit,  $ref, $hash_user)
    {

        $subasta = new Subasta();
        $subasta->cod     = $codSub;


        $subasta->ref     = $ref;


        $user  = new User();
        $user->cod   = $subasta->cod;
        $user->licit = $licit;
        $u = $user->getUserByLicit();
        //el usuario debe ser gestor
        if (count($u) == 0 || $u[0]->tipacceso_cliweb != 'S'){
                $res = $this->error_puja('generic', $licit, true);
            return $res;
        }

        $subasta->is_gestor = true;
        $tk = $u[0]->tk_cliweb;
        $hash = hash_hmac("sha256",$licit ." ".$subasta->cod." ". $subasta->ref, $tk);
        //comprobamos que el hash sea correcto
        if($hash != $hash_user){
            $res = $this->error_puja('generic', $licit, $subasta->is_gestor );
            return $res;
        }
        $subasta->page      = 'all';
        $ordenes = $subasta->getOrdenes();

         if(count($ordenes) == 0 ){
             $res = $this->error_puja('not_order', $licit, $subasta->is_gestor );
             return $res;
        }
        //buscamos la ultima orden añadida
        $orden = $ordenes[0];

        foreach($ordenes as $key => $ord){
            if(strtotime($ord->fec_orlic) > strtotime($orden->fec_orlic)){
                $orden = $ordenes[$key];
            }
        }



        $subasta->imp = $orden->himp_orlic;
        $licit_delete = $orden->cod_licit;
        $subasta->cancelarOrden($licit_delete);
        $ordenes_actuales = $subasta->getOrdenes();



        $res = array();
        $res['status'] = "success";
        $res['licit_delete'] = $licit_delete;
        $res['licit'] = $licit;
        $res['msg_response'] = 'cancel_order_response';
        $res['msg_delete'] = 'delete_order';
        $res['ordenes'] = $ordenes_actuales;
        return $res;

	 }

	public function  cancelarOrdenUser()
	{

		return  $this->cancelarOrdenUserV2(request('params.cod_sub'), request('params.ref'), request('params.cod_licit'), request('params.hash'));
	}

	public function  cancelarOrdenUserV2($codSub, $ref, $licit, $hash_user)
	{
		$subasta = new Subasta();
		$subasta->cod = $codSub;
		$subasta->ref = $ref;


		 Log::info('cancelar orden User subasta:'.  $subasta->cod . " referencia: "  . $subasta->ref. " licitador: ". $licit  );

		 $user  = new User();
		 $user->cod   = $subasta->cod;
		 $user->licit = $licit;
		 $u = $user->getUserByLicit();
		 //el usuario debe existir
		 if (count($u) == 0 ){
				 $res = $this->error_puja('generic', $licit, true);
			 return $res;
		 }


		 $tk = $u[0]->tk_cliweb;
		 $hash = hash_hmac("sha256",$licit ." ".$subasta->cod." ". $subasta->ref, $tk);
		 //comprobamos que el hash sea correcto
		 if($hash != $hash_user){
			 $res = $this->error_puja('generic', $licit, "N" );
			 return $res;
		 }
		 $subasta->page      = 'all';
		 $ordenes = $subasta->getOrdenes(" AND licit_orlic =  '$licit' ");

		  if(count($ordenes) == 0 ){
			  $res = $this->error_puja('not_order', $licit, "N" );
			  return $res;
		 }

		 //buscamos la ultima orden añadida
		 $orden = $ordenes[0];

		 $subasta->imp = $orden->himp_orlic;
		 $licit_delete = $orden->cod_licit;

		 $subasta->cancelarOrden($licit);
		 $ordenes_actuales = $subasta->getOrdenes();



		 $res = array();
		 $res['status'] = "success";
		 $res['licit_delete'] = $licit;
		 $res['licit'] = 0;//lo ha ejecutado el usuario, no el administrador, no queremso que se muestren dos mensajes
		 $res['msg_response'] = 'cancel_order_response';
		 $res['msg_delete'] = 'delete_order';
		 $res['ordenes'] = $ordenes_actuales;
		 return $res;

	  }

	public function cancelarPuja()
	{
		$codSub = request('params.cod_sub');
		$codLicit = request('params.cod_licit');
		$ref  = request('params.ref');
		$hash= request('params.hash');

		return $this->cancelarPujaV2($codSub, $codLicit, $ref, $hash);
	}

    public function cancelarPujaV2($codSub, $codLicit, $ref,  $hash_user)
    {
        $subasta = new Subasta();
        $subasta->cod     = $codSub;
        $licit            = $codLicit;

        $subasta->ref     =$ref;
        $subasta->lote     = $subasta->ref;


        $user  = new User();
        $user->cod   = $subasta->cod;
        $user->licit = $licit;

        $u = $user->getUserByLicit(true);
        //si no existe el licitador
        if (count($u) == 0 ){
                $res = $this->error_puja('generic', $licit, true);
            return $res;
        }

        $subasta->licit = $licit;
        //Baja cliente automatico si no es administrador
        if(!empty(Config::get('app.automatic_blocking_licit_cancel_bids')) && $u[0]->tipacceso_cliweb!= 'S' ){
            \log::info("bloquear usuario");
            $this->automaticBlockingLicit($subasta);
        }

       //comprobamos que el hash sea correcto
        $tk = $u[0]->tk_cliweb;
        $hash = hash_hmac("sha256",$licit ." ".$subasta->cod." ". $subasta->ref, $tk);

        if($hash != $hash_user){
            $res = $this->error_puja('generic', $licit, $subasta->is_gestor );
            return $res;
        }

         $lote_tmp = $subasta->getLote();
        //comprobamos que exista un lote con esos datos
        if (count($lote_tmp) == 0){
            $res = $this->error_puja('generic', $subasta->licit, $subasta->is_gestor);
            return $res;
        }
        //cogemos el precio inicial del lote
        $lote = head($lote_tmp);
        $escalado_correcto = $subasta->NextScaleBid(0,$lote->impsalhces_asigl0 - 1, false);

        //detectar probleams con el importe de salida si no cumple el escalado, modificamos tambien el importe de reserva.
        //not_force_correct_price_tiempo_real sirve que en tiempo real no fuerze el escalado correcto en la primer puja
        if($escalado_correcto != $lote->impsalhces_asigl0 && \Config::get('app.force_correct_price') && ( empty(\Config::get('app.not_force_correct_price_tiempo_real'))  || \Config::get('app.not_force_correct_price_tiempo_real') != 1)){
            $lote->impsalhces_asigl0 = $escalado_correcto;
            $lote->impres_asigl0 = $escalado_correcto;
        }
        $imp_salida = $lote->impsalhces_asigl0;
        /* 2017_10_10 LO QUITO POR QUE DE MOMENTO SE DEBE DEJAR PUJAR AUNQUE HAYA PRECI ODE RESERVA
        if ($lote->tipo_sub == 'W' && $lote->impres_asigl0 > 0 ){
           $imp_salida = max($lote->impres_asigl0,$lote->impsalhces_asigl0);
        }else{
           $imp_salida = $lote->impsalhces_asigl0;
        }
        */
        $subasta->page      = 'all';
        $pujas = $subasta->getPujas();

        if(count($pujas) == 0 ){
             $res = $this->error_puja('not_bid', $licit, $subasta->is_gestor );
             return $res;
        }
        $puja = $pujas[0];



       //si el usuario no es gestor, solo puede cancelar su puja si es la última
       if($u[0]->tipacceso_cliweb != 'S' && $puja->cod_licit != $licit){

            $res = $this->error_puja('no_cancel_bid', $licit, $subasta->is_gestor );
            return $res;
       }
       $ordenes_actuales = $subasta->getOrdenes();
       if ($u[0]->tipacceso_cliweb == 'S'){
        $subasta->is_gestor = true;
        //si el usuario no es gestor, se debe borrar tambien su orden si es la ultima activa
       }else{
           $subasta->is_gestor = false;

            if(count($ordenes_actuales) >0 ){
               $orden = $ordenes_actuales[0];
               if($orden->cod_licit == $licit){
                    Log::info('cancelar orden por usuario');

                   $subasta->imp = $orden->himp_orlic;
                   $licit_delete = $orden->cod_licit;
                   $subasta->cancelarOrden($licit_delete);
                   $ordenes_actuales = $subasta->getOrdenes();
               }
           }
       }

        $subasta->imp = $puja->imp_asigl1;
        $licit_delete = $puja->cod_licit;
        $subasta->cancelarPuja($licit_delete);

        //volvemos a mirar las pujas despues del borrado
        $pujas_actuales =  $subasta->getPujas();
        if(count($pujas_actuales)>0){
            $imp_actual = $pujas_actuales[0]->imp_asigl1;
            $actual_licit = $pujas_actuales[0]->cod_licit;
        }else{
            $imp_actual = $imp_salida;
            $subasta->sin_pujas = true;
            $actual_licit = NULL;
        }
        $imp_siguiente = $subasta->NextScaleBid($imp_salida,$imp_actual);
        $res = array();
        $res['status'] = "success";
        $res['actual_bid'] = $imp_actual;
        $res['actual_licit'] = $actual_licit;
        $res['formatted_actual_bid'] = \Tools::moneyFormat($imp_actual);
        $res['importe_escalado_siguiente'] = $imp_siguiente;
        $res['siguiente'] = $imp_siguiente;
        $res['licit_delete'] = $licit_delete;
        $res['licit'] = $licit;
        $res['msg_response'] = 'cancel_bid_response';
        $res['pujas'] = $pujas_actuales;
        $res['ordenes'] = $ordenes_actuales;
        $res['msg_delete'] = 'delete_bid';
       // $res = $this->error_puja("todo ok", $subasta->licit, true);
        return $res;

    }

    public function addPuja($cod_subasta, $ref_lote, $cod_licit, $importe, $type_bid)
    {
        $subasta = new Subasta();
        $subasta->cod            = $cod_subasta;
        $subasta->ref            = $ref_lote;
        $subasta->licit          = $cod_licit;
        $subasta->imp            = $importe;
        $subasta->type_bid       = $type_bid;

        return $subasta->addPuja();
    }

    public function error_puja($msg, $cod_licit, $is_gestor){

            $res = array(
                'status' => 'error',
                'msg_1' => $msg,
                'cod_licit_actual' => $cod_licit,
                'no_interrupt_cd_time' => 'true'
            );

            if (!empty($is_gestor))
            {
                $res['is_gestor'] = TRUE;
            }

            return $res;

    }

    public function ActiveNext($cod_sub = null,$ref_actual = null,$ref = null){

        $val_return = false;
        $subasta = new Subasta();
        $SubastaTR      = new SubastaTiempoReal();

        if(!empty($cod_sub) && !empty($ref_actual) && !empty($ref)){
            $subasta->cod=$cod_sub;
            $subasta->ref            = $ref;
            $ref_actual = $ref_actual;
        }else{
            $subasta->cod            = Request::input('codsub');
            $subasta->ref            = Request::input('ref');
            $ref_actual = Request::input('ref_actual');
        }


        $subasta->lote =  $subasta->ref;
        $SubastaTR->cod = $subasta->cod ;
        $SubastaTR->ref = $subasta->ref ;

        $destino = $SubastaTR->getOrderFromRef($ref_actual);
        $origen = $SubastaTR->getOrderFromRef($subasta->ref);

        //Inf lote
        $actual_lot = $subasta->getLoteLight();

        $SubastaTR->num = $actual_lot->num_hces1;
        $SubastaTR->lin = $actual_lot->lin_hces1;

        //Si existe modificamos posiciones
        if(!empty($destino)){
            $val_return = $SubastaTR->moveLots($origen->orden_hces1,$destino->orden_hces1);
        }
        if($val_return == true){
            $res = array(
                'status' => 'success',
                'msg' => 'success_move_lot',
                'destino'   => $destino->orden_hces1,
            );
        }else{
            $res = array(
                'status' => 'error',
                'msg' => 'generic',
            );

        }

        return $res;

    }


    //Funcion para poner los lotes en Saltados (J)
    public function jumpLots(){
        $SubastaTR      = new SubastaTiempoReal();
        $SubastaTR->cod = Request::input('codsub');

        $subasta = new Subasta();
        $subasta->cod            = Request::input('codsub');
        $ref   = Request::input('ref');
        $ref_actual = Request::input('ref_actual');
        $subasta->lote            = $ref;
        $actual_lot = $subasta->getLoteLight();

        if(empty($actual_lot)){
             $res = array(
                 'status' => 'error',
                 'msg' => 'generic',
             );
             return $res;
        }
        $jumpToOrder = (int)$actual_lot->orden_hces1;

        $subasta->lote            = $ref_actual;
        $actual_lot = $subasta->getLoteLight();
        if(empty($actual_lot)){
          $res = array(
              'status' => 'error',
              'msg' => 'generic',
          );
          return $res;
        }
        $actualOrder = (int)$actual_lot->orden_hces1;

        //Si el lote que quiero ir es más grande que el que estamos pondremos J en CERRADO_ASIGL0
        if($actualOrder < $jumpToOrder){
          $jumpToOrder--;
          $val_return = $SubastaTR->saltarLotes($actualOrder,$jumpToOrder);

        //Si el lote que estamos es más grande el lote que queremos ir quitaremos J en CERRADO_ASIGL0 y pondremos N
        }elseif($actualOrder > $jumpToOrder){

            $val_return = $SubastaTR->reloadSaltarLotes($jumpToOrder);

        }

        if(!$val_return){
            $res = array(
                 'status' => 'error',
                 'msg' => 'generic',
             );

        }else{
            $res = array(
             'status' => 'success',
            );
        }
        return $res;


    }

    public function bajaCli($cod_sub = null, $cli_licit = null, $status = null){

        $user = new User();

        if(!empty($cod_sub) && !empty($cli_licit)){
            $user->cod = $cod_sub;
            $user->licit = $cli_licit;
            $alta_cli = $status;
        }else{
            $user->cod = Request::input('cod_sub');
            $user->licit = Request::input('cli_licit');
            $alta_cli = Request::input('alta_cli');
        }
        $user_licit = $user->getCodLicit();
        if(empty($user_licit)){
            $res = array(
                    'status' => 'success',
                    'msg' => 'cli_licit_dont_exist',
                   );
                  return $res;
        }
        $user->cod_cli = $user_licit[0]->cli_licit;
        $user_cli = $user->getUserByCodCli('N');
        if(empty($user_cli)){
            $user_cli = $user->getUserByCodCli('S');
        }

        if(empty($user_cli)){
               $res = array(
                 'status' => 'success',
                 'msg' => 'cli_licit_dont_exist',
                );
               return $res;
          }

        try {

             if($user_cli[0]->baja_tmp_cli == 'N'){

                //Pondremos de baja
                $user->BajaTmpCli($user_cli[0]->cod_cli,'S',date("Y-m-d H:i:s"),'W');
                $res = array(
                    'status' => 'success',
                    'msg' => 'user_baja_tmp',
                   );

             }else{
                  if(!empty($alta_cli) &&  $alta_cli == true){
                        $user->BajaTmpCli($user_cli[0]->cod_cli,'N',date("Y-m-d H:i:s"),'W');
                        $res = array(
                            'status' => 'success',
                            'msg' => 'user_dontbaja_tmp',
                       );
                  }else{
                      $res = array(
                        'status' => 'success_cli_baja',
                        'value' => $user->licit
                       );
                  }
             }

             return $res;

       }catch (\Exception $e) {
            \Log::emergency('Error baja cli:'.print_r($e->getMessage(),true));

             $res = array(
                'status' => 'error',
                'msg' => 'generic',
            );
              return $res;
        }



    }

    //Baja cliente automatico
    public function automaticBlockingLicit($subasta){
         $canelPujas = $subasta->getCancelarPuja(true);

         if(Config::get('app.automatic_blocking_licit_cancel_bids') <= $canelPujas){
            $this->bajaCli($subasta->cod,$subasta->licit);
        }
    }

    public function getBajaCliSub(){
        $user = new User();
        $user_baja = array();
        $user->cod_sub = Request::input('cod_sub');
        $user_baja= $user->getLicitsCodsub('S');

        return $user_baja;
    }

#en caso de que sea una autopuja, recibiremso tambien el importe de  la orden que la defiende
    public function email_bid_confirmed($subasta,$result,$type_puja = 'NORMAL', $impOrder = null){
        try{
            if(empty($result) || $result['status'] != 'success'){
                return;
			}

            $user = new User();
            if(empty($subasta->lote)){
                $subasta->lote = $subasta->ref;
            }

            $inf_lot = head($subasta->getLote());
            //En sala tener en cuenta usuario que no tienen cliweb o dummy
            if(empty($inf_lot) || ($inf_lot->tipo_sub == 'V' && $inf_lot->tipo_sub == 'W')){
                return;
			}

            $start_session = strtotime($inf_lot->start);

            if(!isset($start_session) || empty($start_session) ){

                 Log::info('-----No hay sesión, no enviar email: ');
                  return;
            }
			//Si la session ya ha empezado y no es de tipo "On line" o "Permanente" no debería enviar ya que estamos en tiempo real
			//o la sesión ya ha acabado, excepto si tienen config send_email_tr.
            elseif(time() > $start_session && (strtoupper($inf_lot->tipo_sub) != 'O' && strtoupper($inf_lot->tipo_sub) != 'P' && !Config::get('app.send_email_tr', 0))){
				Log::info('-----La session ya ha empezado, no enviar email: ');
				return;
			}

            $user->cod = $subasta->cod;
            $user->licit = $subasta->licit;

            $inf_user = head($user->getUserByLicit());

            if(empty($inf_user) || empty($inf_user->email_cliweb)){
                return;
			}

			if(Config::get('app.send_email_lot_increment_bid_to_all_users_with_deposit', 0)){
				//enviar email a todos los usuarios que tengan un deposito valido por este lote
				(new MailController())->sendLotIncrementBidToAllUsersWithDepositNotification($subasta->cod, $subasta->ref, $subasta->licit);
			}

			$inf_lot_translate = $subasta->getMultilanguageTextLot($inf_lot->num_hces1,$inf_lot->lin_hces1);
            $img = Config::get('app.url').'/img/load/lote_medium/'.Config::get('app.emp').'-'.$inf_lot->numhces_asigl0.'-'.$inf_lot->linhces_asigl0.'.jpg';

			$email = null;
			if ($type_puja == 'AUTOMATICA'){
				$email = new EmailLib('BID_MADE_AUTOMATICA');
				$email->setAtribute("ORDER",$impOrder);
			}
			if (empty($email->email)){
				$email = new EmailLib('BID_MADE');
			}

			if(!empty($email->email)){
				$email->setUserByLicit($subasta->cod, $subasta->licit, true);
				$email->setLot($subasta->cod, $subasta->ref);
				$email->setBid(\Tools::moneyFormat($subasta->imp));

				if(Config::get('app.email_bid_withdatebid', 0)){

					$date = FgAsigl1::select('fec_asigl1')->where([
						['sub_asigl1', $subasta->cod],
						['ref_asigl1', $subasta->ref],
						['licit_asigl1', $subasta->licit],
						['imp_asigl1', $subasta->imp]
					])->value('fec_asigl1');

					$date = Tools::getDateFormat($date, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
					$email->setBidDate($date);
				}
				if(Config::get('app.email_order_to_admin')){
					$email->setBcc(Config::get('app.admin_email'));
				}
				if(Config::get('app.email_bid_to_notiemails')){
					$notiEmails = FgSub::select("NOTIEMAILS_SUB")->where("COD_SUB", $subasta->cod)->first();

					if(!empty($notiEmails) && !empty($notiEmails->notiemails_sub)){
						$emailsEnCopia = explode(";",$notiEmails->notiemails_sub);
						foreach($emailsEnCopia as $emailEnCopia) {

							$email->setBcc(trim($emailEnCopia));
						}
					}
					$email->setBcc(Config::get('app.admin_email'));
				}
				$email->send_email();

				//Para asegurar que pasa por el siguiente if en caso de que sea necesario
				if(!Config::get('app.emails_to_owner')){
					return;
				}

			}

			/**
			 * Eloy: Para Calrandia
			 * Se enviara copia al porpietario si config y el valor de la puja esta en un porcentaje bajo el precio de reserva.
			 * Envio todo el contenido del $email, para no tener que volver a buscar la infomración del lote
			 */
			if(Config::get('app.emails_to_owner') && ($subasta->imp / $inf_lot->impres_asigl0) >= Config::get('percent_to_send_email', 0.9) && $subasta->imp < $inf_lot->impres_asigl0){

				if(empty($email->email)){
					$email->setLot($subasta->cod, $subasta->ref);
					$email->setBid($subasta->imp);
				}

				if(config('app.carlandiaCommission', 0)){
					$email->subtractCommissionToAttributes();
					$bid = Tools::moneyFormat($subasta->imp / (1 + config('app.carlandiaCommission')), false, 2);
					$email->setBid($bid);
				}

				if(Config::get('app.email_order_to_admin')){
					$email->setBcc(Config::get('app.admin_email'));
				}

				$result = (new MailController)->sendToOwner('BID_MADE_OWNER', $email, $inf_lot->num_hces1, $inf_lot->lin_hces1);

				return;

			}


			if(!Config::get('app.email_bid_confirmed')){
				return;
			}

            if(!empty($inf_lot->webfriend_hces1) && !empty($inf_lot_translate[$inf_user->idioma_cli]->webfriend_hces1)){
                $url_friendly = str_slug($inf_lot_translate[$inf_user->idioma_cli]->webfriend_hces1);
            }else{
                $url_friendly = str_slug($inf_lot_translate[$inf_user->idioma_cli]->titulo_hces1);
            }
            \App::setLocale(strtolower($inf_user->idioma_cli));


            $link = Config::get('app.url').\Routing::translateSeo('lote').$inf_lot->sub_asigl0."-".$inf_lot->id_auc_sessions.'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
            $utm_email = '';
            if(!empty(Config::get('app.utm_email'))){
                $utm_email = Config::get('app.utm_email').'&utm_campaign=puja_confirmada';
            }

            $fecha = strftime('%d %b',strtotime($inf_lot->ffin_asigl0));
            if(\App::getLocale() != 'en'){
                $array_fecha = explode(" ",$fecha);
                $array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans(\Config::get('app.theme')."-app.global.month_large"));
                $fecha = $array_fecha[0].' '.$array_fecha[1];
            }
            $fecha.=' '.strftime('%H:%M',strtotime($inf_lot->hfin_asigl0)).'h';

            $emailOptions['UTM'] = $utm_email;
            $emailOptions['to'] = trim($inf_user->email_cliweb);
            $emailOptions['subject'] = trans(\Config::get('app.theme').'-app.emails.subject_puja_confirmada').' '.Config::get('app.name');
            $emailOptions['user'] = $inf_user->nom_cliweb;

            $content = new \stdClass();
            $content->title = trans(\Config::get('app.theme').'-app.emails.title_puja_confirmada');
            $content->text = trans(\Config::get('app.theme').'-app.emails.text_puja_confirmada');
            $content->final_text_up_button = trans_choice(\Config::get('app.theme').'-app.emails.finaltext_puja_confirmada',1,['date'=>$fecha]);
            $content->button = trans(\Config::get('app.theme').'-app.emails.button_puja_confirmada');
            $content->hide_thanks =  true;
            $content->url_button = $link;
            $emailOptions['content'] = $content;

            $email_lot = new \stdClass();
            $email_lot->desc = $inf_lot_translate[$inf_user->idioma_cli]->desc_hces1;
            $email_lot->ref = $inf_lot->ref_asigl0;
            $email_lot->img = $img;
            $email_lot->imp_bid = \Tools::moneyFormat($subasta->imp,false);

            $emailOptions['lot'][] = $email_lot;

            if (\Tools::sendMail('emails_automaticos', $emailOptions) == true){
                \Log::emergency('Success email_bid_confirmed');
            }


       }catch (\Exception $e) {
            \Log::emergency('Error email_bid_confirmed');
            \Log::emergency($e->getMessage());
            return;
        }
   }



   /******* NUEVAS FUNCIONES PARA EL TIEMPO REAL    *********/
    //devolvemos un html con los lotes vendidos de la subasta
    public function historicTab($lang, $codsub, $session){
        $subastaObj = new Subasta();
        $subastaObj->page      = 'all';
        $subastaObj->cod = $codsub;
        $id_auc_sessions = $session;
        $subastaObj->select_filter = 'cod_sub, "name","id_auc_sessions", ref_asigl0, NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1) DESC_HCES1,himp_csub, NVL(HCES1_LANG.WEBFRIEND_HCES1_LANG, HCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1, NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.TITULO_HCES1) TITULO_HCES1, num_hces1,lin_hces1';
        $subastaObj->where_filter .= "AND CERRADO_ASIGL0 = 'S' AND \"id_auc_sessions\" =  $id_auc_sessions ";
        $subastaObj->order_by_values ="ref_asigl0 DESC";

        $lotes = $subastaObj->getLots("small");

        $contents = "";

        foreach($lotes as $lote){
            //$webfriend = !empty($lote->webfriend_hces1)? $lote->webfriend_hces1 :  str_slug($lote->titulo_hces1);
            //$lote->url_friendly = \Routing::translateSeo('lote').$lote->cod_sub."-".str_slug($lote->name).'-'.$lote->id_auc_sessions."/".$lote->ref_asigl0.'-'.$lote->num_hces1.'-'.$webfriend;
            //$lote->img =  Config::get('app.emp').'-'.$lote->num_hces1. '-' .$lote->lin_hces1.'.jpg';
            ///img/load/lote_medium/{{ $lot->imagen }}

              $contents .= view('includes.tr.tabs_tr_lot',array('lot' => $lote))->render();
           }
           $res = array(
               "status" => "success",
               "html"   => $contents
           );
        return $res;
    }

    public function adjudicadosTab($lang, $codsub, $session, $licit){
        $user = new User();

        $lotes = $user->getAllAdjudicacionesSessionAllInfo($codsub, $session, $licit);
        $contents = "";
        foreach($lotes as $lote){
            //$webfriend = !empty($lote->webfriend_hces1)? $lote->webfriend_hces1 :  str_slug($lote->titulo_hces1);
            //$lote->url_friendly = \Routing::translateSeo('lote').$lote->cod_sub."-".str_slug($lote->name).'-'.$lote->id_auc_sessions."/".$lote->ref_asigl0.'-'.$lote->num_hces1.'-'.$webfriend;
            $contents .= view('includes.tr.tabs_tr_lot',array('lot' => $lote))->render();
           }
           $res = array(
               "status" => "success",
               "html"   => $contents
           );
        return $res;
    }

    //devolvemos un html con lso lotes favoritos del usuario en esa subasta
    public function favoritesTab($lang, $cod_sub, $licit){

        $fav  = new Favorites(false, false);

        $fav->list_licit = "'". $cod_sub ."-". $licit. "'";

        $fav->page  = 'all';

        $favs = $fav->getFavsByLicits();
        if($favs["status"] != "success"){
            return array(
                "status" => "error"
            );
        }

        $contents = "";
        foreach($favs["data"] as $fav){
            $lote = new \stdClass();
            $webfriend = !empty($fav->webfriend_hces1)? $fav->webfriend_hces1 :  str_slug($fav->titulo_hces1);
            $lote->url_friendly = \Routing::translateSeo('lote').$fav->cod_sub."-".str_slug($fav->name).'-'.$fav->id_auc_sessions."/".$fav->ref_asigl0.'-'.$fav->num_hces1.'-'.$webfriend;
            $lote->img =  Config::get('app.emp').'-'.$fav->num_hces1. '-' .$fav->lin_hces1.'.jpg';
            $lote->ref_asigl0 = $fav->ref_asigl0;
            $lote->desc_hces1 = $fav->desc_hces1;
            $lote->formatted_impsalhces_asigl0 = $fav->formatted_impsalhces_asigl0;
            $contents .= view('includes.tr.tabs_tr_favorites',array('lot' => $lote))->render();
        }
        $res = array(
               "status" => "success",
               "html"   => $contents
           );
        return $res;
	}

	public function getAwardPrice($cod_sub, $ref_asigl0){

		$awardPrice = FgAsigl0::JoinCSubAsigl0()->JoinFghces1Asigl0()->select('HIMP_CSUB', 'CERRADO_ASIGL0', 'COMPRA_ASIGL0', 'REMATE_ASIGL0', 'IMPLIC_HCES1')->where('REF_ASIGL0', $ref_asigl0)->where('SUB_ASIGL0', $cod_sub)->first();
		$data = [];

		//vendido
		if(!empty($awardPrice->himp_csub) ){
			$data['html'] = $awardPrice->remate_asigl0 == 'S' ? trans(Config::get('app.theme').'-app.sheet_tr.awarded_for', ['price' => Tools::moneyFormat($awardPrice->himp_csub, ' ' .trans(Config::get('app.theme').'-app.lot.eur'))]) : trans(Config::get('app.theme').'-app.sheet_tr.awarded');
			$data['purchasable'] = false;
		}
		//no cerrado
		else if($awardPrice->cerrado_asigl0 != 'S'){
			$data['html'] = $awardPrice->implic_hces1 ? trans(Config::get('app.theme').'-app.lot.current_bid') . ': ' . Tools::moneyFormat($awardPrice->implic_hces1, ' ' . trans(Config::get('app.theme').'-app.lot.eur')) : trans(Config::get('app.theme').'-app.lot_list.no_bids');

			$data['purchasable'] = false;
		}
		//no vendido y posibilidad de compra
		else{
			$data['html'] = trans(Config::get('app.theme').'-app.sheet_tr.not_awarded');
			$data['purchasable'] = $awardPrice->compra_asigl0 == 'S' ?  true : false;
		}

		return response($data, 200);

	}

}
