<?php

namespace App\Services\admin\Content;

use App\DataTransferObjects\Content\BlogData;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Blog_Lang;
use App\Models\V5\Web_Blog_Rel_Category;
use App\Models\V5\Web_Category_Blog;
use Illuminate\Support\Facades\Config;

class BlogService
{
	//son varias porque es el mimso id en diferentes$ idiomas
	public function getCategoriesLangById($id)
	{
		return Web_Category_Blog::query()
			->joinLang()
			->where('id_category_blog', $id)
			->get();
	}

	public function getCategoriesLangByLocale()
	{
		return self::getCategoriesLangByLang(Config::get('app.locale'));
	}

	/**
	 * @param string $lang
	 * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\V5\Web_Category_Blog>|\App\Models\V5\Web_Category_Blog[]
	 */
	public function getCategoriesLangByLang($lang)
	{
		return Web_Category_Blog::query()
			->joinLang()
			->whereLang($lang)
			->get();
	}

	public function getNoticiaLang($idBlog, $lang)
	{
		return Web_Blog::query()
			->joinWebBlogLang()
			->where('id_web_blog', $idBlog)
			->whereLang($lang)
			->first();
	}

	public function getAllPrincipalBlog()
	{
		return Web_Blog::getAllNoticiesWithRelations();
	}

	/**
	 * @param string $idblog
	 * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\V5\Web_Category_Blog>|\App\Models\V5\Web_Category_Blog[]
	 */
	public function getNoticiasAllLangs($idblog)
	{
		return Web_Blog::query()
			->joinWebBlogLang()
			->where('id_web_blog', $idblog)
			->get();
	}

	public function getNoticiaRelCategory($idblog)
	{
		return Web_Blog_Rel_Category::getRelationsByIdQuery($idblog)->get();
	}

	public function getAllBlogs()
	{
		return Web_Blog::query()
			->orderBy('id_web_blog', 'desc')
			->get();
	}

	public function insertBlog(BlogData $blogData)
	{
		Web_Blog::create([
			'id_web_blog' => $blogData->id,
			'title_web_blog' => $blogData->title,
			'img_web_blog' => $blogData->img,
			'lot_categories_web_blog' => $blogData->categories_web,
			'primary_category_web_blog' => $blogData->category_principal,
			'lot_sub_categories_web_blog' => $blogData->sub_categories_web,
			'publication_date_web_blog' => $blogData->date,
			'author_web_blog' => $blogData->author_web_blog
		]);
	}

	/**
	 * @todo por refactorizar con dto
	 */
	public function insertBlogLang($request, $blogId, $idBlogLang, $lang)
	{
		Web_Blog_Lang::create([
			'id_web_blog_lang' => $idBlogLang,
			'idblog_web_blog_lang' => $blogId,
			'lang_web_blog_lang' => $lang,
			'titulo_web_blog_lang' => $request->input("title_{$lang}"),
			'cita_web_blog_lang' => $request->input("cita_{$lang}"),
			'url_web_blog_lang' => str_slug($request->input("url_{$lang}"), '-'),
			'metatitle_web_blog_lang' => $request->input("meta_title_{$lang}"),
			'metadescription_web_blog_lang' => $request->input("meta_desc_{$lang}"),
			'video_web_blog_lang' => $request->input("video_{$lang}"),
			'texto_web_blog_lang' => $request->input("cont_{$lang}"),
			'enabled_web_blog_lang' => $request->input("enabled_{$lang}") ? 1 : 0
		]);
	}

	public function updateBlog(BlogData $blogData)
	{
		Web_Blog::where('id_web_blog', $blogData->id)
			->update([
				'title_web_blog' => $blogData->title,
				'img_web_blog' => $blogData->img,
				'lot_categories_web_blog' => $blogData->categories_web,
				'primary_category_web_blog' => $blogData->category_principal,
				'lot_sub_categories_web_blog' => $blogData->sub_categories_web,
				'publication_date_web_blog' => $blogData->date,
				'author_web_blog' => $blogData->author_web_blog
			]);
	}

	public function updateBlogLang($request, $blogId, $lang)
	{
		Web_Blog_Lang::where('idblog_web_blog_lang', $blogId)
			->where('lang_web_blog_lang', $lang)
			->update([
				'titulo_web_blog_lang' => $request->input("title_{$lang}"),
				'cita_web_blog_lang' => $request->input("cita_{$lang}"),
				'url_web_blog_lang' => str_slug($request->input("url_{$lang}"), '-'),
				'metatitle_web_blog_lang' => $request->input("meta_title_{$lang}"),
				'metadescription_web_blog_lang' => $request->input("meta_desc_{$lang}"),
				'video_web_blog_lang' => $request->input("video_{$lang}"),
				'texto_web_blog_lang' => $request->input("cont_{$lang}"),
				'enabled_web_blog_lang' => $request->input("enabled_{$lang}") ? 1 : 0
			]);
	}

	public function deleteRelationBlog($blogId)
	{
		Web_Blog_Rel_Category::where('idblog_web_blog_rel_category', $blogId)->delete();
	}

	public function insertRelationBlog($blogId, $categoryId)
	{
		Web_Blog_Rel_Category::create([
			'idblog_web_blog_rel_category' => $blogId,
			'idcat_web_blog_rel_category' => $categoryId
		]);
	}
}
