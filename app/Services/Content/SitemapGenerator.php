<?php

namespace App\Services\Content;

use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgSub;
use App\Models\V5\Web_Page;
use App\Providers\RoutingServiceProvider;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SitemapGenerator
{
	/**
	 * Generate the sitemap for the website.
	 *
	 * @return void
	 */
	public function generate()
	{
		$langs = Config::get('app.locales');
		$url =  Config::get('app.url');
		$fechaactual = date("Y-m-d");

		$priority_web = '1.00';
		$priority_page = '0.80';
		$priority_lot = '0.50';
		$xml = array();
		$buffer = '<?xml version="1.0" encoding="utf-8"?>';
		$buffer .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach ($langs as $lang => $valueLang) {

			$xml[] = $this->xml($url, $lang, '', $fechaactual, $priority_web);

			$idioma = strtoupper($lang);
			App::setLocale(strtolower($lang));

			[
				'pages' => $pages,
				'subastas' => $subastas,
				'lotes' => $lotes,
				'categorias' => $categorias
			] = $this->contentAvailable($idioma);


			//páginas
			foreach ($pages as $page) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('pagina'), 4) . $page->key_web_page, $fechaactual, $priority_page);
			}

			//todas subastas
			if (config('app.gridLots', '') == "new") {
				$xml[] = $this->xmlWithUrl(route('allCategories'), $fechaactual, $priority_lot);
			} else {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('todas-subastas'), 4), $fechaactual, $priority_lot);
			}

			//grids subastas
			if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_PRESENCIAL)) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('presenciales'), 4), $fechaactual, $priority_lot);
			}
			if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_ONLINE)) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('subastas-online'), 4), $fechaactual, $priority_lot);
			}
			if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_PERMANENTE)) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('subastas-permanentes'), 4), $fechaactual, $priority_lot);
			}
			if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_VENTA_DIRECTA)) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('venta-directa'), 4), $fechaactual, $priority_lot);
			}
			if ($subastas->contains('subc_sub', FgSub::SUBC_SUB_HISTORICO)) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('subastas-historicas'), 4), $fechaactual, $priority_lot);
			}
			if ($subastas->where('subc_sub', FgSub::SUBC_SUB_HISTORICO)->contains('tipo_sub', FgSub::TIPO_SUB_PERMANENTE)) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('subastas-historicas-presenciales'), 4), $fechaactual, $priority_lot);
			}
			if ($subastas->where('subc_sub', FgSub::SUBC_SUB_HISTORICO)->contains('tipo_sub', FgSub::TIPO_SUB_ONLINE)) {
				$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('subastas-historicas-online'), 4), $fechaactual, $priority_lot);
			}

			//subastas (grid lotes, info subasta)
			foreach ($subastas as $subasta) {
				$url_lotes = ToolsServiceProvider::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
				$url_subasta = ToolsServiceProvider::url_info_auction($subasta->cod_sub, $subasta->name);

				$xml[] = $this->xmlWithUrl($url_subasta, $fechaactual, $priority_lot);
				$xml[] = $this->xmlWithUrl($url_lotes, $fechaactual, $priority_lot);
			}

			//grid lotes por categorias
			foreach ($categorias as $categoria) {
				if (config('app.gridLots', '') == "new") {
					$xml[] = $this->xmlWithUrl(route("category", ['keycategory' => $categoria->key_ortsec0]), $fechaactual, $priority_lot);
				} else {
					$xml[] = $this->xml($url, $lang, substr(RoutingServiceProvider::translateSeo('subastas') . "$categoria->key_ortsec0", 4), $fechaactual, $priority_lot);
				}
			}

			//lotes
			foreach ($lotes as $lote) {
				$url_lote = ToolsServiceProvider::url_lot($lote->sub_asigl0, $lote->id_auc_sessions, $lote->name, $lote->ref_asigl0, $lote->num_hces1, $lote->webfriend_hces1, !empty($lote->descweb_hces1) ? $lote->descweb_hces1 : $lote->titulo_hces1);
				$xml[] = $this->xmlWithUrl($url_lote, $fechaactual, $priority_lot);
			}

			foreach ($xml as $value) {
				$buffer .= $value;
			}
		}

		$buffer .= '</urlset>';

		$file_name = public_path("sitemap.xml");

		if (file_exists($file_name)) {
			$file = fopen($file_name, "w+");
		} else {
			$file = fopen($file_name, "a");
		}
		fwrite($file, $buffer);
		fclose($file);
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

		$showCloseLots = Config::get('app.close_lots_sitemap', false);

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
			])
			->when(!$showCloseLots, function ($query) {
				$query->where('FGASIGL0.CERRADO_ASIGL0', 'N');
			})
			->orderBy('sub_asigl0')
			->get();

		$categorias = FgOrtsec0::select('des_ortsec0', 'key_ortsec0')->getAllFgOrtsec0()->get();

		return [
			'pages' => $pages,
			'subastas' => $subastas,
			'lotes' => $lotes,
			'categorias' => $categorias
		];
	}

	private function xml($url, $key, $key_name, $fechaactual, $priority)
	{
		return "
           <url>
                <loc>" . $url . "/$key/" . $key_name . "</loc>
                <lastmod>" . $fechaactual . "</lastmod>
                <priority>" . $priority . "</priority>
            </url>
            ";
	}

	private function xmlWithUrl($url, $fechaactual, $priority)
	{
		return "
			<url>
				 <loc>" . $url . "</loc>
				 <lastmod>" . $fechaactual . "</lastmod>
				 <priority>" . $priority . "</priority>
			</url>
			";
	}
}
