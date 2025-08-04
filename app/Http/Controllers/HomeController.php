<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    public function index()
    {
		if(Config::get('app.redirecto_home_to', '')) {
			return redirect()->to(route(Config::get('app.redirecto_home_to')));
		}

		$data['restricti_css_js'] = true;

		if(Config::get('app.seo_in_home', 0)){
			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = trans(Config::get('app.theme').'-app.metas.title_home');
			$data['seo']->meta_description = trans(Config::get('app.theme').'-app.metas.description_home');
		}

        return view('front::pages.home', array('data' => $data));
    }
}
