<?php
namespace App\Http\Controllers\V5;
use App\Http\Controllers\Controller;

use App\Http\Controllers\subastaTiempoRealController;
use App\Http\Controllers\ChatController;
use Session;
use App\Models\V5\FgLicit;

use ElephantIO\Engine\SocketIO\Version2X;
use ElephantIO\Engine\Socket\SecureOptionBuilder;

class NodePhp extends Controller
{

public function actionV2()
    {

		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}


		$codSub = request('cod_sub');
        $ref = request('ref');
        //si el usuario es administrador debemos mirar este código ya que elcod_licit se machaca
	//	$cod_original_licit = request('cod_original_licit');


		$imp = intval(request('imp'));
        $type_bid = request("type_bid","W");

        $can_do                  = request('can_do');
        $tipo_puja_gestor        = request('tipo_puja_gestor');

		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();


		# si es usuario administrador puede haber realizado la puja por otra persona
		if (Session::has('user.admin')){
			$codLicitPeticion =$Fglicit->cod_licit;
			#si viene licitador es que ha pujado por otro, si no ponemos el mismo
			$codLicitPuja = request('cod_licit',$codLicitPeticion);
		}else{
			$codLicitPeticion =$Fglicit->cod_licit;
			$codLicitPuja = $Fglicit->cod_licit;
		}

		$subastaTiempoRealController = new subastaTiempoRealController();

		$hash_user =  hash_hmac("sha256",$codLicitPuja ." ". $codSub ." ". $ref ." ". $imp, Session::get('user.tk'));

		$res =  $subastaTiempoRealController->executeAction($codSub, $ref, $codLicitPuja, $codLicitPeticion, $imp, $type_bid, $can_do, $hash_user,  $tipo_puja_gestor  );

		if (!is_array($res)){
			$res = json_decode($res);
		}



