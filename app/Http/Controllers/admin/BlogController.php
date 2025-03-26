<?php

namespace App\Http\Controllers\admin;

use App\DataTransferObjects\Content\BlogData;
use App\Http\Controllers\Controller;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Blog_Lang;
use App\Models\V5\Web_Category_Blog;
use App\Models\V5\Web_Category_Blog_Lang;
use App\Services\admin\Content\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

/**
 * Solo utilizado por Subarna.
 * Si conseguimos que lo cambién al nuevo formato de blog, podemos eliminar este controlador y los modelos relacionados
 */
class BlogController extends Controller
{
	private $lang;

	public function __construct()
	{
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
		$categorys = Web_Category_Blog::get();
		return View::make('admin::pages.categoryBlog', ['data' => $categorys]);
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

	public function EditBlogCategory(Request $request)
	{
		$categoryBlog = [
			'id_category_blog' => $request->input('id'),
			'title_category_blog' => $request->input('title'),
			'orden_category_blog' => $request->input('orden', $request->input('id')),
			'enable_category_blog' => !empty($request->input('enabled', 0)) ? 1 : 0
		];

		if (empty($request->input('id'))) {

			$maxId = Web_Category_Blog::max('id_category_blog');
			$categoryBlog['id_category_blog'] = $maxId + 1;
			$categoryBlog['orden_category_blog'] = $maxId + 1;

			DB::transaction(function () use ($categoryBlog, $request) {

				Web_Category_Blog::create($categoryBlog);

				foreach (array_keys($this->lang) as $lang) {
					$lang = strtoupper($lang);

					$categoryBlogLang = [
						'id_category_blog_lang' => $categoryBlog['id_category_blog'],
						'lang_category_blog_lang' => strtoupper($lang),
						'name_category_blog_lang' => $request->input("name_$lang"),
						'title_category_blog_lang' => $request->input("title_$lang"),
						'url_category_blog_lang' => $request->input("url_$lang"),
						'metatit_category_blog_lang' => $request->input("meta_title_$lang"),
						'metades_category_blog_lang' => $request->input("meta_desc_$lang"),
						'metacont_category_blog_lang' => $request->input("meta_cont_$lang")
					];

					Web_Category_Blog_Lang::create($categoryBlogLang);
				}
			});
		} else {

			Web_Category_Blog::where('id_category_blog', $categoryBlog['id_category_blog'])
				->update([
					'title_category_blog' => $categoryBlog['title_category_blog'],
					'enable_category_blog' => $categoryBlog['enable_category_blog']
				]);

			foreach (array_keys($this->lang) as $lang) {

				$lang = strtoupper($lang);
				$categoryBlogLang = [
					'name_category_blog_lang' => $request->input("name_$lang"),
					'title_category_blog_lang' => $request->input("title_$lang"),
					'url_category_blog_lang' => $request->input("url_$lang"),
					'metatit_category_blog_lang' => $request->input("meta_title_$lang"),
					'metades_category_blog_lang' => $request->input("meta_desc_$lang"),
					'metacont_category_blog_lang' => $request->input("meta_cont_$lang")
				];

				Web_Category_Blog_Lang::query()
					->where('id_category_blog_lang', $categoryBlog['id_category_blog'])
					->where('lang_category_blog_lang', $lang)
					->update($categoryBlogLang);
			}
		}

		return $categoryBlog['id_category_blog'];
	}

	public function EditBlog(Request $request)
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
