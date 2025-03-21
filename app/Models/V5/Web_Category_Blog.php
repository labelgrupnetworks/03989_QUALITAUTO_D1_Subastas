<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
class Web_Category_Blog extends Model
{
    protected $table = 'web_category_blog';
    protected $primaryKey = 'id_category_blog';

    public $timestamps = false;
    public $incrementing = false;
 	//public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = []){
		$this->attributes=[
			'emp_category_blog' => Config::get("app.main_emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_category_blog', Config::get("app.main_emp"));
		});
	}

	public function scopeJoinLang($query)
	{
		return $query->join('web_category_blog_lang', 'id_category_blog_lang = id_category_blog');
	}

	public function scopeWhereLang($query, $lang)
	{
		$lang = Str::upper($lang);
		return $query->where('lang_category_blog_lang', $lang);
	}

	public function languages()
	{
		return $this->hasMany('App\Models\V5\Web_Category_Blog_Lang', 'id_category_blog_lang', 'id_category_blog');
	}

}
