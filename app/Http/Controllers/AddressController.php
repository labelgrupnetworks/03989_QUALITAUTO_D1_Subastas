<?php

namespace App\Http\Controllers;

use Redirect;

//opcional
use App;
use DB;
use Request;
use Validator;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session;
use View;
use Routing;
use Illuminate\Support\Facades\Config;
use Route;
use File;

# Cargamos el modelo
use App\Models\Content;
use App\Models\AucIndex;
use App\Models\Address;
use App\Models\Enterprise;
use App\Models\User;

class AddressController extends Controller
{
	//Direcciones envio
	public function updateShippingAddress($fxCliAddres = null)
	{

		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);

		$addres = new Address();
		$addres->cod_cli = Session::get('user.cod');
		$data_adress = array();

		/**
		 * añadir: rsoc_clid, email_clid,
		 */

		if(!empty($fxCliAddres)){
			$envio = array(
				'clid_direccion'  => $fxCliAddres->dir,
				'clid_direccion_2'  => $fxCliAddres->dir2,
				'clid_cod_pais'   => $fxCliAddres->pais,
				'clid_poblacion'   => $fxCliAddres->pob,
				'clid_cpostal'   => $fxCliAddres->cp,
				'clid_pais' => $fxCliAddres->nombre_pais,
				'clid_via' => '',
				'clid_provincia' => $fxCliAddres->pro,
				'clid_name' => $fxCliAddres->nom,
				'clid_telf' => $fxCliAddres->tel,
				'preftel_clid' => $fxCliAddres->preftel_clid,
				'obs_clid' => $fxCliAddres->obs_clid,
			);
		}
		else{

			$rsoc = request('clid_rsoc', request('usuario'));
			$envio = array(
				'clid_direccion'  => $strToDefault ? mb_substr(Input::get('clid_direccion'), 0, 30, 'UTF-8') : strtoupper(mb_substr(Input::get('clid_direccion'), 0, 30, 'UTF-8')),
				'clid_direccion_2'  => $strToDefault ? mb_substr(Input::get('clid_direccion'), 30, 30, 'UTF-8') :strtoupper(mb_substr(Input::get('clid_direccion'), 30, 30, 'UTF-8')),
				'clid_cod_pais'   => trim(Input::get('clid_pais')),
				'clid_poblacion'   => trim(Input::get('clid_poblacion')),
				'clid_cpostal'   => trim(Input::get('clid_cpostal')),
				'clid_pais' => DB::select("SELECT des_paises FROM FSPAISES WHERE cod_paises = :codPais",array("codPais"=>Request::input('clid_pais'))),
				'clid_via' => trim(!empty(Input::get('clid_codigoVia')) ? Input::get('clid_codigoVia') : null),
				'clid_provincia'    => trim(!empty(Input::get('clid_provincia')) ? Input::get('clid_provincia') : null),
				'clid_name' => $strToDefault ? trim(Input::get('usuario')) : trim(strtoupper(Input::get('usuario'))),
				'clid_telf' => trim(Input::get('telefono')),
				'clid_rsoc' => $strToDefault ? trim($rsoc) : trim(strtoupper($rsoc)),
				'email_clid' => trim(!empty(Input::get('email_clid')) ? Input::get('email_clid') : null),
				'preftel_clid' => trim(request('preftel_clid', '')),
				'obs_clid' => trim(request('obs_clid', '')),
			);
		}

		if (!empty(Request::input('codd_clid'))) {
			$codd_clid = Request::input('codd_clid');
			$data_adress = $addres->getUserShippingAddress($codd_clid);
		} else {
			$max_direcc_temp = $addres->getMaxShippingAddress();
			if (!empty($max_direcc_temp)) {
				$max_direcc = head($max_direcc_temp)->max_codd + 1;
				$codd_clid = str_pad($max_direcc, '2', 0, STR_PAD_LEFT);
			} else {
				$codd_clid = '01';
			}
		}
		$envio['codd_clid'] = $codd_clid;
		if (!empty($data_adress)) {
			//edit dirección de envio

			$addres->editDirEnvio($envio, $addres->cod_cli);
			return $response = array(
				"status"       => 'success',
				"codd_clid"       =>  $envio['codd_clid']
			);
		} else {
			$addres->addDirEnvio($envio, $addres->cod_cli, $envio['clid_name']);
			return $response = array(
				"status"       => 'success',
				"codd_clid"       =>  $envio['codd_clid']
			);
		}
	}

	public function deleteShippingAddress()
	{
		$addres = new Address();
		$addres->cod_cli = Session::get('user.cod');
		$codd_clid = Request::input('cod');
		$addres->deleteAddres($codd_clid);
		return $response = array(
			"status"       => 'success',
			"codd_clid"       =>  $codd_clid
		);
	}

	public function FavoriteShippingAddress()
	{
		$addres = new Address();
		$addres->cod_cli = Session::get('user.cod');
		$codd_clid = Request::input('codd_clid');

		$addres->changeFavoriteAddres($codd_clid, 'W2');
		$addres->changeFavoriteAddres('W1', $codd_clid);
		$addres->changeFavoriteAddres('W2', 'W1');
		return $response = array(
			"status"       => 'success'
		);
	}

	public function seeShippingAddress()
	{
		$addres = new Address();
		$enterprise = new Enterprise();
		$data['via']  = $enterprise->getVia();
		$data['countries'] = DB::select("SELECT cod_paises, des_paises FROM FSPAISES ORDER BY des_paises ASC");
		$data['new'] = false;

		$coddCli =Request::input('codd_clid');
		#controlamos que no esten insertando código malicioso, cómo el limite del campo son 4 caracteres usaremos esa caracteristica
		if(strlen($coddCli)>4){
			$coddCli ='W1';
		}

		//Si no viene codd_clid cremos nuevo codigo de envio
		if ($coddCli == 'new') {
			$addres->cod_cli = Session::get('user.cod');
			$max_direcc_temp = $addres->getMaxShippingAddress();
			if (!empty($max_direcc_temp)) {
				$max_direcc = head($max_direcc_temp)->max_codd + 1;
				$max_direcc = str_pad($max_direcc, '2', 0, STR_PAD_LEFT);
				$codd_clid = $max_direcc;
			} else {
				$codd_clid = '01';
			}
			$data['new'] = true;
			//Si viene asignamos codigo
		} elseif (!empty($coddCli)) {
			$codd_clid = $coddCli;
		} else {
			$codd_clid = 'W1';
		}

		$addres->cod_cli = Session::get('user.cod');
		$data_adress = $addres->getUserShippingAddress($codd_clid);
		$data['shippingaddress'] = $addres->getUserShippingAddress();
		if (empty($data_adress)) {
			$data['address']  = null;
		} else {
			$data['address']   = head($data_adress);
		}

		$data['codd_clid'] = $codd_clid;

		$userClass          = new User();
		$userClass->cod_cli = Session::get('user.cod');
        $data['user'] = $userClass->getUser();

		return view('front::pages.panel.address_shipping', array('data' => $data));
	}
}
