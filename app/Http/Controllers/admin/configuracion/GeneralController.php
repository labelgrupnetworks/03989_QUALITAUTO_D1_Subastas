<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class GeneralController extends Controller
{
    public function index()
    {
        $data = [
			'registration_disabled' => empty(Config::get('app.registration_disabled'))
		];

		if(Config::get('app.emails_with_commission')){
			$data['buyer_premium_active'] = Config::get('app.buyer_premium_active', false);
			$data['addComisionEmailBid'] = Config::get('app.addComisionEmailBid', 0);
		}

        return View::make('admin::pages.configuracion.general.general', ['data' => $data]);
    }

    public function save(Request $request)
	{
		Web_Config::where([
			'key' => 'registration_disabled',
			'emp' => Config::get('app.emp')
		])->update(['value' => $request->input('registration_disabled', 0)]);

		if(Config::get('app.emails_with_commission')) {
			Web_Config::where([
				'key' => 'buyer_premium_active',
				'emp' => Config::get('app.emp')
			])->update(['value' => $request->input('buyer_premium_active', 0)]);

			Web_Config::where([
				'key' => 'addComisionEmailBid',
				'emp' => Config::get('app.emp')
			])->update(['value' => $request->input('addComisionEmailBid', 0)]);
		}

		return redirect()->back()->with('success', ['Cambios guardados correctamente']);
    }
}
