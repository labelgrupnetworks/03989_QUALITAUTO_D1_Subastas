<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddressRequest;
use App\Models\Enterprise;
use App\Models\User;
use App\Models\V5\FsIdioma;
use App\Models\V5\FsPaises;
use App\Services\User\UserAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AddressController extends Controller
{
	public function index()
	{
		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data = trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			return View::make('front::pages.not-logged', array('data' => $data));
		}

		$userId = Session::get('user.cod');

		$Usuario = new User();

		$Usuario->cod_cli = $userId;
		$datos = $Usuario->getUser();

		$addressService = new UserAddressService();

		$data = [
			'name' => trans(Config::get('app.theme') . '-app.user_panel.personal_info'),
			'user' => $datos,
			'shippingaddress'  => $addressService->getUserAddresses($userId),
			'address'  => $addressService->getUserAddressById($userId, 'W1')
		];

		$data['codd_clid'] = 'W1';
		$countries_aux = FsPaises::JoinLangPaises()->addSelect('preftel_paises')->orderby("des_paises")->get();

		$countries = [];
		$prefix = [];
		foreach($countries_aux as $item) {
			$countries[$item->cod_paises] = $item->des_paises;
			$prefix[$item->cod_paises] = str_pad($item->preftel_paises, 4, 0, STR_PAD_LEFT);
		}

		$data['countries'] = $countries;
		$data['prefix'] = $prefix;

		$data['via'] = $addressService->getPluckStreetTypes();

		if (!empty(FsIdioma::getArrayValues())) {
			$data['language'] = FsIdioma::getArrayValues();
		} else {
			foreach (Config::get('app.locales') as $key => $value) {
				$data['language'][strtoupper($key)] = $value;
			}
		}

		//para volver a la pasarela de pago en caso de necesitarlo.
		$data['auction'] = request('cod_sub');

		return view()->make('front::pages.panel.direcciones', array('data' => $data));
	}

	public function updateShippingAddress(AddressRequest $request)
	{
		$addressDTO = $request->toDTO();
		$userCod = Session::get('user.cod');
		$addressService = new UserAddressService();

		/**
		 * Si en la petición viene codd_clid es porque se está editando una dirección
		 * Si no viene es porque se está creando una nueva dirección
		 * @todo Mover a otro metodo el crear la dirección
		 */
		if ($addressDTO->codd_clid) {
			$addressService->editAddress($addressDTO, $userCod);
		} else {
			$addressDTO->setCoddClid($addressService->getNewMaxAddressId($userCod));
			$addressService->addAddress($addressDTO, $userCod);
		}

		return [
			'status' => 'success',
			'codd_clid' => $addressDTO->codd_clid
		];
	}

	public function deleteShippingAddress(Request $request)
	{
		$userId = Session::get('user.cod');
		$codd_clid = $request->input('codd_clid', $request->input('cod'));

		$addressService = new UserAddressService();
		$addressService->deleteAddress($userId, $codd_clid);

		return [
			'status' => 'success',
			'codd_clid' => $codd_clid
		];
	}

	public function FavoriteShippingAddress(Request $request)
	{
		$userID = Session::get('user.cod');
		$codd_clid = $request->input('codd_clid');

		$addressService = new UserAddressService();
		$addressService->changeFavoriteAddress($userID, $codd_clid, 'W2');
		$addressService->changeFavoriteAddress($userID, 'W1', $codd_clid);
		$addressService->changeFavoriteAddress($userID, 'W2', 'W1');

		return ['status' => 'success'];
	}

	public function seeShippingAddress(Request $request)
	{
		$userCod = Session::get('user.cod');
		$addressService = new UserAddressService();
		$coddCli = $request->input('codd_clid');

		#controlamos que no esten insertando código malicioso, cómo el limite del campo son 4 caracteres usaremos esa caracteristica
		if (strlen($coddCli) > 4) {
			$coddCli = 'W1';
		}

		//Si no viene codd_clid cremos nuevo codigo de envio
		if ($coddCli == 'new') {
			$codd_clid = $addressService->getNewMaxAddressId($userCod);
			//Si viene asignamos codigo
		} elseif (!empty($coddCli)) {
			$codd_clid = $coddCli;
		} else {
			$codd_clid = 'W1';
		}

		$userClass = new User();
		$userClass->cod_cli = $userCod;
		$data = [
			'via' => $addressService->getStreetTypes(),
			'countries' => FsPaises::selectBasicPaises()->orderBy("des_paises")->get(),
			'new' => $coddCli == 'new',
			'shippingaddress' => $addressService->getUserAddresses($userCod)->toArray(),
			'address' => $addressService->getUserAddressById($userCod, 'W1'),
			'codd_clid' => $codd_clid,
			'user' => $userClass->getUser()
		];

		return view('front::pages.panel.address_shipping', ['data' => $data]);
	}

}
