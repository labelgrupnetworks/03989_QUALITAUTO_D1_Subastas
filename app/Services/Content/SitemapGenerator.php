<?php

namespace App\Services\Content;

use App\Http\Controllers\ContentController;
use App\Models\V5\FgSub;
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

			['pages' => $pages, 'subastas' => $subastas, 'lotes' => $lotes, 'categorias' => $categorias] = (new ContentController())->contentAvailable($idioma);

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
