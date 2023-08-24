<?php

namespace App\Http\Controllers;

use Redirect;

//opcional
use App;
use App\Http\Controllers\V5\LotListController;
use DB;
use Request;
use Validator;
use Session;
use View;
use Routing;
use Config;
use Route;

# Cargamos el modelo
use App\Models\Content;
use App\Models\Bloques;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgSub;
use App\Models\V5\Web_Page;

class ContentController extends Controller
{
	public $lang;
	public $id;

	/*public function getContent()
    {
    	$Content 		= new Content();
    	$Content->lang  = Config::get(\App::getLocale());

        $data = $Content->getContent();



        return View::make('front::pages.content', array('data' => $data));
    }*/

	/* 2017_11_13 Se ha hecho un controlador nuevo page controllercion nueva y esta está obsoleta */
	/*
    public function getPagina($lang,$pagina)
    {
        $pag = $pagina;
        $Pagina         = new Content();

        # Seteamos la pagina como slug para buscar y establecemos el idioma
        $Pagina->lang   = strtoupper($lang);
        $Pagina->slug   = $pag;

        $data = head($Pagina->getContent());



        /*
         *
       es necesario la linea de código Route::get('/{lang}/pagina/{pagina}', 'ContentController@getPagina'); del routes para que esto funcione
        $lang   = Route::current()->parameter('lang');
        $Pagina->slug   = $pag;
        $pa = head($Pagina->getContent());
        if ($pa->id_lang != $lang){
            $pagina_lang   = new Content();
            $pagina_lang->lang = $lang;
            $pagina_lang->id = $pa->id_content;
            print_r($pagina_lang);die();
            $pa = head($pagina_lang->getContent());

        }
        $data = $pa;
         *
         *
         *
         *
         *//*
        # Si no existe la pagina mostramos 404
        if(empty($data)) {
            exit (\View::make('front::errors.404'));
        }

        # Comprobamos las coincidencias de anchors de solo un nivel
        $coincidencias = self::anchor($data->value);

        # Reemplazamos las coincidencias
        foreach ($coincidencias as $key) {
            $data->value = str_replace($key['clave'], $key['value'], $data->value);
        }

        # Asignamos
        $data->name = $data->title.' - '.Config::get('app.name');

        $SEO_metas= new \stdClass();
        $SEO_metas->noindex_follow = false;
        $SEO_metas->meta_title = $data->webmetat_content;
        $SEO_metas->meta_description = $data->webmetad_content;


        if(empty($_GET['modal'])){
            $data = array(
            'data' => $data,
            'seo' => $SEO_metas ,
        );
            return View::make('front::pages.content', array('data' => $data));
        }else{
            //return View::make('front::includes.ficha.modals_information', array('data' => $data->value));
            return $data->value;
        }
    }
*/
	# Comprobamos el contenido según el punto de anclaje (Anchor)
	public static function anchor($contenido)
	{
		$patron = '/##(.*?)##/';
		preg_match_all($patron, $contenido, $coincidencias);

		$total = array();

		# Bucle de coincidencias
		foreach ($coincidencias[0] as $item => $val) {

			# Consultamos por cada coincidencia
			$CMS            = new Content();
			$CMS->lang      = Config::get(\App::getLocale());
			$CMS->anchor    = str_replace('##', '', $val);
			$contenido      = head($CMS->getContent())->value;

			array_push($total, $item);
			$total[$item] = array('clave' => $val, 'value' => $contenido, 'slug' => str_replace('##', '', $val));
		}

		return $total;
	}

	public function setContent()
	{
	}

	public function getAjaxStaticCarousel()
	{
		$pathFile = public_path(request('path', ''));

		if(!file_exists($pathFile) || !is_file($pathFile)){
			return response()->json(['message' => 'Not Found'], 404);
		}

		$lots = json_decode(file_get_contents($pathFile));
		$content = "";

		foreach ($lots as $lot) {
			$content .= view('includes.static_carousel', ['lot' => $lot])->render();
		}

		return $content;
	}

	function getAjaxCarousel()
	{
		$bloque         = new Bloques();
		$contents = "";
		$banner = $bloque->getResultBlockByKeyname($_POST['key'], $_POST['replace']);
		if (empty($banner)) {
			return;
		}

		foreach ($banner as $item) {
			if (isset($item->impsalhces_asigl0)) {
				$item->no_formated_impsalhces_asigl0 = $item->impsalhces_asigl0;
				$item->impsalhces_asigl0 = \Tools::moneyFormat($item->impsalhces_asigl0);
			}
		}
		if (!empty($_POST['replace'])) {
			$lang_temp = $_POST['replace']['lang'];
			$locales = Config::get('app.language_complete');
			foreach ($locales as $key => $value) {
				if ($value == $lang_temp) {
					\App::setLocale($key);
				}
			}
		}
		$img = new \App\Models\Subasta;
		if (!empty($banner)) {
			foreach ($banner as $bann) {
				$contents .= view('includes.carousel', array('bann' => $bann, 'img' => $img))->render();
			}
		}


		if (!empty($_POST['size'])) {
			$data = array('contents' => $contents, 'size' => count($banner));
			return $data;
		}

		return $contents;
	}

