<?php

namespace App\Services\admin;

use App\Models\WebNewbannerModel;
use Illuminate\Support\Facades\Config;

class AdminMenuService
{

	/**
	 * Secciones del menú de administración por defecto
	 */
	private function getSections()
	{
		return [
			'usuarios' => [
				'id' => 'usuarios',
				'icon' => 'person',
				'label' => 'users',
				'sub_sections' => [
					['id' => 'clientes', 'icon' => 'people', 'label' => 'users', 'route' => route('clientes.index')],
					['id' => 'newsletter_table', 'icon' => 'envelope', 'label' => 'newsletter', 'route' => route('newsletter.index')],
					['id' => 'subConditions', 'icon' => 'check-square', 'label' => 'accepted_conditions', 'route' => route('subasta_conditions.index')],
				],
			],
			'subastas' => [
				'id' => 'subastas',
				'icon' => 'hammer',
				'label' => 'auctions',
				'sub_sections' => [
					['id' => 'newsubastas', 'icon' => 'hammer', 'label' => 'auctions', 'route' => route('subastas.index')],
					['id' => 'stock', 'icon' => 'box', 'label' => 'stock', 'route' => route('subastas.stock.index')],
					['id' => 'ordenLotesDestacados', 'icon' => 'star', 'label' => 'featured_sales', 'route' => route('subastas.lotes.order_destacadas_edit')],
					['id' => 'concursal', 'icon' => 'building', 'label' => 'auction_concursal', 'route' => route('subastas_concursales.index')],
					['id' => 'reports', 'icon' => 'file-text', 'label' => 'reports', 'route' => route('subasta.reports.index')],
					['id' => 'licits', 'icon' => 'gavel', 'label' => 'licits', 'route' => '/admin/licit'],
					['id' => 'orders', 'icon' => 'clipboard-list', 'label' => 'orders', 'route' => route('orders.index')],
					['id' => 'bids', 'icon' => 'hand-holding-usd', 'label' => 'bids', 'route' => route('admin.bids.index')],
					['id' => 'awards', 'icon' => 'trophy', 'label' => 'awards', 'route' => '/admin/award'],
					['id' => 'lotsNotAwards', 'icon' => 'exclamation-triangle', 'label' => 'not_awards', 'route' => route('not_award.index')],
					['id' => 'categories', 'icon' => 'folder', 'label' => 'categories', 'route' => '/admin/category'],
					['id' => 'subcategories', 'icon' => 'folder-open', 'label' => 'subcategories', 'route' => '/admin/subcategory'],
					['id' => 'escalados', 'icon' => 'expand', 'label' => 'scaleds', 'route' => '/admin/escalado'],
					['id' => 'visibilidad', 'icon' => 'eye', 'label' => 'visibility', 'route' => route('visibilidad.index')],
					['id' => 'favorites', 'icon' => 'heart', 'label' => 'favorites', 'route' => '/admin/favoritos'],
				],
			],
			'producto' => [
				'id' => 'producto',
				'icon' => 'cube',
				'label' => 'products',
				'sub_sections' => [
					['id' => 'articles', 'icon' => 'shopping-cart', 'label' => 'articles', 'route' => route('articles.index')],
					['id' => 'artist', 'icon' => 'user', 'label' => 'artist', 'route' => route('artist.index')],
				],

			],
			'facturacion' => [
				'id' => 'facturacion',
				'icon' => 'receipt-cutoff',
				'label' => 'facturation',
				'sub_sections' => [
					['id' => 'proveedores', 'icon' => 'truck', 'label' => 'providers', 'route' => route('providers.index')],
					['id' => 'pedidos', 'icon' => 'receipt', 'label' => 'pedidos', 'route' => route('pedidos.index')],
					['id' => 'facturacion', 'icon' => 'credit-card', 'label' => 'bills', 'route' => route('bills.index')],
					['id' => 'depositos', 'icon' => 'warehouse', 'label' => 'deposits', 'route' => route('deposito.index', ['menu' => 'subastas'])],
					['id' => 'nfts', 'icon' => 'file-code', 'label' => 'nfts', 'route' => route('nft.index')],
					['id' => 'credito', 'icon' => 'credit-card', 'label' => 'credit', 'route' => route('credito.index')],
				],
			],
			'content' => [
				'id' => 'content',
				'icon' => 'palette',
				'label' => 'content',
				'sub_sections' => [
					['id' => 'bloque', 'icon' => 'columns', 'label' => 'blocks', 'route' => '/admin/bloque'],
					['id' => 'newbanner', 'icon' => 'image', 'label' => 'banners', 'route' => '/admin/newbanner'],
					['id' => 'newbannerHome', 'icon' => 'image', 'label' => 'home_banners', 'route' => '/admin/newbanner/ubicacionhome'],
					['id' => 'event_museum', 'icon' => 'calendar-event', 'label' => 'museum_pieces', 'route' => route('event.index', ['ubicacion' => WebNewbannerModel::UBICACION_MUSEO])],
					['id' => 'events', 'icon' => 'calendar-event', 'label' => 'events', 'route' => route('event.index', ['ubicacion' => WebNewbannerModel::UBICACION_EVENTO])],
					['id' => 'static_pages', 'icon' => 'file-alt', 'label' => 'static_pages', 'route' => '/admin/static-pages'],
					['id' => 'edit_emails', 'icon' => 'envelope', 'label' => 'email_editor', 'route' => route('emails.index')],
					['id' => 'blog_cms', 'icon' => 'blog', 'label' => 'blog', 'route' => route('admin.contenido.blog.index')],
					['id' => 'uploads', 'icon' => 'upload', 'label' => 'uploads', 'route' => route('admin.contenido.uploads.index')],
					['id' => 'articles_resources', 'icon' => 'folder', 'label' => 'articles', 'route' => '/admin/resources?see=A'],
					['id' => 'articles_banner', 'icon' => 'folder', 'label' => 'news', 'route' => '/admin/banner?see=N'],
					['id' => 'calendar', 'icon' => 'calendar', 'label' => 'calendar', 'route' => '/admin/banner?see=C'],
					['id' => 'calendar_resources', 'icon' => 'folder', 'label' => 'calendar', 'route' => '/admin/resources?see=C'],
					['id' => 'newemail', 'icon' => 'envelope', 'label' => 'emails', 'route' => '/admin/email'],
					['id' => 'content_page', 'icon' => 'desktop', 'label' => 'web_content', 'route' => '/admin/content'],
					['id' => 'faq', 'icon' => 'question-circle', 'label' => 'faq', 'route' => '/admin/faqs/es'],
					['id' => 'traducciones', 'icon' => 'language', 'label' => 'translates', 'route' => '/admin/traducciones'],
					['id' => 'traducciones_search', 'icon' => 'search', 'label' => 'translation_searcher', 'route' => '/admin/traducciones/search'],
					['id' => 'new_calendar', 'icon' => 'calendar', 'label' => 'calendar', 'route' => '/admin/calendar'],
					['id' => 'emails_clients', 'icon' => 'envelope', 'label' => 'emails_log', 'route' => route('adminemails.showlog')],
					['id' => 'blog', 'icon' => 'blog', 'label' => 'entries', 'route' => '/admin/blog-admin'],
					['id' => 'blog_categories', 'icon' => 'folder', 'label' => 'categories', 'route' => '/admin/category-blog'],
					['id' => 'banner_resource_a', 'icon' => 'folder', 'label' => 'resources', 'route' => '//admin/resources?see=all'],
					['id' => 'banner_resource_b', 'icon' => 'folder', 'label' => 'banners', 'route' => '/admin/banner?see=B'],
				],
			],
			'extra' => [
				'id' => 'extra',
				'icon' => 'plus',
				'label' => 'extra',
				'sub_sections' => [
					['id' => 'bi', 'icon' => 'chart-bar', 'label' => 'bi', 'route' => '/admin/bi/report/categoryAwardsSales?years[]=' . date('Y')],
				],
			],
			'configuracion' => [
				'id' => 'configuracion',
				'icon' => 'gear',
				'label' => 'general_config',
				'sub_sections' => [
					['id' => 'config', 'icon' => 'gear', 'label' => 'general_config', 'route' => '/admin/configuracion'],
					['id' => 'thumbs', 'icon' => 'image', 'label' => 'thumbs', 'route' => route('admin.thumbs.index')],
					['id' => 'test-auctions', 'icon' => 'hammer', 'label' => 'test_auctions', 'route' => route('admin.test-auctions.index')],
					['id' => 'jobs', 'icon' => 'cogs', 'label' => 'jobs', 'route' => route('admin.jobs.index')],
					['id' => 'cache', 'icon' => 'database', 'label' => 'cache', 'route' => '/admin/cache'],
					['id' => 'logs', 'icon' => 'file-alt', 'label' => 'logs', 'route' => '/admin/log-viewer'],
					['id' => 'mesures', 'icon' => 'chart-bar', 'label' => 'statistics', 'route' => '/admin/mesures'],
					['id' => 'disk-status', 'icon' => 'hdd', 'label' => 'disk_status', 'route' => route('admin.disk-status.index')],
				],
			],
		];
	}

	/**
	 * Obtiene las subsecciones activas del menú de administración
	 *
	 * @return array
	 */
	private function getActiveSubsections()
	{
		return Config::get('app.config_menu_admin', []);
	}

	/**
	 * Filtramos las secciones del menú para mostrar solo las que están activas
	 *
	 * @return \Illuminate\Support\Collection
	 */
	private function getFilteredMenu()
	{
		return collect($this->getSections())
			->transform(function ($section) {
				if (Config::get('app.env') === 'local') {
					return $section;
				}

				$section['sub_sections'] = $this->getFilteredSubsections($section);
				return $section;
			})
			->filter(function ($section) {
				return !empty($section['sub_sections']);
			});
	}

	/**
	 * Filtra las subsecciones de una sección específica del menú
	 *
	 * @param array $section
	 * @return array
	 */
	private function getFilteredSubsections($section)
	{
		$activeSubsections = $this->getActiveSubsections();
		return array_filter($section['sub_sections'], function ($subSection) use ($activeSubsections) {
			return in_array($subSection['id'], $activeSubsections);
		});
	}

	/**
	 * Retrieves the menu items for the admin panel.
	 *
	 * @return array
	 */
	public function getMenuItems()
	{
		return $this->getFilteredMenu();
	}
}
