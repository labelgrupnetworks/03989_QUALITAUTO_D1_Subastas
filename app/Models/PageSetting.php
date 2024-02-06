<?php

namespace App\Models;

use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgSub;
use App\Models\V5\Web_Page;
use Illuminate\Support\Facades\Config;

class PageSetting
{

	public $settings = [];

	private $auc_parameters = [
		'tipo_sub' => '',
		'subc_sub' => ''
	];
	private $config_menu_admin = [];

	public function __construct()
	{
		$this->config_menu_admin = Config::get('app.config_menu_admin');
	}

	public function getSettings(): array
	{
		if (empty(session()->all()['user'])) {
			return [];
		}

		$userSession = session()->all()['user'];
		$isAdmin = $userSession['admin'][0];

		if (!$isAdmin) return [];

		$routeName = request()->route()->getName();
		$routeParams = request()->route()->parameters();

		$settings = match (true) {
			($routeName == 'urlAuction' || $routeName == 'urlAuctionOld') => $this->gridLotSettings($routeParams),
			str_contains($routeName, 'subastas.') => $this->gridAuctionsSettings(),
			($routeName == 'subasta.lote.ficha') => $this->lotFichaSettings($routeParams),
			($routeName == 'urlAuctionInfo' || $routeName == 'subasta.indice') => $this->auctionInfoSettings($routeParams),
			($routeName == 'category') => $this->categorySettings($routeParams),
			($routeName == 'allCategories') => $this->allCategoriesSettings(),
			($routeName == 'calendar') => $this->calendarSettings(),
			($routeName == 'staticPage') => $this->staticPagesSettings($routeParams),
			($routeName == 'faqs_page') => $this->faqsSettings(),
			($routeName == 'blog.index') => $this->blogsSettings(),
			($routeName == 'blog.news') => $this->blogSettings($routeParams),
			($routeName == 'artists') => $this->artistsSettings(),
			($routeName == 'artist') => $this->artistSettings($routeParams),
			default => [],
		};

		// This function is used to view in page the route and route parameters
		// $this->tempDumpRouteData();
		return array_merge($this->settings, array_filter($settings));
	}

	public function addSettings(array $settings)
	{
		$this->settings = array_merge($this->settings, $settings);
	}

	#region Subastas

	private function gridAuctionsSettings()
	{
		$auc_params = explode('.', $this->getRouteName())[1] ?? '';
		$isFinished = isset((request()->query())['finished']) ? (request()->query())['finished'] : null;
		$this->auc_parameters = $this->aucQueryParams($auc_params, $isFinished);
		$canAccessAuc = in_array('newsubastas', $this->config_menu_admin);
		$canAccessAucCon = in_array('concursal', $this->config_menu_admin);
		return [
			// Subastas
			$canAccessAuc ? $this->newRoute('edit_auctions', route('subastas.index', ['tipo_sub' => $this->auc_parameters['tipo_sub'], 'subc_sub' => $this->auc_parameters['subc_sub']])) : null,
			// Subastas Concursales
			$canAccessAucCon ? $this->newRoute('edit_concurs_auctions', route('subastas_concursales.index', ['tipo_sub' => $this->auc_parameters['tipo_sub'], 'subc_sub' => $this->auc_parameters['subc_sub']])) : null,
		];
	}

	private function auctionInfoSettings(array $params)
	{
		$canAccessAuc = in_array('newsubastas', $this->config_menu_admin);
		$canAccessAucCon = in_array('concursal', $this->config_menu_admin);
		return [
			// Subastas
			$canAccessAuc ? $this->newRoute('edit_auctions', route('subastas.index', ['tipo_sub' => $this->auc_parameters['tipo_sub'], 'subc_sub' => $this->auc_parameters['subc_sub']])) : null,
			$canAccessAuc ? $this->newRoute('edit_auction', route('subastas.edit', ['subasta' => $params['cod']])) : null,
			$canAccessAuc ? $this->newRoute('edit_lots', route('subastas.show', ['subasta' => $params['cod']])) : null,
			// Subastas Concursales
			$canAccessAucCon ? $this->newRoute('edit_concurs_auctions', route('subastas_concursales.index', ['tipo_sub' => $this->auc_parameters['tipo_sub'], 'subc_sub' => $this->auc_parameters['subc_sub']])) : null,
			$canAccessAucCon ? $this->newRoute('edit_concurs_auction', route('subastas_concursales.edit', ['subasta' => $params['cod']])) : null,
			$canAccessAucCon ? $this->newRoute('edit_concurs_lots', route('subastas_concursales.show', ['subasta' => $params['cod']])) : null,
		];
	}

	#endregion

	#region Lotes

