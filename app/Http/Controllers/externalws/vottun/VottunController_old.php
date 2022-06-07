<?php
namespace App\Http\Controllers\externalws\vottun;

use Request;
use Exception;
use SoapFault;
use SimpleXMLElement;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

class VottunController extends Controller
{



public function VottumRequest($parameters, $function, $token=null){

	$method= "POST";
	$url = "https://nftdemo.vottun.com/api";
	$clientGuzz = new Client();
	$headers = [
		'Content-Type' => 'application/json',
		'Accept' => '*/*'
	];
	if(!empty($token)){
		$headers["authorization"] ="token $token";
		$headers["x-registertype"] =2;

	}

	$response = $clientGuzz->request($method, $url . $function,[
		'headers' => $headers,
		   'verify' => false,
		   'body' =>json_encode($parameters),


		]);

		return $response;
}

public function AuthLoginVottum(){

	$vars = new Stdclass();
	$vars->email ="labelgrup@nfts.com";
	$vars->password = "1a1a1a1a";
	$response = $this->VottumRequest($vars, "/auth/login/");
	$body = json_decode($response->getBody());
	return $body->key;

}


public function OwnerAddVottum() {
	$token =  $this->AuthLoginVottum();

	$vars = new Stdclass();
	$vars->student_id = '00001';
	$vars->first_name = 'Toni';
	$vars->last_name = 'Gomez';
	$vars->email = 'toni@gomez.com';
	$vars->phone = '961234567';
	$vars->dob = '2022-02-23';
	$vars->gender = 'M';
	$vars->avatar = 'Avatar';
	$vars->group_id = 'Grupo ID';

	$response = $this->VottumRequest($vars, "/users/owner/add/",$token);
	$body = json_decode($response->getBody());
	print_r($body);

}

#pendiente de probar
public function NFTAddVottum() {
	$token =  $this->AuthLoginVottum();
	$vars = new Stdclass();

	#variables que tienen en su formulario de la web
	$vars->media_type = 'jpg'; #jpg, png
	$vars->title = 'Prueba NFT';
	$vars->description = ' ';
	$vars->external_url = ' ';
	$vars->artist = ' ';
	$vars->yoc = ' '; # year of creation, solo año
	$vars->country = ' ';
	$vars->transfer_commission = ' ';
	$vars->number_of_token = ' ';




	$vars->is_certificate = true;

	$vars->degree = ' ';
	$vars->code = ' ';

	$vars->criteria = ' ';
	$vars->logo = ' ';
	$vars->badges = ' ';
	$vars->courses ="a8f4b500-c59f-470d-beee-f3855a21ca9e,a8b55abe-4a23-4946-a2e7-d9f3d7b1a439";
	$vars->type = true;
	$vars->is_apple_wallet = true;
	$vars->is_designed = true;
	$vars->company_logo = ' ';
	$vars->eulogy = ' ';
	$vars->subject = ' ';
	$vars->bg_image = ' ';
	$vars->identifier = ' ';
	$vars->color_scheme = true;
	$vars->option_logo_list = ' ';
	$vars->option_name_list = ' ';
	$vars->option_position_list = ' ';
	$vars->number_of_nft = ' ';

	$vars->media_url = ' ';
	$vars->media_path = ' ';
}


}
