<?php

namespace App\Http\Controllers\admin\contenido;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorysBlog;
use App\Models\V5\Web_Category_Blog;
use App\Models\V5\Web_Category_Blog_Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class AdminBlogCategoryController extends Controller
{
	//añadir las variables del contructor
	public $blog;
	public $categorysBlog;
	public $lang;

	public function __construct()
	{
		$this->categorysBlog = new CategorysBlog();
		$this->lang = Config::get('app.locales');

		$this->middleware('trimStrings');
	}

	public function store(Request $request)
	{
		$allCategories = Web_Category_Blog::all();

		$categoryData = [
			'id_category_blog' => $allCategories->max('id_category_blog') + 1,
			'orden_category_blog' => $allCategories->max('orden_category_blog') + 1,
			'title_category_blog' => $request->name_ES,
			'enable_category_blog' => 1,
		];

		$webCategoryBlog = Web_Category_Blog::create($categoryData);

		foreach (array_keys($this->lang) as $lang) {
			$lang = strtoupper($lang);

			$slugUrl = !empty($request->{"url_$lang"})
				? $request->{"url_$lang"}
				: Str::slug($request->{"title_$lang"});

			$categoryLangData = [
				'id_category_blog_lang' => $webCategoryBlog->id_category_blog,
				'lang_category_blog_lang' => $lang,
				'title_category_blog_lang' => $request->{"title_$lang"},
				'name_category_blog_lang' => $request->{"name_$lang"},
				'url_category_blog_lang' => $slugUrl,
				'metatit_category_blog_lang' => $request->{"meta_title_$lang"},
				'metades_category_blog_lang' => $request->{"meta_desc_$lang"},
				'metacont_category_blog_lang' => $request->{"meta_cont_$lang"}
			];

			Web_Category_Blog_Lang::create($categoryLangData);
		}

		return response()->json([
			'success' => true,
			'status' => 'success',
			'message' => 'Categoría creada correctamente',
			'data' => [
				'action' => 'create',
				'category' => $webCategoryBlog
			]
		]);
	}

	public function edit($id)
	{
		$category = Web_Category_Blog::where('id_category_blog', $id)
			->with('languages')
			->first();

		$html = view('admin::pages.contenido.blog._form_category', compact('category'))->render();

		return response()->json([
			'success' => true,
			'status' => 'success',
			'message' => 'Categoría obtenida correctamente',
			'data' => [
				'html' => $html
			]
		]);
	}

	public function update(Request $request, $id)
	{
		$webCategoryBlog = Web_Category_Blog::where('id_category_blog', $id)
			->with('languages')
			->first();

		$webCategoryBlog->title_category_blog = $request->name_ES;
		if ($webCategoryBlog->isDirty('title_category_blog')) {
			$webCategoryBlog->save();
		}

		foreach (array_keys($this->lang) as $lang) {
			$lang = strtoupper($lang);

			$webCategoryBlogLang = $webCategoryBlog->languages->where('lang_category_blog_lang', $lang)->first();
			$slugUrl = !empty($request->{"url_$lang"})
				? $request->{"url_$lang"}
				: Str::slug($request->{"title_$lang"});

			$categoryLangData = [
				'title_category_blog_lang' => $request->{"title_$lang"},
				'name_category_blog_lang' => $request->{"name_$lang"},
				'url_category_blog_lang' => $slugUrl,
				'metatit_category_blog_lang' => $request->{"meta_title_$lang"},
				'metades_category_blog_lang' => $request->{"meta_desc_$lang"},
				'metacont_category_blog_lang' => $request->{"meta_cont_$lang"}
			];

			if (!$webCategoryBlogLang) {
				$categoryLangData['id_category_blog_lang'] = $webCategoryBlog->id_category_blog;
				$categoryLangData['lang_category_blog_lang'] = $lang;
				Web_Category_Blog_Lang::create($categoryLangData);
			} else {
				Web_Category_Blog_Lang::where([
					'id_category_blog_lang' => $webCategoryBlog->id_category_blog,
					'lang_category_blog_lang' => $lang
				])->update($categoryLangData);
			}
		}

		return response()->json([
			'success' => true,
			'status' => 'success',
			'message' => 'Categoría actualizada correctamente',
			'data' => [
				'category' => $webCategoryBlog
			]
		]);
	}

	public function updateOrder(Request $request)
	{
		$categories = $request->categories;
		foreach ($categories as $category) {
			Web_Category_Blog::where('id_category_blog', $category['id_category_blog'])
				->update([
					'orden_category_blog' => $category['order']
				]);
		}

		return response()->json([
			'success' => true,
			'status' => 'success',
			'message' => 'Orden actualizado correctamente'
		]);
	}

	public function changeIsEnabled(Request $request, $id)
	{
		Web_Category_Blog::where('id_category_blog', $id)
			->update([
				'enable_category_blog' => $request->isEnabled == 'true' ? 1 : 0
			]);

		return response()->json([
			'success' => true,
			'status' => 'success',
			'message' => 'Habilitado actualizado correctamente'
		]);
	}

	public function destroy($id)
	{
		$webCategoryBlog = Web_Category_Blog::where('id_category_blog', $id)->first();

		Web_Category_Blog_Lang::where('id_category_blog_lang', $id)->delete();
		$webCategoryBlog->delete();

		return response()->json([
			'success' => true,
			'status' => 'success',
			'message' => 'Categoría eliminada correctamente'
		]);
	}
}
