<?php

namespace App\Http\Controllers;

use App\Services\Content\PageService;
use App\Services\Content\SitemapGenerator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
	protected PageService $pageService;

	public function __construct()
	{
		$this->pageService = new PageService();
	}

	public function getPagina($lang, $key)
	{
		$data = $this->pageService->getPage($key);
		if (empty($data)) {
			return abort(404);
		}

		$SEO_metas = new \stdClass();
		if (!empty($data->webnoindex_web_page) && $data->webnoindex_web_page == 1) {
			$SEO_metas->noindex_follow = true;
		} else {
			$SEO_metas->noindex_follow = false;
		}
		$SEO_metas->meta_title = $data->webmetat_web_page;
		$SEO_metas->meta_description = $data->webmetad_web_page;

		if (empty($_GET['modal'])) {
			$data = array(
				'data' => $data,
				'seo' => $SEO_metas,
				'lang' => $lang
			);

			return View::make('front::pages.page', array('data' => $data));
		} else {
			//return View::make('front::includes.ficha.modals_information', array('data' => $data->value));
			return $data->content_web_page;
		}
	}

	public function getDepartment($lang)
	{
		return $this->getPagina($lang, "departamentos");
	}

	public function siteMapPage()
	{
		$lang = strtoupper(Config::get('app.locale', 'es'));

		['pages' => $pages, 'subastas' => $subastas, 'lotes' => $lotes, 'categorias' => $categorias] = (new SitemapGenerator())->contentAvailable($lang);

		$subastas = $subastas->map(function ($subasta, $key) use ($lotes) {
			$subasta->lotes = $lotes->where('sub_asigl0', $subasta->cod_sub);
			return $subasta;
		});

		return view('front::pages.site_map', compact('subastas', 'pages', 'categorias'));
	}
}
