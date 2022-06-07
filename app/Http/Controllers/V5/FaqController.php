<?php
namespace App\Http\Controllers\V5;

use Redirect;
use Config;
use Response;
use View;
use Route;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\TradLib;
# Cargamos el modelo
use App\Models\V5\Web_Faq;
use App\Models\V5\Web_FaqCat;

class FaqController extends Controller
{
    public function index(Request $request){

        $data = array();
        $emp = \Config::get('app.main_emp');
        $data['items'] = Web_Faq::where("lang_faq",strtoupper(\Config::get("app.locale")))->orderBy('position')->get();
        $data['cats'] = Web_FaqCat::where("lang_faqcat",strtoupper(\Config::get("app.locale")))->orderBy('position')->get();

		$itemsCats = array();
		#voy a clasificar los items por categorias
		foreach($data['items']  as $item){
			if(empty($itemsCats[$item->cod_faqcat])){
				$itemsCats[$item->cod_faqcat] = array();
			}
			$itemsCats[$item->cod_faqcat] [] = $item;
		}

		$data['itemsCats'] = $itemsCats;

		$seoExist = TradLib::getWebTranslateWithStringKey('metas', 'title_faq', config('app.locale', 'es'));
		if(!empty($seoExist)){
			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = trans(\Config::get('app.theme') . '-app.metas.title_faq');
			$data['seo']->meta_description = trans(\Config::get('app.theme') . '-app.metas.description_faq');
		}

        return View::make('pages.V5.faqs', array("data" => $data));

    }

}
