<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Models\V5\FxCliWeb;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
	#Metodo nuevo
	public function setNewsletter(Request $request)
	{
		$rules = array(
			'email'    => 'required|email',
			'condiciones'    => 'required',
		);

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return [
				'status' => 'error',
				"msg" => 'err-add_newsletter'
			];
		}

		$email = trim($request->get('email'));
		$lang = $request->get('lang');

		// Miramos si ya existe el usuario y está registrado
		$hasCliweb = FxCliWeb::where('LOWER(USRW_CLIWEB)', strtolower($email))->first();
		if (!$hasCliweb) {
			FxCliWeb::insert([
				"GEMP_CLIWEB" => Config::get('app.gemp'),
				"COD_CLIWEB" => "0",
				"USRW_CLIWEB" => $email,
				"EMAIL_CLIWEB" => $email,
				"EMP_CLIWEB" => Config::get('app.emp'),
				"TIPACCESO_CLIWEB" => "N",
				"TIPO_CLIWEB" => "C",
				"FECALTA_CLIWEB" => date("Y-m-d H:i:s"),
				"IDIOMA_CLIWEB" => $lang,
			]);
		}

		$news = new Newsletter();
		$news->families = $request->get('families');
		$news->lang = $lang;
		$news->email = $email;

		if (!empty($news->families)) {
			$news->newFamilies();
		}

		return [
			'status' => 'success',
			'msg' => 'success-add_newsletter'
		];
	}
}
