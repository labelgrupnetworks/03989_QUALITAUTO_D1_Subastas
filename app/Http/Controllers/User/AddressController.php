<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Enterprise;
use App\Models\User;
use App\Models\V5\FsIdioma;
use App\Models\V5\FsPaises;
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

		$Usuario          = new User();
		$Usuario->cod_cli = Session::get('user.cod');
		$datos            = $Usuario->getUser();
		$addres = new Address();
		$addres->cod_cli = Session::get('user.cod');
		$shippingaddress            = $addres->getUserShippingAddress();
		$address = array();
		$address            = head($addres->getUserShippingAddress('W1'));
		$enterprise = new Enterprise();

		$data = array(
			'name' => trans(Config::get('app.theme') . '-app.user_panel.personal_info'),
			'user' => $datos,
			'shippingaddress'  => $shippingaddress,
			'address'  => $address,
		);

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

		$data['via']  = collect($enterprise->getVia())->pluck('des_sg', 'cod_sg');

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

}
