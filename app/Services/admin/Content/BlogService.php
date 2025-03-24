<?php

namespace App\Services\admin\Content;

use App\DataTransferObjects\Content\BlogData;
use App\libs\CacheLib;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Blog_Lang;
use App\Models\V5\Web_Blog_Rel_Category;
use App\Models\V5\Web_Category_Blog;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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

	/**
	 * @todo Refactorizar por eloquent
	 */
	public function getCategSubCateg($cache_sql = false, $all_categ_sub)
	{

		$sql = "SELECT
        COD_SEC,ORTSEC1.lin_ortsec1 as lin_ortsec1 ,NVL(SEC_LANG.DES_SEC_LANG,  SEC.DES_SEC) DES_SEC  ,NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC) KEY_SEC ,NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0 , NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0
        FROM FXSEC SEC
        LEFT JOIN FXSEC_LANG SEC_LANG ON (SEC_LANG.CODSEC_SEC_LANG = SEC.COD_SEC AND  SEC_LANG.GEMP_SEC_LANG = SEC.GEMP_SEC  AND SEC_LANG.LANG_SEC_LANG = :lang)
        JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = :emp )
        JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = ORTSEC1.EMP_ORTSEC1  and ORTSEC0.LIN_ORTSEC0 =ORTSEC1.LIN_ORTSEC1)
        LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON (ORTSEC0_LANG.sub_ORTSEC0_LANG = ORTSEC1.sub_ORTSEC1 AND ORTSEC0_LANG.EMP_ORTSEC0_LANG = ORTSEC1.EMP_ORTSEC1  and ORTSEC0_LANG.LIN_ORTSEC0_LANG =ORTSEC1.LIN_ORTSEC1  AND ORTSEC0_LANG.LANG_ORTSEC0_LANG = :lang)
        WHERE
        ORTSEC1.LIN_ORTSEC1 != '10'
        AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = :gemp AND ORTSEC1.SUB_ORTSEC1 = :cod_sub
        GROUP BY COD_SEC,ORTSEC1.ORDEN_ORTSEC1,ORTSEC1.lin_ortsec1, NVL(SEC_LANG.DES_SEC_LANG,  SEC.DES_SEC), NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC),NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) , NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0)
        ORDER BY ORTSEC1.ORDEN_ORTSEC1 ASC";


		$params =  array(
			'cod_sub'   => $all_categ_sub,
			'emp'       => Config::get('app.emp'),
			'gemp'       => Config::get('app.gemp'),
			'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
		);

		if ($cache_sql) {
			//quitamos espacios en blanco
			$name_cache = "CategSubCateg_" . $all_categ_sub . '_' . ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

			$res = CacheLib::useCache($name_cache, $sql, $params);
		} else {
			$res = DB::select($sql, $params);
		}

		return $res;
	}
}
