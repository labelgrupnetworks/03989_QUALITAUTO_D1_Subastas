<?php

# Ubicacion del modelo
namespace App\Models;

use App\Models\V5\Web_Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Blog extends Model
{
	public $lang;

	public function __construct()
	{

		$this->lang;

		//Blog
		$this->id;
		$this->title;
		$this->enabled;
		$this->file_url;
		$this->date;
		$this->img;
		$this->categories_web;
		$this->sub_categories_web;
		$this->category_principal;
		$this->rel_category;
		$this->author_web_blog;

		//Blog Lang
		$this->id_lang;
		$this->idblog;
		$this->title_blog;
		$this->cita_blog;
		$this->url_blog;
		$this->metatit_blog;
		$this->metades_blog;
		$this->cont_blog;
		$this->video_blog;
		$this->enabled_blog;
	}

	public function getCategorysLang()
	{
		$where = '';
		$bindings = array(
			'emp'      => Config::get('app.main_emp')
		);

		if (!empty($this->id)) {
			$where .= ' and category_blog.id_category_blog = :id';
			$bindings['id'] = $this->id;
		}
		if (!empty($this->lang)) {
			$where .= ' and category_blog_lang.lang_category_blog_lang = :lang';
			$bindings['lang'] = $this->lang;
		}

		$sql = "SELECT * FROM WEB_CATEGORY_BLOG category_blog
               INNER JOIN WEB_CATEGORY_BLOG_LANG category_blog_lang ON category_blog.id_category_blog = category_blog_lang.id_category_blog_lang
               where category_blog.emp_category_blog = :emp $where";

		return DB::select($sql, $bindings);
	}

	public function getNoticiaLang()
	{
		$bindings = array(
			'emp'      => Config::get('app.main_emp'),
			'id' => $this->idblog,
			'lang' => $this->lang
		);

		$sql = "SELECT * FROM WEB_BLOG blog
               INNER JOIN WEB_BLOG_LANG blog_lang ON blog.id_web_blog = blog_lang.idblog_web_blog_lang
               where blog.id_web_blog = :id
               and blog.emp_web_blog = :emp
               and blog_lang.lang_web_blog_lang = :lang";

		return DB::select($sql, $bindings);
	}

	public function getAllPrincipalBlog()
	{
		return Web_Blog::getAllNoticiesWithRelations();
	}

	public function getNoticiasAllLangs()
	{
		$noticias = Web_Blog::joinWebBlogLang()->where('id_web_blog', $this->idblog)->get();
		return $noticias;
	}

	public function getNoticiaRelCategory()
	{
		$bindings = array(
			'id' => $this->idblog,
			'emp'      => Config::get('app.main_emp'),
		);

		$sql = "SELECT * FROM WEB_BLOG_REL_CATEGORY BLOG_REL_CATEG"
			. " INNER JOIN WEB_BLOG BLOG ON BLOG_REL_CATEG.IDBLOG_WEB_BLOG_REL_CATEGORY = BLOG.ID_WEB_BLOG"
			. " INNER JOIN WEB_CATEGORY_BLOG CATEG_BLOG ON CATEG_BLOG.ID_CATEGORY_BLOG = BLOG_REL_CATEG.IDCAT_WEB_BLOG_REL_CATEGORY"
			. " WHERE BLOG.EMP_WEB_BLOG = :emp and BLOG.ID_WEB_BLOG = :id";
		return DB::select($sql, $bindings);
	}

	public function InsertBlog()
	{
		DB::table('WEB_BLOG')->insert(
			[
				'id_web_blog' => $this->idblog_lang,
				'title_web_blog' => $this->title,
				'img_web_blog' => $this->img,
				'lot_categories_web_blog' => $this->categories_web,
				'primary_category_web_blog' => $this->category_principal,
				'lot_sub_categories_web_blog' => $this->sub_categories_web,
				'publication_date_web_blog' => $this->date,
				'emp_web_blog' => Config::get('app.main_emp')
			]
		);
	}

	public function MaxBlog()
	{
		return DB::table('WEB_BLOG')->max('id_web_blog');
	}

	public function MaxBlogLang()
	{
		return DB::table('WEB_BLOG_LANG')->max('id_web_blog_lang');
	}

	public function InsertBlogLang()
	{
		DB::table('WEB_BLOG_LANG')->insert(
			[
				'id_web_blog_lang' => $this->id_lang,
				'idblog_web_blog_lang' => $this->idblog_lang,
				'ENABLED_WEB_BLOG_LANG' => $this->enabled_blog,
				'lang_web_blog_lang' => $this->lang,
				'titulo_web_blog_lang' => $this->title_blog,
				'cita_web_blog_lang' => $this->cita_blog,
				'texto_web_blog_lang' => $this->cont_blog,
				'metatitle_web_blog_lang' => $this->metatit_blog,
				'metadescription_web_blog_lang' => $this->metades_blog,
				'url_web_blog_lang' => $this->url_blog,
				'video_web_blog_lang' => $this->video_blog
			]
		);
	}

	public function UpdateBlogLang()
	{
		DB::table('WEB_BLOG_LANG')
			->where('idblog_web_blog_lang', $this->idblog_lang)
			->where('lang_web_blog_lang', $this->lang)
			->update([
				'titulo_web_blog_lang' => $this->title_blog,
				'cita_web_blog_lang' => $this->cita_blog,
				'texto_web_blog_lang' => $this->cont_blog,
				'enabled_web_blog_lang' => $this->enabled_blog,
				'metatitle_web_blog_lang' => $this->metatit_blog,
				'metadescription_web_blog_lang' => $this->metades_blog,
				'url_web_blog_lang' => $this->url_blog,
				'video_web_blog_lang' => $this->video_blog
			]);
	}

	public function UpdateBlog()
	{

		DB::table('WEB_BLOG')
			->where('emp_web_blog', Config::get('app.main_emp'))
			->where('id_web_blog', $this->idblog_lang)
			->update([
				'title_web_blog' => $this->title,
				'img_web_blog' => $this->img,
				'lot_categories_web_blog' => $this->categories_web,
				'primary_category_web_blog' => $this->category_principal,
				'lot_sub_categories_web_blog' => $this->sub_categories_web,
				'publication_date_web_blog' => $this->date,
				'author_web_blog' => $this->author_web_blog
			]);
	}

	public function getAllBlogs()
	{
		return DB::table('WEB_BLOG')->where('emp_web_blog', Config::get('app.main_emp'))->orderby("ID_WEB_BLOG", "DESC")->get();
	}

	public function InsertRelBlog()
	{
		DB::table('WEB_BLOG_REL_CATEGORY')->insert(
			[
				'IDCAT_WEB_BLOG_REL_CATEGORY' => $this->rel_category,
				'IDBLOG_WEB_BLOG_REL_CATEGORY' => $this->idblog_lang
			]
		);
	}

	public function DeleteRelBlog()
	{
		DB::table('WEB_BLOG_REL_CATEGORY')
			->where('IDBLOG_WEB_BLOG_REL_CATEGORY', $this->idblog_lang)->delete();
	}

	public function getSmallNoticiasLang()
	{
		$lang = $this->lang;
		$blog = Web_Blog::query()
			->select("WEB_BLOG.IMG_WEB_BLOG, WEB_BLOG.PRIMARY_CATEGORY_WEB_BLOG")
			->addSelect("WEB_BLOG_LANG.TITULO_WEB_BLOG_LANG, substr(WEB_BLOG_LANG.TEXTO_WEB_BLOG_LANG, 0, 600) as TEXTO_WEB_BLOG_LANG, WEB_BLOG_LANG.URL_WEB_BLOG_LANG")
			->addSelect("WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG")

			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')
			->join('WEB_BLOG_REL_CATEGORY', 'WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', '=', 'WEB_BLOG.ID_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG.PRIMARY_CATEGORY_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')

			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $lang)
			->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1)
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $lang)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->where('ENABLED_WEB_BLOG_LANG', 1)

			->whereNotNull('URL_WEB_BLOG_LANG')
			->whereNotNull('primary_category_web_blog')
			->orderBy('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', 'desc')
			->paginate(Config::get('app.paginate_blog', 16));

		return $blog;
	}

	public function getAllNoticiasLang($key_categ = null)
	{
		$lang = $this->lang;

		$blog = DB::table('WEB_BLOG')
			->select('ID_WEB_BLOG,IMG_WEB_BLOG,LOT_CATEGORIES_WEB_BLOG,LOT_SUB_CATEGORIES_WEB_BLOG,PUBLICATION_DATE_WEB_BLOG,primary_category_web_blog,author_web_blog')
			->addSelect('ID_WEB_BLOG_LANG, IDBLOG_WEB_BLOG_LANG, LANG_WEB_BLOG_LANG, TITULO_WEB_BLOG_LANG, CITA_WEB_BLOG_LANG, METATITLE_WEB_BLOG_LANG, METADESCRIPTION_WEB_BLOG_LANG, URL_WEB_BLOG_LANG, VIDEO_WEB_BLOG_LANG, ENABLED_WEB_BLOG_LANG')
			->addSelect('WEB_CATEGORY_BLOG_LANG.*')
			->selectRaw('substr(TEXTO_WEB_BLOG_LANG, 0, 1200) as TEXTO_WEB_BLOG_LANG')
			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')
			->join('WEB_BLOG_REL_CATEGORY', 'WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', '=', 'WEB_BLOG.ID_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->when($key_categ, function ($q) use ($key_categ) {
				return $q->where('WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG', $key_categ);
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
			->where('WEB_BLOG.emp_web_blog', Config::get('app.main_emp'))
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $lang)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->where('ENABLED_WEB_BLOG_LANG', 1)
			->whereNotNull('URL_WEB_BLOG_LANG')
			->whereNotNull('primary_category_web_blog')
			->orderBy('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', 'desc');

		if (!empty(Config::get('app.paginate_blog'))) {
			return $blog->paginate(Config::get('app.paginate_blog'));
		} else {
			return $blog->paginate('16');
		}
	}

	public function getAllNoticiasLangByIdCategory($id_category_blog)
	{
		$lang = $this->lang;

		$blog = DB::table('WEB_BLOG')
			->select('ID_WEB_BLOG,IMG_WEB_BLOG,LOT_CATEGORIES_WEB_BLOG,LOT_SUB_CATEGORIES_WEB_BLOG,PUBLICATION_DATE_WEB_BLOG,primary_category_web_blog,author_web_blog,'
				. 'WEB_BLOG_LANG.*')
			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')

			->when(
				$id_category_blog,
				function ($q) use ($id_category_blog, $lang) {
					return $q->addSelect('WEB_CATEGORY_BLOG_LANG.*')
						->join('WEB_BLOG_REL_CATEGORY', 'WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', '=', 'WEB_BLOG.ID_WEB_BLOG')
						->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY')
						->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
						->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
						->where('WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', $id_category_blog)
						->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $lang)
						->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1);
				}
			)
			->where('WEB_BLOG.emp_web_blog', Config::get('app.main_emp'))
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $lang)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->where('ENABLED_WEB_BLOG_LANG', 1)
			->whereNotNull('URL_WEB_BLOG_LANG')
			->whereNotNull('primary_category_web_blog')
			->orderBy('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', 'desc');
		if (!empty(Config::get('app.paginate_blog'))) {
			return $blog->paginate(Config::get('app.paginate_blog'));
		} else {
			return $blog->paginate('16');
		}
	}

	public function getAllNoticiasRelacionadas($relation_new)
	{

		$categ_rel = array();
		$result = array();
		$result = DB::table('WEB_BLOG_REL_CATEGORY')
			->select('idcat_web_blog_rel_category')
			->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY')
			->where('WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', $relation_new)
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1)
			->get();



		foreach ($result as $value_resul) {
			$categ_rel[] = $value_resul->idcat_web_blog_rel_category;
		}

		$lang = $this->lang;
		return DB::table('WEB_BLOG')
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


	public function getNoticia($key_categ, $key_news)
	{
		return DB::table('WEB_BLOG')
			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')
			->join('WEB_CATEGORY_BLOG', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG.PRIMARY_CATEGORY_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG', $key_categ)
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $this->lang)
			->where('WEB_BLOG.emp_web_blog', Config::get('app.main_emp'))
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $this->lang)
			->where('WEB_BLOG_LANG.URL_WEB_BLOG_LANG', $key_news)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->first();
	}

	public function getHomeNotices($limit, $withContent)
	{
		return Web_Blog::getNoticiesQuery($withContent)
			->select('id_web_blog', 'img_web_blog', 'primary_category_web_blog')
			->limit($limit)
			->get();
	}
}

