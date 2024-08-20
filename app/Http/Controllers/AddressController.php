<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Enterprise;
use App\Models\User;
use App\Models\V5\FsPaises;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AddressController extends Controller
{
	//Direcciones envio
	public function updateShippingAddress(Request $request)
	{
		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);
		$userCod = Session::get('user.cod');

		$addres = new Address();
		$addres->cod_cli = $userCod;
		$data_adress = [];

		//$values = $request->except(['_token']);
		//trim to all values
		$request->merge(array_map('trim', $request->all()));

		//apply strtoupper to all values if strtodefault_register is true
		if (!$strToDefault) {
			$request->merge(array_map('mb_strtoupper', $request->all()));
		}

		//$rsoc = request('clid_rsoc', request('usuario'));
		$rsoc = $request->input('clid_rsoc', $request->input('usuario'));
		$desPais = FsPaises::select('des_paises')
			->where('cod_paises', $request->input('clid_pais'))
			->get();

		$envio = [
			'clid_direccion'  => mb_substr($request->input('clid_direccion'), 0, 30, 'UTF-8'),
			'clid_direccion_2'  => mb_substr($request->input('clid_direccion'), 30, 30, 'UTF-8'),
			'clid_cod_pais'   => $request->input('clid_pais'),
			'clid_poblacion'   => $request->input('clid_poblacion'),
			'clid_cpostal'   => $request->input('clid_cpostal'),
			'clid_pais' => $desPais,
			'clid_via' => $request->input('clid_codigoVia', null),
			'clid_provincia'    => $request->input('clid_provincia', null),
			'clid_name' => $request->input('usuario'),
			'clid_telf' => $request->input('telefono'),
			'clid_rsoc' => $rsoc,
			'email_clid' => $request->input('email_clid', null),
			'preftel_clid' => $request->input('preftel_clid', ''),
			'obs_clid' => $request->input('obs_clid', ''),
		];

		if (!empty($request->input('codd_clid'))) {
			$codd_clid = $request->input('codd_clid');
			$data_adress = $addres->getUserShippingAddress($codd_clid);
		} else {
			$codd_clid = $this->getNewCoddClid($userCod);
		}
		$envio['codd_clid'] = $codd_clid;

		if (!empty($data_adress)) {
			$addres->editDirEnvio($envio, $addres->cod_cli);
		} else {
			$addres->addDirEnvio($envio, $addres->cod_cli, $envio['clid_name']);
		}

		return [
			'status' => 'success',
			'codd_clid' => $envio['codd_clid']
		];
	}

	public function deleteShippingAddress(Request $request)
	{
		$addres = new Address();
		$addres->cod_cli = Session::get('user.cod');
		$codd_clid = $request->input('cod');
		$addres->deleteAddres($codd_clid);

		return [
			'status' => 'success',
			'codd_clid' => $codd_clid
		];
	}

	public function FavoriteShippingAddress(Request $request)
	{
		$addres = new Address();
		$addres->cod_cli = Session::get('user.cod');
		$codd_clid = $request->input('codd_clid');

		$addres->changeFavoriteAddres($codd_clid, 'W2');
		$addres->changeFavoriteAddres('W1', $codd_clid);
		$addres->changeFavoriteAddres('W2', 'W1');
		return ['status' => 'success'];
	}

	public function seeShippingAddress(Request $request)
	{
		$userCod = Session::get('user.cod');

		$addres = new Address();
		$addres->cod_cli = $userCod;

		$coddCli = $request->input('codd_clid');
		#controlamos que no esten insertando código malicioso, cómo el limite del campo son 4 caracteres usaremos esa caracteristica
		if (strlen($coddCli) > 4) {
			$coddCli = 'W1';
		}

		//Si no viene codd_clid cremos nuevo codigo de envio
		if ($coddCli == 'new') {
			$codd_clid = $this->getNewCoddClid($userCod);
			//Si viene asignamos codigo
		} elseif (!empty($coddCli)) {
			$codd_clid = $coddCli;
		} else {
			$codd_clid = 'W1';
		}

		$data_adress = $addres->getUserShippingAddress($codd_clid);

		$userClass = new User();
		$userClass->cod_cli = $userCod;

		$data = [
			'via' => (new Enterprise)->getVia(),
			'countries' => FsPaises::selectBasicPaises()->orderBy("des_paises")->get(),
			'new' => $coddCli == 'new',
			'shippingaddress' => $addres->getUserShippingAddress(),
			'address' => !empty($data_adress) ? head($data_adress) : null,
			'codd_clid' => $codd_clid,
			'user' => $userClass->getUser()
		];

		return view('front::pages.panel.address_shipping', ['data' => $data]);
	}

	private function getNewCoddClid($codCli)
	{
		$addres = new Address();
		$addres->cod_cli = $codCli;

		$max_direcc_temp = $addres->getMaxShippingAddress();

		if (!empty($max_direcc_temp)) {
			$max_direcc = head($max_direcc_temp)->max_codd + 1;
			return str_pad($max_direcc, '2', 0, STR_PAD_LEFT);
		}

		return '01';
	}
}
