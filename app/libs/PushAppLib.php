<?php

namespace App\libs;

use Config;
use App\Jobs\PushAppJob;
use App\Models\V5\AppPush;
use Illuminate\Support\Facades\Log;

class PushAppLib {

	private $idPush;
	private $url;
	private $tokens;
	private $title;
	private $description;
	private $action;
	private $info;

	public function __construct($idPush, $url,$title, $description, $action, $info=[] ){
		$this->idPush = $idPush;
		$this->url = $url;
		$this->title = $title;
		$this->description = $description;
		$this->action = $action;
		$this->info = $info;

	}

	public function setTokens($tokens){
		$this->tokens = $tokens;

	}

	public function send_push(){

		$rCurl = curl_init();

		$data = array(
			'tokens' => $this->tokens,
			'data' => [
				"title"=> $this->title,
				"description"=> $this->description,
				"action_link"=> $this->action,
				"info" => $this->info
				]
			);

		$dataJson = json_encode( $data);
		//attach encoded JSON string to the POST fields

		$headers = array(
			"Content-Type:application/json"
			);

		curl_setopt($rCurl, CURLOPT_URL, $this->url);
		curl_setopt($rCurl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($rCurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($rCurl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
		curl_setopt($rCurl, CURLOPT_POSTFIELDS, $dataJson);
		curl_setopt($rCurl, CURLOPT_POST, true);
		curl_exec($rCurl);

		curl_close($rCurl);

		$sendedTokens= count($this->tokens);
		//Log::info("sended tokens". $sendedTokens);
		Log::info('sended tokens '.$this->idPush, ['sended_tokens' => $sendedTokens]);
		AppPush::where("ID_PUSH", $this->idPush)->increment("SENDEDTOKENS_PUSH", $sendedTokens,["SENDED_PUSH" => "S"]);


	}


}
