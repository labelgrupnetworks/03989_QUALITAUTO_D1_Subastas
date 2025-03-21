<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

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
}
