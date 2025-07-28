<?php

namespace App\Services\Content;

use App\Models\V5\Web_Blog_Rel_Category;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Category_Blog;
use App\Support\Localization;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BlogService
{
	public function getAllNoticiasLang($slugCategory = null)
	{
		$lang = Localization::getUpperLocale();

		return Web_Blog::query()
			->select('ID_WEB_BLOG,IMG_WEB_BLOG,LOT_CATEGORIES_WEB_BLOG,LOT_SUB_CATEGORIES_WEB_BLOG,PUBLICATION_DATE_WEB_BLOG,primary_category_web_blog,author_web_blog')
			->addSelect('ID_WEB_BLOG_LANG, IDBLOG_WEB_BLOG_LANG, LANG_WEB_BLOG_LANG, TITULO_WEB_BLOG_LANG, CITA_WEB_BLOG_LANG, METATITLE_WEB_BLOG_LANG, METADESCRIPTION_WEB_BLOG_LANG, URL_WEB_BLOG_LANG, VIDEO_WEB_BLOG_LANG, ENABLED_WEB_BLOG_LANG')
			->addSelect('WEB_CATEGORY_BLOG_LANG.*')
			->selectRaw('substr(TEXTO_WEB_BLOG_LANG, 0, 1200) as TEXTO_WEB_BLOG_LANG')
			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')
			->join('WEB_BLOG_REL_CATEGORY', 'WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', '=', 'WEB_BLOG.ID_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->when($slugCategory, function ($q) use ($slugCategory) {
				return $q->where('WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG', $slugCategory);
			})
			->when(Config::get('app.conent_to_all_blog'), function ($query) {
				$query->addSelect(DB::raw("(select html_content
				from WEB_CONTENT_HTML
				join WEB_CONTENT_PAGE on WEB_CONTENT_PAGE.TYPE_ID_CONTENT_PAGE = WEB_CONTENT_HTML.ID_CONTENT and WEB_CONTENT_PAGE.TYPE_CONTENT_PAGE in ('TEXT', 'HTML')
				where WEB_CONTENT_PAGE.TABLE_REL_CONTENT_PAGE = 'WEB_BLOG_LANG' and WEB_CONTENT_PAGE.REL_ID_CONTENT_PAGE = ID_WEB_BLOG_LANG
				order by WEB_CONTENT_PAGE.ORDER_CONTENT_PAGE
				fetch first 1 row only
				) as CONTENT"));
			})
			->when(Config::get('app.most_distant_blog_date', null), function ($query, $date) {
				$query->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '>=', $date);
			})
			->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1)
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $lang)
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $lang)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->where('ENABLED_WEB_BLOG_LANG', 1)
			->whereNotNull('URL_WEB_BLOG_LANG')
			->whereNotNull('primary_category_web_blog')
			->orderBy('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', 'desc')
			->orderBy('WEB_BLOG.ID_WEB_BLOG', 'asc')
			->when(Config::get('app.paginate_blog'), function ($query) {
				return $query->paginate(Config::get('app.paginate_blog'));
			}, function ($query) {
				return $query->paginate('16');
			});
	}

	public function getNoticia($slugCategory, $slugBlog)
	{
		$lang = Localization::getUpperLocale();

		return Web_Blog::query()
			->joinWebBlogLang()
			->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG.PRIMARY_CATEGORY_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG', $slugCategory)
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $lang)
			->whereLang($lang)
			->where('WEB_BLOG_LANG.URL_WEB_BLOG_LANG', $slugBlog)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->first();
	}

	public function getAllNoticiasRelacionadas($relation_new)
	{
		$lang = Localization::getUpperLocale();

		$categ_rel = Web_Blog_Rel_Category::getRelationsByIdQuery($relation_new)
			->where('enable_category_blog', 1)
			->pluck('idcat_web_blog_rel_category')
			->all();


		return Web_Blog::query()
			->select('WEB_BLOG.id_web_blog', 'WEB_BLOG.primary_category_web_blog', 'url_web_blog_lang', 'titulo_web_blog_lang', 'img_web_blog', 'author_web_blog')
			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')
			->join('WEB_BLOG_REL_CATEGORY', 'WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', '=', 'WEB_BLOG.ID_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $lang)
			->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1)
			->where('WEB_BLOG.emp_web_blog', Config::get('app.main_emp'))
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $lang)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->where('ENABLED_WEB_BLOG_LANG', 1)
			->when(Config::get('app.most_distant_blog_date', null), function ($query, $date) {
				$query->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '>=', $date);
			})
			->whereNotNull('URL_WEB_BLOG_LANG')
			->whereIn('WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY', $categ_rel)
			->where('WEB_BLOG.id_web_blog', '!=', $relation_new)
			->orderBy('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', 'desc')
			->groupBy('WEB_BLOG.id_web_blog', 'WEB_BLOG.primary_category_web_blog', 'WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', 'url_web_blog_lang', 'titulo_web_blog_lang', 'img_web_blog', 'author_web_blog')
			->limit(Config::get('app.news_relacionadas'))
			->get();
	}

	/**
	 * Obtener todas las categorias que tengan posts.
	 * Necesito también saber cuantos post tiene cada categoría.
	 * De la categoria necesito el nombre, la url y la cantidad de post.
	 */
	public function getCategoriesHasNews()
	{
		$locale = Localization::getDefaultUpperLocale();

		return Web_Blog::query()
			->select('WEB_CATEGORY_BLOG_LANG.NAME_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->addSelect(DB::raw('count(WEB_BLOG.id_web_blog) as count'))
			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')
			->join('WEB_BLOG_REL_CATEGORY', 'WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', '=', 'WEB_BLOG.ID_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG', function ($join) {
				$join->orOn('WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY')
					->orOn('WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG.primary_category_web_blog');
			})
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->where('WEB_BLOG.emp_web_blog', Config::get('app.main_emp'))
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $locale)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->where('ENABLED_WEB_BLOG_LANG', 1)
			->whereNotNull('URL_WEB_BLOG_LANG')
			->whereNotNull('URL_CATEGORY_BLOG_LANG')
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $locale)
			->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1)
			->when(Config::get('app.most_distant_blog_date', null), function ($query, $date) {
				$query->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '>=', $date);
			})
			->groupBy('WEB_CATEGORY_BLOG_LANG.NAME_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->get();
	}

	public function getCategory($slugCategory = null, $checkIsEnabled = true)
	{
		$query = Web_Category_Blog::query()
			->select('id_category_blog', 'metatit_category_blog_lang', 'metades_category_blog_lang', 'metacont_category_blog_lang', 'title_category_blog_lang', 'name_category_blog_lang ', 'id_category_blog_lang', 'url_category_blog_lang')
			->joinLang()
			->whereLang(Config::get('app.locale'))
			->when($checkIsEnabled, function ($query) {
				return $query->where('enable_category_blog', 1);
			});

		if ($slugCategory) {
			return $query->where('url_category_blog_lang', $slugCategory)
				->first();
		}

		return $query->get();
	}

	public function getHomeNotices($limit, $withContent)
	{
		return Web_Blog::getNoticiesQuery($withContent)
			->select('id_web_blog', 'img_web_blog', 'primary_category_web_blog')
			->limit($limit)
			->get();
	}
}