class CategorysBlog extends Model
{
	public $lang;

	public function __construct()
	{
		$this->lang;
		$this->id;
		$this->title;
		$this->orden;
		$this->enabled;
		$this->title_category;
		$this->name_category;
		$this->url_category;
		$this->metatit_category;
		$this->metades_category;
		$this->metacont_category;
	}

	public function getCategorys()
	{
		$bindings = array('emp'      => Config::get('app.main_emp'));

		$sql = "SELECT * FROM WEB_CATEGORY_BLOG category_blog
               where category_blog.emp_category_blog = :emp";

		return DB::select($sql, $bindings);
	}


	public function InsertCategoryBlog()
	{
		DB::table('WEB_CATEGORY_BLOG')->insert(
			['id_category_blog' => $this->id, 'title_category_blog' => $this->title, 'enable_category_blog' => $this->enabled, 'emp_category_blog' => Config::get('app.main_emp'), 'orden_category_blog' => $this->orden]
		);
	}

	public function Max_Category_Blog()
	{
		return DB::table('WEB_CATEGORY_BLOG')->max('id_category_blog');
	}

	public function UpdateCategoryBlog()
	{
		DB::table('WEB_CATEGORY_BLOG')
			->where('id_category_blog', $this->id)
			->where('emp_category_blog', Config::get('app.main_emp'))
			->update(['title_category_blog' => $this->title, 'enable_category_blog' => $this->enabled]);
	}

