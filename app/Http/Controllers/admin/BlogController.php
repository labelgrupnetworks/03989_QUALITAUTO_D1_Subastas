<?php

namespace App\Http\Controllers\admin;

use App\DataTransferObjects\Content\BlogData;
use App\Http\Controllers\Controller;
use App\Models\CategorysBlog;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Blog_Lang;
use App\Services\admin\Content\BlogService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

/**
 * Solo utilizado por Subarna.
 * Si conseguimos que lo cambién al nuevo formato de blog, podemos eliminar este controlador y los modelos relacionados
 */
class BlogController extends Controller
{
	private $categorysBlog;
	private $lang;

	public function __construct()
	{
		$this->categorysBlog = new CategorysBlog();
		$this->lang = Config::get('app.locales');
		view()->share(['menu' => 'blog']);
	}

	public function index($id = null)
	{
		$blogService = new BlogService();

		$data = array();
		$sub_categ = array();
		$sec = array();

		$categorys = $blogService->getCategSubCategCollection();
		foreach ($categorys as $categ) {
			$sub_categ[$categ->cod_sec] = ucfirst(mb_strtolower($categ->des_sec));
			$sec[$categ->lin_ortsec1] = $categ->des_ortsec0;
		}

		$categorys = $blogService->getCategoriesLangByLocale();

		$all_categories = array();
		foreach ($categorys as $categ) {
			$all_categories[$categ->url_category_blog_lang] = $categ;
		}

		asort($all_categories);
		asort($sec);
		asort($sub_categ);
		$data = array('sub_categ' => $sub_categ, 'sec' => $sec, 'idiomes' => $this->lang, 'categories' => $all_categories);
		if (!empty($id)) {

			foreach ($this->lang as $key_alng => $lang) {

				$inf_noticia['lang'][strtoupper($key_alng)] = $blogService->getNoticiaLang($id, $key_alng);

				if (!empty($inf_noticia['lang'][strtoupper($key_alng)]->lot_categories_web_blog)) {
					$inf_noticia['lot_categories_web_blog'] = explode(",", $inf_noticia['lang'][strtoupper($key_alng)]->lot_categories_web_blog);
				}
				if (!empty($inf_noticia['lang'][strtoupper($key_alng)]->lot_sub_categories_web_blog)) {
					$inf_noticia['lot_sub_categories_web_blog'] = explode(",", $inf_noticia['lang'][strtoupper($key_alng)]->lot_sub_categories_web_blog);
				}
			}
			$categ_blog = $blogService->getNoticiaRelCategory($id);
			foreach ($categ_blog as $value) {
				$inf_noticia['categories'][] = $value->idcat_web_blog_rel_category;
			}

			$data['noticia'] = $inf_noticia;
		}

		return View::make('admin::pages.editBlog', array('data' => $data));
	}


	public function getBlogs(BlogService $blogService)
	{
		return View::make('admin::pages.blog', ['data' => $blogService->getAllBlogs()]);
	}

	public function getCategoryBlog()
	{
		$categorys = array();
		$categorys = $this->categorysBlog->getCategorys();
		return View::make('admin::pages.categoryBlog', array('data' => $categorys));
	}

	public function seeCategoryBlog($id = null)
	{
		$blogService = new BlogService();

		$categorys = [];
		if (!empty($id)) {
			$categorys_temp = $blogService->getCategoriesLangById($id);
			foreach ($categorys_temp as $categ) {
				$categorys[$categ->lang_category_blog_lang] = $categ;
			}
		}

		$data['categorys'] = $categorys;
		$data['idiomes'] = $this->lang;
		return View::make('admin::pages.editCategoryBlog', array('data' => $data));
	}

	public function EditBlogCategory()
	{
		$this->categorysBlog->id = Request::input('id');
		$this->categorysBlog->title = Request::input('title');
		$this->categorysBlog->orden = Request::input('orden');
		if (!empty(Request::input('orden'))) {
			$this->categorysBlog->orden = Request::input('orden');
		} else {
			$this->categorysBlog->orden = Request::input('id');
		}
		if (empty(Request::input('enabled'))) {
			$this->categorysBlog->enabled = 0;
		} else {
			$this->categorysBlog->enabled = 1;
		}
		if ($this->categorysBlog->id == 0) {

			$max_id = $this->categorysBlog->Max_Category_Blog();
			$this->categorysBlog->id = $max_id + 1;
			$this->categorysBlog->orden = $max_id + 1;

			/*
             * Si falla la inserción de cualquier idioma, realiza rollback de las operaciones anteriores,
             * no comitea hasta finalizar todas las operaciones
             */
			DB::transaction(function () {

				$this->categorysBlog->InsertCategoryBlog();
				foreach ($this->lang as $lang => $idiom) {
					$lang = strtoupper($lang);
					$this->categorysBlog->lang = $lang;
					$this->categorysBlog->name_category = trim(Request::input('name_' . $lang));
					$this->categorysBlog->title_category = trim(Request::input('title_' . $lang));
					$this->categorysBlog->url_category = trim(Request::input('url_' . $lang));
					$this->categorysBlog->metatit_category = trim(Request::input('meta_title_' . $lang));
					$this->categorysBlog->metades_category = trim(Request::input('meta_desc_' . $lang));
					$this->categorysBlog->metacont_category = trim(Request::input('meta_cont_' . $lang));
					$this->categorysBlog->InsertCategoryBlogLang();
				}
			});
		} else {
			$this->categorysBlog->UpdateCategoryBlog();
			foreach ($this->lang as $lang => $idiom) {
				$lang = strtoupper($lang);
				$this->categorysBlog->lang = $lang;
				$this->categorysBlog->name_category = trim(Request::input('name_' . $lang));
				$this->categorysBlog->title_category = trim(Request::input('title_' . $lang));
				$this->categorysBlog->url_category = trim(Request::input('url_' . $lang));
				$this->categorysBlog->metatit_category = trim(Request::input('meta_title_' . $lang));
				$this->categorysBlog->metades_category = trim(Request::input('meta_desc_' . $lang));
				$this->categorysBlog->metacont_category = trim(Request::input('meta_cont_' . $lang));
				$this->categorysBlog->UpdateCategoryBlogLang();
			}
		}

		return $this->categorysBlog->id;
	}

	public function EditBlog(HttpRequest $request)
	{
		$blog = BlogData::fromRequest($request);
		$blogService = new BlogService();

		if ($blog->id == 0) {
			$max_id = Web_Blog::max('id_web_blog');
			$blog->id = $max_id + 1;
			$blogService->insertBlog($blog);

			foreach (array_keys($this->lang) as $lang) {
				$idBlogLang = Web_Blog_Lang::max('id_web_blog_lang') + 1;
				$lang = strtoupper($lang);

				$blogService->insertBlogLang($request, $blog->id, $idBlogLang, $lang);
			}
		} else {
			$blogService->updateBlog($blog);

			foreach (array_keys($this->lang) as $lang) {
				$lang = strtoupper($lang);
				$blogService->updateBlogLang($request, $blog->id, $lang);
			}
		}

		$blogService->deleteRelationBlog($blog->id);

		$categ_blog = $request->input('cate_blog', []);
		foreach ($categ_blog as $cate_blog) {
			$blogService->insertRelationBlog($blog->id, $cate_blog);
		}

		return $blog->id;
	}
}
