<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    public function index()
    {
		$data['restricti_css_js'] = true;

		if(Config::get('app.seo_in_home', 0)){
			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = trans(Config::get('app.theme').'-app.metas.title_home');
			$data['seo']->meta_description = trans(Config::get('app.theme').'-app.metas.description_home');
		}

        return view('front::pages.home', array('data' => $data));
    }

	public function accept_cookies()
	{
		$config = config('session');

		return response('OK')->cookie(
		    'cookie_law', '1', (60*24*365), $config['path'], $config['domain'], $config['secure'], true
		);
	}

}