	public function UpdateCategoryBlogLang()
	{
		DB::table('WEB_CATEGORY_BLOG_LANG')
			->where('id_category_blog_lang', $this->id)
			->where('lang_category_blog_lang', $this->lang)
			->update([
				'title_category_blog_lang' => $this->title_category,
				'name_category_blog_lang' => $this->name_category,
				'url_category_blog_lang' => $this->url_category,
				'metatit_category_blog_lang' => $this->metatit_category,
				'metades_category_blog_lang' => $this->metades_category,
				'metacont_category_blog_lang' => $this->metacont_category
			]);
	}

	public function InsertCategoryBlogLang()
	{
		DB::table('WEB_CATEGORY_BLOG_LANG')->insert([
			'id_category_blog_lang' => $this->id,
			'lang_category_blog_lang' => $this->lang,
			'title_category_blog_lang' => $this->title_category,
			'name_category_blog_lang' => $this->name_category,
			'url_category_blog_lang' => $this->url_category,
			'metatit_category_blog_lang' => $this->metatit_category,
			'metades_category_blog_lang' => $this->metades_category,
			'metacont_category_blog_lang' => $this->metacont_category
		]);
	}

	public function getCategory($all = false, $checkIsEnabled = true)
	{

		$result = DB::table('WEB_CATEGORY_BLOG')
			->select('id_category_blog', 'metatit_category_blog_lang', 'metades_category_blog_lang', 'metacont_category_blog_lang', 'title_category_blog_lang', 'name_category_blog_lang ', 'id_category_blog_lang', 'url_category_blog_lang')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $this->lang)
			->when($checkIsEnabled, function ($query) {
				return $query->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1);
			});

		if ($all) {
			return $result->get();
		} else {
			return $result->where('WEB_CATEGORY_BLOG_LANG.URL_CATEGORY_BLOG_LANG', $this->url_category)->first();
		}
	}

	public function getCategoryById($id_category_blog)
	{

		$result = DB::table('WEB_CATEGORY_BLOG')
			->select('id_category_blog', 'metatit_category_blog_lang', 'metades_category_blog_lang', 'metacont_category_blog_lang', 'title_category_blog_lang', 'name_category_blog_lang ', 'id_category_blog_lang', 'url_category_blog_lang')
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $this->lang)
			->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1)
			->where('WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', $id_category_blog)->first();

		return $result;
	}

	public function getCategoryHasNews()
	{
		$result = array();

		$value =  DB::table('WEB_BLOG')
			->select('id_category_blog')
			->join('WEB_BLOG_LANG', 'WEB_BLOG_LANG.idblog_web_blog_lang', '=', 'WEB_BLOG.id_web_blog')
			->join('WEB_BLOG_REL_CATEGORY', 'WEB_BLOG_REL_CATEGORY.IDBLOG_WEB_BLOG_REL_CATEGORY', '=', 'WEB_BLOG.ID_WEB_BLOG')
			->join('WEB_CATEGORY_BLOG', function ($join) {
				$join->orOn('WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG_REL_CATEGORY.IDCAT_WEB_BLOG_REL_CATEGORY')
					->orOn('WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG', '=', 'WEB_BLOG.primary_category_web_blog');
			})
			->join('WEB_CATEGORY_BLOG_LANG', 'WEB_CATEGORY_BLOG_LANG.ID_CATEGORY_BLOG_LANG', '=', 'WEB_CATEGORY_BLOG.ID_CATEGORY_BLOG')
			->where('WEB_BLOG.emp_web_blog', Config::get('app.main_emp'))
			->where('WEB_BLOG_LANG.lang_web_blog_lang', $this->lang)
			->where('WEB_BLOG.PUBLICATION_DATE_WEB_BLOG', '<=', date("Y-m-d"))
			->where('ENABLED_WEB_BLOG_LANG', 1)
			->whereNotNull('URL_WEB_BLOG_LANG')
			->whereNotNull('URL_CATEGORY_BLOG_LANG')
			->where('WEB_CATEGORY_BLOG.EMP_CATEGORY_BLOG', Config::get('app.main_emp'))
			->where('WEB_CATEGORY_BLOG_LANG.lang_category_blog_lang', $this->lang)
			->where('WEB_CATEGORY_BLOG.ENABLE_CATEGORY_BLOG', 1)
			->groupBy('id_category_blog')
			->get();

		foreach ($value as $categ) {
			$result[] = $categ->id_category_blog;
		}
		return $result;
	}

	/**
	 * Obtener todas las categorias que tengan posts.
	 * Necesito también saber cuantos post tiene cada categoría.
	 * De la categoria necesito el nombre, la url y la cantidad de post.
	 */
	public function getCategoriesHasNews()
	{
		$locale = mb_strtoupper(Config::get('app.locale'));
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
}