	private function gridLotSettings(array $params)
	{
		if (empty($params['cod'])) {
			return [];
		}
		$canAccessAuc = in_array('newsubastas', $this->config_menu_admin);
		$canAccessAucCon = in_array('concursal', $this->config_menu_admin);
		return [
			// Subastas
			$canAccessAuc ? $this->newRoute('edit_auction', route('subastas.edit', ['subasta' => $params['cod']])) : null,
			$canAccessAuc ? $this->newRoute('edit_lots', route('subastas.show', ['subasta' => $params['cod']])) : null,
			// Subastas Concursales
			$canAccessAucCon ? $this->newRoute('edit_concurs_auction', route('subastas_concursales.edit', ['subasta' => $params['cod']])) : null,
			$canAccessAucCon ? $this->newRoute('edit_concurs_lots', route('subastas_concursales.show', ['subasta' => $params['cod']])) : null,
		];
	}

	private function lotFichaSettings(array $params)
	{
		$canAccessAuc = in_array('newsubastas', $this->config_menu_admin);
		$canAccessAucCon = in_array('concursal', $this->config_menu_admin);
		return [
			// Subastas
			$canAccessAuc ? $this->newRoute('edit_auction', route('subastas.edit', ['subasta' => $params['cod']])) : null,
			$canAccessAuc ? $this->newRoute('edit_lots', route('subastas.show', ['subasta' => $params['cod']])) : null,
			$canAccessAuc ? $this->newRoute('edit_lot', route('subastas.lotes.edit', ['cod_sub' => $params['cod'], 'lote' => $params['ref']])) : null,
			// Subastas Concursales
			$canAccessAucCon ? $this->newRoute('edit_concurs_auction', route('subastas_concursales.edit', ['subasta' => $params['cod']])) : null,
			$canAccessAucCon ? $this->newRoute('edit_concurs_lots', route('subastas_concursales.show', ['subasta' => $params['cod']])) : null,
			$canAccessAucCon ? $this->newRoute('edit_concurs_lot', route('subastas_concursales.lotes_concursales.edit', ['cod_sub' => $params['cod'], 'lote' => $params['ref']])) : null,
		];
	}

	#endregion

	#region Categorías

	private function categorySettings(array $params)
	{
		$canAccessAuc = in_array('newsubastas', $this->config_menu_admin);
		$canAccessAucCon = in_array('concursal', $this->config_menu_admin);
		$canAccess = !in_array('noCategories', $this->config_menu_admin);
		$id_category = (new FgOrtsec0())->getOrtsec0LinFromKey($params['keycategory']);
		return [
			$canAccess ? $this->newRoute('edit_categories', route('category.index')) : null,
			$canAccess ? $this->newRoute('edit_category', route('category.edit', ['idcategory' => $id_category])) : null,
			// Subastas
			$canAccessAuc ? $this->newRoute('edit_auctions', route('subastas.index')) : null,
			// Subastas Concursales
			$canAccessAucCon ? $this->newRoute('edit_concurs_auctions', route('subastas_concursales.index')) : null,
		];
	}

	private function allCategoriesSettings()
	{
		$canAccessAuc = in_array('newsubastas', $this->config_menu_admin);
		$canAccessAucCon = in_array('concursal', $this->config_menu_admin);
		$canAccessCat = !in_array('noCategories', $this->config_menu_admin);
		return [
			$canAccessCat ? $this->newRoute('edit_categories', route('category.index')) : null,
			// Subastas
			$canAccessAuc ? $this->newRoute('edit_auctions', route('subastas.index')) : null,
			// Subastas Concursales
			$canAccessAucCon ? $this->newRoute('edit_concurs_auctions', route('subastas_concursales.index')) : null,
		];
	}

	#endregion

	#region Calendario

	private function calendarSettings()
	{
		$canAccess = in_array('calendar', $this->config_menu_admin);
		return [
			$canAccess ? $this->newRoute('edit_resources_calendar', route('resources.index', ['see' => 'C'])) : null,
			$canAccess ? $this->newRoute('edit_banner_calendar', route('banner.index', ['see' => 'C'])) : null,
		];
	}

	#endregion

	#region Static Pages

	private function staticPagesSettings($params)
	{
		$id_page = Web_Page::getPageIDfromKey($params['pagina']);
		$canAccess = in_array('dev', $this->config_menu_admin);
		return [
			$canAccess ? $this->newRoute('edit_static_pages', route('content.index')) : null,
			$canAccess ? $this->newRoute('edit_static_page', route('content.page', ['id' => $id_page])) : null,
		];
	}

	#endregion

	#region Faqs