		$params = [
			"cod_sub" => $codSub,
			"can_do" =>  $can_do,
			"res" => $res
		];
		$this->emit("actionFinish", $params);

	}

	public function cancelarBid()
	{
		#no comprobamos que sea admin, por que los usuarios tambien pueden borrar pujas y ya se revisa en la función cancelarPujaV2 si el usuario puede elimnar la puja o no tiene permiso
		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$codSub = request('cod_sub');

		$ref  = request('ref');

		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();

		$codLicit = $Fglicit->cod_licit;
		$hash =  hash_hmac("sha256",$codLicit ." ". $codSub ." ". $ref, Session::get('user.tk') );

		$subastaTiempoRealController = new subastaTiempoRealController();

		$res = $subastaTiempoRealController->cancelarPujaV2($codSub, $codLicit, $ref, $hash);

		if (!is_array($res)){
			$res = json_decode($res);
		}
		$params = [
			"cod_sub" => $codSub,

			"res" => $res
		];
		$this->emit("emitCancelarBid", $params);
	}

	public function endLot()
	{

		#no comprobamos que sea admin, por que los usuarios tambien pueden borrar pujas y ya se revisa en la función cancelarPujaV2 si el usuario puede elimnar la puja o no tiene permiso
		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$codSub  = request('cod_sub');
        $lot = request('lot');
        $jump_lot = request('jump_lot');

		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();

		$codLicit = $Fglicit->cod_licit;
		$hash =  hash_hmac("sha256",$lot  ." ". $codSub ." ". $codLicit, Session::get('user.tk') );


		$subastaTiempoRealController = new subastaTiempoRealController();
		$res = $subastaTiempoRealController->endLotV2($codSub, $lot, $codLicit, $hash, $jump_lot);
		if (!is_array($res)){
			$res = json_decode($res);
		}
		$params = [
			"cod_sub" => $codSub,

			"res" => $res
		];
		$this->emit("emitEndLot", $params);
	}

	public function cancelarOrden()
	{

		#no comprobamos que sea admin, por que los usuarios tambien pueden borrar pujas y ya se revisa en la función cancelarPujaV2 si el usuario puede elimnar la puja o no tiene permiso
		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$codSub  = request('cod_sub');
        $ref = request('ref');


		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();

		$codLicit = $Fglicit->cod_licit;

		$hash = hash_hmac("sha256",$codLicit ." ".$codSub." ". $ref, Session::get('user.tk'));

		$subastaTiempoRealController = new subastaTiempoRealController();

		$res = $subastaTiempoRealController->cancelarOrdenV2($codSub, $codLicit,  $ref, $hash);

		if (!is_array($res)){
			$res = json_decode($res);
		}

		$params = [
			"cod_sub" => $codSub,
			"res" => $res
		];

		$this->emit("emitCancelOrder", $params);
	}

	public function cancelarOrdenUser()
	{

		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$codSub  = request('cod_sub');
        $ref = request('ref');


		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();

		$codLicit = $Fglicit->cod_licit;

		$hash = hash_hmac("sha256",$codLicit ." ".$codSub." ". $ref, Session::get('user.tk'));

		$subastaTiempoRealController = new subastaTiempoRealController();

		$res = $subastaTiempoRealController->cancelarOrdenUserV2($codSub, $ref, $codLicit, $hash);


		if (!is_array($res)){
			$res = json_decode($res);
		}

		$params = [
			"cod_sub" => $codSub,
			"res" => $res
		];

		$this->emit("emitCancelOrderUser", $params);
	}

	public function setStatusAuction(){
		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

        $codSub        = request('cod_sub');
        $status         = request('status');
        $reanudacion    = request('reanudacion');
        $minutes    = request('minutesPause');
        $id_auc_sessions =  request('id_auc_sessions');

		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();

		$codLicit = $Fglicit->cod_licit;
		if(!empty( $minutes)){

			$hasString = $status ." ".$codSub." ". $codLicit." ". $minutes;

		}elseif(!empty( $reanudacion)){

			$hasString = $status ." ".$codSub." ". $codLicit." ". $reanudacion;

		}
		else{
			$hasString = $status ." ".$codSub." ". $codLicit. " ";
		}

		$hash = hash_hmac("sha256",$hasString, Session::get('user.tk'));


		$subastaTiempoRealController = new subastaTiempoRealController();

		$res = $subastaTiempoRealController->setStatusv2($codSub, $status, $reanudacion, $minutes, $codLicit, $hash, $id_auc_sessions );

		if ($res !="error"){
			$res = json_decode($res);
		}

		$params = [
			"cod_sub" => $codSub,
			"res" => $res
		];

		$this->emit("emitSetStatus", $params);
	}

	public function setMessageChat(){
		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

        $codSub        = request('cod_sub');
        $mensaje    = request('mensaje');
		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();
		$codLicit = $Fglicit->cod_licit;
		$msgES = "";
		if(!empty($mensaje["ES"]) && !empty($mensaje["ES"]["msg"])){
			$msgES = $mensaje["ES"]["msg"];
		}
		$hasString = $msgES ." ".$codSub." ". $codLicit;
		$hash = hash_hmac("sha256",$hasString, Session::get('user.tk'));


		//string_hash = $mensajes.ES.msg + " " + cod_sub + " " + cod_licit;
		$chatController = new ChatController();

		$res = $chatController->setChat($codSub, $mensaje,  $codLicit,  $hash );

		if (!is_array($res)){
			$res = json_decode($res);
		}

		$params = [
			"cod_sub" => $codSub,
			"res" => $res
		];
		$this->emit("emitSetChat", $params);
	}

	public function deleteMessageChat(){
		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}


        $codSub        = request('cod_sub');
        $idMensaje    = request('id_mensaje');
        $predefinido    = request('predefinido');
		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", Session::get('user.cod'))->first();

		$codLicit = $Fglicit->cod_licit;

		$hasString = $idMensaje ." ".$codSub." ". $codLicit;
		$hash = hash_hmac("sha256",$hasString, Session::get('user.tk'));

		$chatController = new ChatController();

		$res = $chatController->deleteChatv2( $codSub, $idMensaje, $codLicit, $hash, $predefinido );

		if (!is_array($res)){
			$res = json_decode($res);
		}

		$params = [
			"cod_sub" => $codSub,
			"res" => $res
		];

		$this->emit("emitDeleteChat", $params);
	}

	public function startCountDown(){

		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$params["cod_sub"] = request('cod_sub');
		$params["cd_time"] = request('cd_time');
		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", request('cod_sub'))->where("CLI_LICIT", Session::get('user.cod'))->first();
		$params["cod_licit"] = $Fglicit->cod_licit;
		$this->emit("start_count_down", $params);
	}

	public function stopCountDown(){

		$params["cod_sub"] = request('cod_sub');

		$this->emit("emitStopCountDown", $params);
	}


	public function lotPause(){

		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$codSub        = request('cod_sub');
        $ref    = request('ref');
        $status    = request('status');
		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", request('cod_sub'))->where("CLI_LICIT", Session::get('user.cod'))->first();
		$codLicit =  $Fglicit->cod_licit;
		$hash = hash_hmac("sha256",$ref . " ". $codSub . " ". $codLicit, Session::get('user.tk'));
		$subastaTiempoRealController = new subastaTiempoRealController();
		$res = $subastaTiempoRealController->pausarLoteV2( $codSub, $codLicit,  $hash, $ref, $status);

		if (!is_array($res)){
			$res = json_decode($res);
		}

		$params = [
			"cod_sub" => $codSub,
			"res" => $res
		];

		$this->emit("emitLotPause", $params);
	}

	public function jumpLot(){

		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$codSub        = request('cod_sub');
        $ref    = request('ref');
        $ref_new_pos_lot    = request('ref_lot');

        $orden_actual    = request('orden_actual');
        $status    = request('status');
		$Fglicit = FgLicit::select("COD_LICIT")->where("SUB_LICIT", request('cod_sub'))->where("CLI_LICIT", Session::get('user.cod'))->first();
		$codLicit =  $Fglicit->cod_licit;
		$hash = hash_hmac("sha256",$ref . " ". $codSub . " ". $codLicit, Session::get('user.tk'));
		$subastaTiempoRealController = new subastaTiempoRealController();
		$res = $subastaTiempoRealController->pausarLoteV2( $codSub, $codLicit,  $hash, $ref, $status, $ref_new_pos_lot);
		if (!is_array($res)){
			$res = json_decode($res);
		}

		$params = [
			"cod_sub" => $codSub,
			"res" => $res
		];

		$this->emit("emitLotPause", $params);
	}

	public function fairWarning(){

		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$params["cod_sub"] =   request('cod_sub');
		$this->emit("fairwarning", $params);

	}


	public function openBids(){

		if (!Session::has('user')) {
			$res = new Stdclass();
			$res->status = "error";
			$res->msg ="session_end" ;
			return $res;
		}

		$params["cod_sub"] =   request('cod_sub');
		$this->emit("open_bids", $params);

	}


	private function emit($function , $data){

		$options=[
			'headers' => [

				'X-My-Header: websocket rocks',
				'Authorization: Bearer 12b3c4d5e6f7g8h9i'
			],'context' => [
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false
				]
			]

		];

		$url =  env('NODE_URL');
		$client = new \ElephantIO\Client(new Version2X($url, $options));

		$client->initialize();
		$client->emit($function, $data);

		$client->close();
	}
}
?>
