<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Web_Blog_Rel_Category extends Model
{
	protected $table = 'web_blog_rel_category';

	public $timestamps = false;
	public $incrementing = false;
	protected $guarded = [];

	public static function getRelationsByIdQuery($id)
	{
		return self::query()
			->joinWebBlog()
			->joinCategoryBlog()
			->where([
				'emp_web_blog' => Config::get('app.main_emp'),
				'id_web_blog' => $id
			]);
	}

	public function scopeJoinWebBlog($query)
	{
		return $query->join('web_blog', 'idblog_web_blog_rel_category', '=', 'id_web_blog');
	}

	public function scopeJoinCategoryBlog($query)
	{
		return $query->join('web_category_blog', 'idcat_web_blog_rel_category', '=', 'id_category_blog');
	}

}