	private function faqsSettings()
	{
		$lang = $this->getLangFromURL();
		$canAccess = in_array('faqs', $this->config_menu_admin);
		return [
			$canAccess ? $this->newRoute('edit_faqs', route('admin.faqs.index', ['lang' => $lang])) : null,
		];
	}

	#endregion

	#region Blog

	private function blogsSettings()
	{
		$canAccess = !empty(array_intersect(['blog_cms', 'dev'], $this->config_menu_admin));
		return [
			$canAccess ? $this->newRoute('edit_blog', route('admin.contenido.blog.index')) : null,
		];
	}

	private function blogSettings(array $params)
	{
		$canAccess = !empty(array_intersect(['blog_cms', 'dev'], $this->config_menu_admin));
		if (!$canAccess) {
			return [];
		}

		$blog = new Blog();
		$blog->lang = strtoupper(Config::get('app.locale'));
		$news = $blog->getNoticia($params['key_categ'], $params['key_news']);

		return [
			$canAccess ? $this->newRoute('edit_blogs', route('admin.contenido.blog.index')) : null,
			$canAccess ? $this->newRoute('edit_blog_post', route('admin.contenido.blog.edit', ['id' => $news->id_web_blog])) : null,
		];
	}

	#endregion

	#region Artists

	private function artistsSettings()
	{
		$canAccess = in_array('artist', $this->config_menu_admin);
		return [
			$canAccess ? $this->newRoute('edit_artists', route('artist.index')) : null,
		];
	}

	private function artistSettings(array $params)
	{
		$canAccess = in_array('artist', $this->config_menu_admin);
		return [
			$canAccess ? $this->newRoute('edit_artists', route('artist.index')) : null,
			$canAccess ? $this->newRoute('edit_artist', route('artist.edit', ['artist' => $params['idArtist']])) : null,
		];
	}

	#endregion

	#region Making routes

	private function newRoute(string $name, string $url, array $name_val = [])
	{
		return [
			'name' => $name,
			'name_val' => $name_val,
			'url' => $url,
		];
	}

	private function getRouteName()
	{
		return request()->route()->getName();
	}

	#endregion

	public function tempDumpRouteData()
	{
		dump(request()->route()->uri, request()->route()->getName(), request()->route()->parameters(), request()->query());
	}

	#region Helpers

	private function aucQueryParams(string $params, bool|null $isFinished = null): array
	{
		$paramsExploded = explode('_', $params);

		$aucParams = [
			'subc_sub' => $isFinished !== null && $isFinished ? FgSub::SUBC_SUB_HISTORICO : '',
			'tipo_sub' => '',
		];

		if (count($paramsExploded) == 1) {
			$paramSubc = $this->calculateSubcSub($params);
			$paramTipo = $this->calculateTipoSub($params);
			$aucParams['subc_sub'] = $paramSubc ? $paramSubc : $aucParams['subc_sub'];
			$aucParams['tipo_sub'] = $paramTipo ? $paramTipo : $aucParams['tipo_sub'];
			return $aucParams;
		}

		if ($paramsExploded[0] == 'venta' || $paramsExploded[1] == 'oferta') {
			$paramTipo = $this->calculateTipoSub($params);
			$aucParams['tipo_sub'] = $paramTipo ? $paramTipo : $aucParams['tipo_sub'];
			return $aucParams;
		}

		$aucParams['subc_sub'] = $this->calculateSubcSub($paramsExploded[0]);
		$aucParams['tipo_sub'] = $this->calculateTipoSub($paramsExploded[1]);

		return $aucParams;
	}

	private function calculateSubcSub(string $subc_sub): string
	{
		switch ($subc_sub) {
			case 'activas':
				return FgSub::SUBC_SUB_ACTIVO;
			case 'historicas':
				return FgSub::SUBC_SUB_HISTORICO;
			default:
				return '';
		}
	}

	private function calculateTipoSub(string $tipo_sub): string
	{
		switch ($tipo_sub) {
			case 'presenciales':
				return FgSub::TIPO_SUB_PRESENCIAL;
			case 'online':
				return FgSub::TIPO_SUB_ONLINE;
			case 'venta_directa':
				return FgSub::TIPO_SUB_VENTA_DIRECTA;
			case 'permanentes':
				return FgSub::TIPO_SUB_PERMANENTE;
			case 'especiales':
				return FgSub::TIPO_SUB_ESPECIAL;
			case 'haz_oferta':
				return FgSub::TIPO_SUB_MAKE_OFFER;
			default:
				return '';
		}
	}

	private function getLangFromURL(): string
	{
		$uri = request()->route()->uri;
		$uri = explode('/', $uri);
		return $uri[0];
	}

	#endregion
}
