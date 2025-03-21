<?php

namespace App\Http\Controllers;

use App\Models\Sec;
use App\Models\V5\Web_Content_Page;
use App\Models\WebNewbannerItemModel;
use App\Models\WebNewbannerModel;
use App\Providers\RoutingServiceProvider;
use App\Services\Content\BlogService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class NoticiasController extends Controller
{

	public function index($lang, $key_categ = null)
	{
		$blogService = new BlogService();
		$noticias = $blogService->getAllNoticiasLang($key_categ);
		$categ = null;

		foreach ($noticias as $noticia) {
			$noticia->texto_web_blog_lang = strip_tags($noticia->texto_web_blog_lang);
			$noticia->url = RoutingServiceProvider::translateSeo("blog/{$noticia->url_category_blog_lang}/{$noticia->url_web_blog_lang}");
			$noticia->category_url = RoutingServiceProvider::translateSeo("blog/{$noticia->url_category_blog_lang}");
		}

		$SEO_metas = (object)[
			'meta_title' => trans('web.blog.blog_metatile'),
			'meta_description' => trans('web.blog.blog_metades'),
			'canonical' => route('blog.index', ['lang' => $lang])
		];

		if (!empty($key_categ)) {
			$categ = $blogService->getCategory($key_categ);

			$url_category_blog_lang = $categ->url_category_blog_lang ?? $key_categ;
			$SEO_metas->meta_title = $categ->metatit_category_blog_lang ?? $SEO_metas->meta_title;
			$SEO_metas->meta_description = $categ->meta_description ?? $SEO_metas->meta_description;
			$SEO_metas->canonical = route('blog.index', ['lang' => $lang, 'key_categ' => $url_category_blog_lang]);
		}

		$data = [
			'categ' => $categ,
			'categories' => $blogService->getCategoriesHasNews(),
			'noticias' => $noticias,
			'seo' => $SEO_metas
		];

		return View::make('front::pages.noticias.noticias', ['data' => $data]);
	}

	public function news($lang, $key_categ, $key_news)
	{
		$blogService = new BlogService();
		$sec = new Sec();

		$isAdmin = Session::has('user.admin');
		$categorys = $blogService->getCategory(null, !$isAdmin)
			->keyBy('id_category_blog_lang');

		$noticias = $blogService->getNoticia($key_categ, $key_news);

		if (empty($noticias) || empty($categorys[$noticias->primary_category_web_blog])) {
			exit(View::make('front::errors.404'));
		}

		$cod_sub = '0';
		$lot_categories = explode(",", $noticias->lot_categories_web_blog);
		$categorys_web = $sec->getOrtsecByOrtsec($cod_sub, $lot_categories);

		$noticias->cita_web_blog_lang =  '<div id="cita" class="post_text-special">' . $noticias->cita_web_blog_lang . '</div>';
		$noticias->texto_web_blog_lang = str_replace('[*CITA*]', $noticias->cita_web_blog_lang, $noticias->texto_web_blog_lang);

		$relationship_new = $blogService->getAllNoticiasRelacionadas($noticias->id_web_blog);

		$SEO_metas = new \stdClass();
		$SEO_metas->meta_title = $noticias->metatitle_web_blog_lang;
		$SEO_metas->meta_description = $noticias->metadescription_web_blog_lang;
		$SEO_metas->canonical =  $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];

		$contents = Web_Content_Page::with(['contentHtml'])
			->where([
				['table_rel_content_page', Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG],
				['rel_id_content_page', $noticias->id_web_blog_lang]
			])->orderBy('order_content_page')->get();

		$data = array(
			'news' => $noticias,
			'relationship_new' => $relationship_new,
			'categorys' => $categorys,
			'seo' => $SEO_metas,
			'categorys_web' => $categorys_web,
			'contents' => $contents
		);

		if (Config::get('app.new_blog', false)) {
			return view('front::pages.noticias.new_entrada', ['data' => $data]);
		}

		return View::make('front::pages.noticias.entrada', array('data' => $data));
	}

	public function eventBanner($ubicacion)
	{

		$theme = Config::get('app.theme');
		$emp = Config::get('app.emp');
		//$lang = strtoupper(Config::get('app.locale', 'ES'));
		$lang = 'ES';

		$banners = WebNewbannerModel::select('id', 'descripcion')
			->where([
				['ubicacion', $ubicacion],
				['activo', 1]
			])
			->orderBy('orden')->get();

		foreach ($banners as $banner) {

			$webNewbannerItem = WebNewbannerItemModel::select('id', 'texto')
				->where([
					['ID_WEB_NEWBANNER', $banner->id],
					['ACTIVO', 1],
					['LENGUAJE', $lang]
				])
				->orderBy('orden', 'desc')->first();

			if ($webNewbannerItem) {
				$banner->id_image = $webNewbannerItem->id;
				$banner->texto = $webNewbannerItem->texto;
				$imagePath = "/img/banner/$theme/$emp/$banner->id/$banner->id_image/$lang.jpg";
				if ($lang != 'ES' && !file_exists(public_path() . $imagePath)) {
					$imagePath = "/img/banner/$theme/$emp/$banner->id/$banner->id_image/ES.jpg";
				}

				$banner->url_image = $imagePath;
			}
		}

		return $banners;
	}

	public function museumPieces()
	{
		$banners = $this->eventBanner(WebNewbannerModel::UBICACION_MUSEO);
		return View::make('front::pages.noticias.mosaic_blog', compact('banners'));
	}

	public function events()
	{
		$banners = $this->eventBanner(WebNewbannerModel::UBICACION_EVENTO);
		return View::make('front::pages.noticias.events', compact('banners'));
	}



	public function event($lang, $id)
	{

		$theme = Config::get('app.theme');
		$emp = Config::get('app.emp');
		$lang = strtoupper($lang);

		//validate if id is number
		if (!is_numeric($id)) {
			exit(View::make('front::errors.404'));
		}

		$banner = WebNewbannerModel::select('id', 'descripcion')
			->where([
				['ubicacion', WebNewbannerModel::UBICACION_EVENTO],
				['id', $id],
				['activo', 1]
			])->first();

		if (!$banner) {
			exit(View::make('front::errors.404'));
		}

		$banner->images = WebNewbannerItemModel::select('id', 'texto')
			->where([
				['ID_WEB_NEWBANNER', $banner->id],
				['ACTIVO', 1],
				['LENGUAJE', 'ES']
			])
			->orderBy('orden', 'desc')->get()->pluck('texto', 'id');


		return View::make('front::pages.noticias.event', compact('banner'));
	}
}
