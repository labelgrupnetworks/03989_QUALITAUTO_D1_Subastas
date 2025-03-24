<?php

namespace App\Http\Controllers\admin\contenido;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\Category;
use App\Models\CategorysBlog;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Blog_Lang;
use App\Models\V5\Web_Category_Blog;
use App\Models\V5\Web_Content_Page;
use App\Models\WebNewbannerModel;
use App\Services\admin\Content\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
	//añadir las variables del contructor
	public $categorysBlog;
	public $lang;

	public function __construct()
	{
		$this->categorysBlog = new CategorysBlog();
		$this->lang = Config::get('app.locales');

		$this->middleware('trimStrings');
		view()->share(['menu' => 'contenido']);
	}

	public function index()
	{
		$blogService = new BlogService();
		$blogs = $blogService->getAllPrincipalBlog();
		$categories = $this->categorysBlog->getCategorys();
		$categories = collect($categories)->sortBy('orden_category_blog')->toArray();

		$blogsWithoutCategory = $blogs->where('principalCategory', null);

		return view('admin::pages.contenido.blog.index', compact('blogs', 'categories', 'blogsWithoutCategory'));
	}

	public function create()
	{
		$blogService = new BlogService();
		$subSections = $blogService->getCategSubCateg(false, '0');
		$subSectionsCollection = collect($subSections)->sortBy('lin_ortsec1');

		$sections = $subSectionsCollection->pluck('des_ortsec0', 'lin_ortsec1');

		$sub_categ = $subSectionsCollection->map(function ($item) {
			$item->des_ortsec0 = " {$item->des_ortsec0} / {$item->des_sec}";
			return $item;
		});

		$categories = $blogService->getCategoriesLangByLocale();

		$data = [
			'sub_categ' => $sub_categ,
			'sec' => $sections,
			'idiomes' => $this->lang,
			'categories' => $categories,
			'categories_select' => $categories->pluck('title_category_blog_lang', 'id_category_blog'),
		];

		return View::make('admin::pages.contenido.blog.create', array('data' => $data));
	}

	/**
	 * @todo
	 * [] - Validar campos
	 * [] - Validar que no exista la url
	 */
	public function store(Request $request)
	{
		$max_id = Web_Blog::withoutGlobalScope('emp')->max('id_web_blog');
		$new_id = $max_id + 1;

		Web_Blog::create([
			'id_web_blog' => $new_id,
			'title_web_blog' => $request->title,
			'lot_categories_web_blog' => implode(',', $request->sec ?? []),
			'lot_sub_categories_web_blog' => implode(',', $request->sub_categ ?? []),
			'publication_date_web_blog' => $request->date,
			'primary_category_web_blog' => $request->categ_blog_principal,
			'author_web_blog' => $request->author
		]);

		$relationalCategories = $request->input('categ_blog', []);
		if (!in_array($request->categ_blog_principal, $relationalCategories)) {
			$relationalCategories[] = $request->categ_blog_principal;
		}

		$this->addRelationsBlog($new_id, $relationalCategories);

		$langs = array_keys($this->lang);

		foreach ($langs as $lang) {
			$lang = strtoupper($lang);

			$url_web_blog_lang = !empty($request->{"url_$lang"}) ? $request->{"url_$lang"} : $request->{"title_$lang"};
			$url_web_blog_lang = Str::slug($url_web_blog_lang);

			Web_Blog_Lang::create([
				'idblog_web_blog_lang' => $new_id,
				'lang_web_blog_lang' => $lang,
				'id_web_blog_lang' => Web_Blog_Lang::max('id_web_blog_lang') + 1,
				'titulo_web_blog_lang' => $request->{"title_$lang"},
				'cita_web_blog_lang' => $request->{"cita_$lang"},
				'texto_web_blog_lang' => $request->{"text_$lang"},
				'metatitle_web_blog_lang' => $request->{"meta_title_$lang"},
				'metadescription_web_blog_lang' => $request->{"meta_desc_$lang"},
				'url_web_blog_lang' => $url_web_blog_lang
			]);
		}

		//save the image file
		if ($request->hasFile('url_img')) {
			$file = $request->file('url_img');
			$blog = Web_Blog::where('id_web_blog', $new_id)->first();
			$blog->setMedia($file);
		}

		return redirect()->route('admin.contenido.blog.edit', ['id' => $new_id])->with('success', ['Noticia creada correctamente']);
	}

	public function show(Web_Blog $webBolg)
	{
		dd('show');
	}

	public function edit($id)
	{
		$blogService = new BlogService();
		$subSections = $blogService->getCategSubCateg(false, '0');
		$subSectionsCollection = collect($subSections)->sortBy('lin_ortsec1');

		$sections = $subSectionsCollection->pluck('des_ortsec0', 'lin_ortsec1');

		$sub_categ = $subSectionsCollection->map(function ($item) {
			$item->des_ortsec0 = " {$item->des_ortsec0} / {$item->des_sec}";
			return $item;
		});

		$categories = $blogService->getCategoriesLangByLocale();

		//obtener datos de la noticia por idioma
		$inf_noticia['lang'] = $blogService->getNoticiasAllLangs($id)->keyBy('lang_web_blog_lang');
		$ids = $inf_noticia['lang']->pluck('id_web_blog_lang')->toArray();

		$contents = Web_Content_Page::WhereCustomRelation(Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG, $ids)
			->with(['contentHtml', 'contentResource'])
			->orderBy('order_content_page')->get();


		$inf_noticia['lang']->each(function ($item) use ($contents, $categories) {
			$item->contents = $contents->where('rel_id_content_page', $item->id_web_blog_lang);
			$categoryUrl = collect($categories)->where('id_category_blog', $item->primary_category_web_blog)->first()->url_category_blog_lang ?? '';
			$item->link = "/es/blog/$categoryUrl/{$item->url_web_blog_lang}";
		});

		//obtener relaciones de lotes
		$noticiaLocale = $inf_noticia['lang']->where('lang_web_blog_lang', mb_strtoupper(Config::get('app.locale')))->first();
		$inf_noticia['lot_categories_web_blog'] = $noticiaLocale->lot_categories_web_blog ? explode(",", $noticiaLocale->lot_categories_web_blog) : [];
		$inf_noticia['lot_sub_categories_web_blog'] = $noticiaLocale->lot_sub_categories_web_blog ? explode(",", $noticiaLocale->lot_sub_categories_web_blog) : [];

		//obtener noticias relacionadas
		$categ_blog = $blogService->getNoticiaRelCategory($id);
		$inf_noticia['categories'] = [];
		foreach ($categ_blog as $value) {
			$inf_noticia['categories'][] = $value->idcat_web_blog_rel_category;
		}

		$data = [
			'sub_categ' => $sub_categ,
			'sec' => $sections,
			'idiomes' => $this->lang,
			'categories' => $categories,
			'categories_select' => collect($categories)->pluck('title_category_blog_lang', 'id_category_blog'),
			'noticia' => $inf_noticia,
			'banner' => $this->getBannersInfoByContents($contents),
			'images' => $this->getAssets($id)
		];

		return View::make('admin::pages.contenido.blog.edit', array('data' => $data));
	}

	public function update(Request $request, $id)
	{
		Web_Blog::where('id_web_blog', $id)->update([
			'title_web_blog' => $request->title,
			'lot_categories_web_blog' => implode(',', $request->sec ?? []),
			'lot_sub_categories_web_blog' => implode(',', $request->sub_categ ?? []),
			'publication_date_web_blog' => $request->date,
			'primary_category_web_blog' => $request->categ_blog_principal,
			'author_web_blog' => $request->author
		]);

		$relationalCategories = $request->input('categ_blog', []);
		if (!in_array($request->categ_blog_principal, $relationalCategories)) {
			$relationalCategories[] = $request->categ_blog_principal;
		}

		$this->addRelationsBlog($id, $relationalCategories);

		$langs = array_keys($this->lang);
		$webBlogsLang = Web_Blog_Lang::where('idblog_web_blog_lang', $id)->get();

		foreach ($langs as $lang) {
			$lang = strtoupper($lang);
			$webBlogLang = $webBlogsLang->where('lang_web_blog_lang', $lang)->first();

			$url_web_blog_lang = !empty($request->{"url_$lang"}) ? $request->{"url_$lang"} : $request->{"title_$lang"};
			$url_web_blog_lang = Str::slug($url_web_blog_lang);

			$updateData = [
				'titulo_web_blog_lang' => $request->{"title_$lang"},
				'cita_web_blog_lang' => $request->{"cita_$lang"},
				'texto_web_blog_lang' => $request->{"text_$lang"},
				'metatitle_web_blog_lang' => $request->{"meta_title_$lang"},
				'metadescription_web_blog_lang' => $request->{"meta_desc_$lang"},
				'url_web_blog_lang' => $url_web_blog_lang
			];

			if ($webBlogLang !== null) {
				Web_Blog_Lang::where('id_web_blog_lang', $webBlogLang->id_web_blog_lang)->update($updateData);
			} else {
				Web_Blog_Lang::create(array_merge($updateData, [
					'idblog_web_blog_lang' => $id,
					'lang_web_blog_lang' => $lang,
					'id_web_blog_lang' => Web_Blog_Lang::max('id_web_blog_lang') + 1
				]));
			}
		}

		return redirect()->back()->with('success', ['Noticia actualizada correctamente']);
	}

	public function destroy($id)
	{
		/**
		 * Eliminar relaciones
		 * [] - Web_Blog_Lang
		 * [] - WEB_BLOG_REL_CATEGORY (addRelationsBlog con array vacio)
		 * [] - Web_Content_Page
		 * 	[] - Web_Content_Resource
		 * 		[] - Eliminar archivos
		 *  [] - Web_Content_Html
		 * [] - Web_Blog
		 * [] - Eliminar archivos
		 * [] - Eliminar carpeta
		 */
		$webBlog = Web_Blog::with('languages')->where('id_web_blog', $id)->first();
		dd($webBlog);


		$this->addRelationsBlog($id, []);

		Web_Blog_Lang::where('idblog_web_blog_lang', $id)->delete();

		//No!, no se debe pasar el id de blog, si no el id de todos los web_blog_langs en un array
		Web_Content_Page::WhereCustomRelation(Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG, $id)->delete();



	}

	private function getForm($webBlog)
	{
		$form = [
			'title_web_blog' => [
				'label' => trans("admin-app.fields.title_web_blog"),
				'helped' => trans("admin-app.help_fields.title_web_blog"),
				'input' => FormLib::text('title_web_blog', 1, old('title_web_blog', $webBlog->title_web_blog)),
			],
			'publication_date_web_blog' => [],
			'publication_date_web_blog' => FormLib::date('publication_date_web_blog', 1, old('publication_date_web_blog', $webBlog->publication_date_web_blog ?? now()->format('Y-m-d'))),
			'ahutor_web_blog' => FormLib::text('ahutor_web_blog', 0, old('ahutor_web_blog', $webBlog->ahutor_web_blog)),
			'primary_category_web_blog' => FormLib::select('primary_category_web_blog', 1, old('primary_category_web_blog', $webBlog->primary_category_web_blog), Web_Category_Blog::pluck('title_category_blog')->toArray()),
			'img_web_blog' => [
				'input' => FormLib::file('img_web_blog', 0),
				'file' => $webBlog->img_web_blog
			],
			'lot_categories_web_blog' => '',
			'lot_sub_categories_web_blog' => '',
		];

		return $form;
	}

	public function storeFrontResourceBlog(Request $request, $id)
	{
		//validator file type image or video
		$validator = Validator::make($request->all(), [
			'file' => 'required|mimes:jpeg,jpg,png,gif,webp,mp4,mov,ogg,qt',
		]);

		$file = $request->hasFile('file') ? $request->file('file') : null;

		$response = [
			'status' => 'error',
			'code' => '400',
			'message' => 'Error al subir la imagen'
		];

		if (!$file->isValid() || !$validator->validate()) {
			return response()->json($response, 200);
		}

		$blog = Web_Blog::where('id_web_blog', $id)->first();
		$pathFile = $blog->setMedia($file);

		return response()->json([
			'status' => 'success',
			'data' => $pathFile,
			'message' => 'Imagen subida correctamente'
		], 200);
	}

	public function changeIsEnabledBlog(Request $request, $id)
	{
		Web_Blog_Lang::where('idblog_web_blog_lang', $id)->update([
			'enabled_web_blog_lang' => $request->isEnabled ? 1 : 0
		]);

		return response()->json([
			'status' => 'success',
			'message' => 'Noticia actualizada correctamente'
		]);
	}

	/**
	 * @param Collection $contents
	 * @return array
	 */
	private function getBannersInfoByContents($contents)
	{
		$bannersContent = $contents
			->where('type_content_page', '=', Web_Content_Page::TYPE_CONTENT_PAGE_BANNER)
			->where('type_id_content_page', '!=', null);

		$banners = WebNewbannerModel::whereIn('ID', $bannersContent->pluck('type_id_content_page'))->get();

		$bannerController = new BannerController();
		$bannerImages = $bannerController->bannerImage($banners);

		$bannersArray = $banners->each(function ($item) use ($bannerImages) {
			$item->image = $bannerImages[$item->id] ?? '';
		})->keyBy('id')->toArray();

		return $bannersArray;
	}

	/**
	 * @todo
	 * Mejorable
	 * Se pueden insertar todas las relaciones de una
	 * O crear una relación many to many y usar sync
	 */
	private function addRelationsBlog($id, $relCategories)
	{
		$blogService = new BlogService();
		$blogService->deleteRelationBlog($id);

		foreach ($relCategories ?? [] as $cate_blog) {
			$blogService->insertRelationBlog($id, $cate_blog);
		}
	}

	private function getAssets($id)
	{
		$path = "/img/blog/$id";
		$images = [];

		if (is_dir(getcwd() . $path)) {
			$images = array_slice(scandir(getcwd() . $path), 2);

			foreach ($images as $key => $value) {
				$images[$key] = config('app.url') . $path . '/' . $value;
			}
		}

		return $images;
	}
}