	function getAjaxNewCarousel()
	{
		Config::set('app.locale', request('lang', 'es'));
		$bloque         = new Bloques();
		$contents = "";
		$lots = null;
		$lotsQuery = $bloque->getResultBlockByKeyname($_POST['key'], $_POST['replace']);

		$lotlistcontroller = new LotListController();
		$lotlist = $lotlistcontroller->setRef($lotsQuery);

		#cargamos los datos de los lotes
		if (!empty($lotlist) && !empty($lotlist->refLots)) {

			$fgasigl0 = new FgAsigl0();
			$lots = $fgasigl0->GetLotsByRefAsigl0($lotlist->refLots)
			->when(request('order'), function ($query, $order) {
				return $query->orderBy($order);
			})
			->get();

			#seteamos las variables para la blade
			$lots = $lotlistcontroller->setVarsLot($lots);
		}

		if (empty($lots)) {
			return "";
		}

		return \View::make('front::includes.new_carrousel', ["lots"  => $lots]);
	}

	public function faqs()
	{

		$data = array();
		$img = array();
		$lang = strtoupper(Config::get('app.locale'));
		$fila = 1;
		if (($gestor = fopen("files/faqs/faqs_" . $lang . ".csv", "r")) !== FALSE) {
			while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {

				$numero = count($datos);
				for ($x = 0; $x < $numero; $x++) {

					if ($x == 0 &&  !empty($datos[$x])) {
						$a = $datos[$x];
						$imgagen = $datos[$x + 1];
						$data[$a] = array();
						$img[$a] = $imgagen;
						break;
					} else if ($x == 1 &&  !empty($datos[$x])) {
						$b = $datos[$x];
						$data[$a][$b] = array();
						break;
					} else if ($x == 2 &&  !empty($datos[$x])) {
						$c = $datos[$x];
						$data[$a][$b][$c] = $datos[3];
					}
				}
			}
			fclose($gestor);
		}
		$data['faqs'] = $data;
		$data['img'] = $img;
		$data['name'] = 'FAQS';
		$SEO_metas = new \stdClass();
		$SEO_metas->meta_title = trans(\Config::get('app.theme') . '-app.metas.theme_auction_faqs_title');
		$SEO_metas->meta_description = trans(\Config::get('app.theme') . '-app.metas.theme_auction_faqs_description');
		$data['seo'] = $SEO_metas;

		return View::make('pages.faqs', array('data' => $data));
	}

	public function contentAvailable($lang)
	{
		$pages = Web_Page::select('key_web_page', 'name_web_page')
			->where([
				['LANG_WEB_PAGE', $lang],
				['WEBNOINDEX_WEB_PAGE', '!=', '1']
			])
			->get();

		//blog y entradas

		//articulos

		//artistas

		$subastas = FgSub::select('subc_sub')->joinSessionSub()
			->whereIn('subc_sub', [FgSub::SUBC_SUB_ACTIVO, FgSub::SUBC_SUB_HISTORICO])
			->get();

		$lotes = FgAsigl0::select('sub_asigl0', 'ref_asigl0', '"id_auc_sessions"', '"name"', 'num_hces1')
			->addSelect("NVL(FGHCES1_LANG.TITULO_HCES1_LANG, FGHCES1.TITULO_HCES1) TITULO_HCES1, NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) DESCWEB_HCES1, NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1")
			->joinFghces1Asigl0()
			->joinFghces1LangAsigl0()
			->joinSessionAsigl0()
			->whereIn('FGASIGL0.SUB_ASIGL0', $subastas->pluck('cod_sub'))
			->where([
				['FGASIGL0.RETIRADO_ASIGL0', 'N'],
				['FGHCES1.FAC_HCES1', '!=', 'D'],
				['FGHCES1.FAC_HCES1', '!=', 'R'],
				['FGASIGL0.CERRADO_ASIGL0', 'N'],
			])
			->orderBy('sub_asigl0')
			->get();

		$categorias = FgOrtsec0::select('des_ortsec0', 'key_ortsec0')->getAllFgOrtsec0()->get();

		return compact('pages', 'subastas', 'lotes', 'categorias');
	}

	public function rematesDestacados($codSub){

		$lots = FgAsigl0::ActiveLotAsigl0()->
		select('REF_ASIGL0, NUM_HCES1, LIN_HCES1, DESCWEB_HCES1, DESC_HCES1, IMPSALHCES_ASIGL0, IMPLIC_HCES1, COD_SUB, "id_auc_sessions", "name", WEBFRIEND_HCES1, DES_SUB ')->
		where("SUB_ASIGL0", $codSub)->
		where("cerrado_asigl0", "S")->
		where("IMPLIC_HCES1",">",0)->
		where("DESTACADO_ASIGL0", "S")->
		orderby('"start", ref_asigl0')->
		get();
		$sessions = array();
		foreach($lots as $lot){
			if (empty($sessions[$lot->name])){
				$sessions[$lot->name] = array();
			}
			$sessions[$lot->name][] = $lot;
		}

		return View::make('pages.remates_destacados', compact("sessions"));

	}
}
