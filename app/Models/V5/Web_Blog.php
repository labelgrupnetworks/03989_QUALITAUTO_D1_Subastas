<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\RoutingServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Web_Blog extends Model
{
	protected $table = 'web_blog';
	protected $primaryKey = 'id_web_blog';

	public $timestamps = false;
	public $incrementing = false;
	//public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_web_blog' => Config::get("app.main_emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_web_blog', Config::get("app.main_emp"));
		});
	}

	public function principalCategory()
	{
		return $this->hasOne(Web_Category_Blog::class, 'id_category_blog', 'primary_category_web_blog');
	}

	public function localeLang()
	{
		return $this->hasOne(Web_Blog_Lang::class, 'idblog_web_blog_lang', 'id_web_blog')->where('lang_web_blog_lang', mb_strtoupper(Config::get('app.locale')));
	}

	public function languages()
	{
		return $this->hasMany(Web_Blog_Lang::class, 'idblog_web_blog_lang', 'id_web_blog');
	}

	public function scopeJoinWebBlogLang($query)
	{
		return $query->join('web_blog_lang', 'idblog_web_blog_lang', '=', 'id_web_blog');
	}

	public function scopeWhereLang($query, $lang)
	{
		$lang = mb_strtoupper($lang);
		return $query->where('lang_web_blog_lang', $lang);
	}

	public static function getNoticiesQuery($withContent)
	{
		return self::query()
			->with(['principalCategory.languages' => function ($query) {
				$query->where('lang_category_blog_lang', mb_strtoupper(Config::get('app.locale')));
			}])
			->with(['localeLang' => function ($query) use ($withContent) {
				$query->select('idblog_web_blog_lang', 'titulo_web_blog_lang', 'url_web_blog_lang', 'enabled_web_blog_lang', 'cita_web_blog_lang')
					->when($withContent, function ($query) {
						return $query->addSelect('texto_web_blog_lang');
					});
			}])
			->whereHas('principalCategory', function (Builder $query) {
				$query->where('enable_category_blog', 1);
			})
			->whereHas('localeLang', function (Builder $query) {
				return $query->where('enabled_web_blog_lang', 1);
			})
			->orderBy('publication_date_web_blog', 'desc');
	}

	public static function getAllNoticiesWithRelations()
	{
		return self::query()
			->with('principalCategory')
			/* ->with(['localeLang' => function ($query) {
				$query->select('idblog_web_blog_lang', 'titulo_web_blog_lang', 'url_web_blog_lang', 'enabled_web_blog_lang');
			}]) */
			->with(['languages' => function ($query) {
				$query->select('idblog_web_blog_lang', 'titulo_web_blog_lang', 'url_web_blog_lang', 'enabled_web_blog_lang', 'lang_web_blog_lang');
			}])
			->orderBy('publication_date_web_blog', 'desc')
			->get();
	}

	public function getCategoryNameAttribute()
	{
		return $this->principalCategory->title_category_blog ?? null;
	}

	public function getIsEnableAttribute()
	{
		return $this->localeLang->enabled_web_blog_lang ?? null;
	}

	public function getPublishDateAttribute()
	{
		return $this->publication_date_web_blog ? date('d/m/Y', strtotime($this->publication_date_web_blog)) : null;
	}

	public function getIsVisibleMessageAttribute()
	{
		$messages = [
			'error' => "",
			'warnings' => []
		];
		if (!$this->languages) {
			return [
				$messages['error'] => '- La noticia no tiene idiomas asociados'
			];
		}

		if (!$this->publication_date_web_blog) {
			$messages['warnings'][] = "- Falta fecha de publicación";
		}

		if ($this->publication_date_web_blog > date('Y-m-d H:i:s')) {
			$messages['warnings'][] = "- La fecha de publicación es posterior a la actual";
		}

		if (array_sum($this->languages->pluck('enabled_web_blog_lang')->toArray()) != count($this->languages)) {
			$messages['warnings'][] = "- La noticia no está activa";
		}

		foreach ($this->languages as $webBlogLang) {
			if (!$webBlogLang->url_web_blog_lang) {
				$messages['warnings'][] = "- Falta url en el idioma $webBlogLang->lang_web_blog_lang";
			}
		}

		return (object)$messages;
	}

	public function getIsVisibleAttribute()
	{
		return $this->is_visible_message->error == "" && count($this->is_visible_message->warnings) == 0;
	}

	public function getUrlAttribute()
	{
		if(!$this->localeLang) {
			return null;
		};

		if(!$this->principalCategory) {
			return RoutingServiceProvider::translateSeo("blog/{$this->localeLang->url_web_blog_lang}");
		};

		$categoryLanguage = $this->principalCategory->languages->where('lang_category_blog_lang', mb_strtoupper(Config::get('app.locale')))->first();
		return RoutingServiceProvider::translateSeo("blog/{$categoryLanguage->url_category_blog_lang}/{$this->localeLang->url_web_blog_lang}");
	}

	public function setMedia($file)
	{
		$publicPath = config('filesystems.disks.blog.root');
		$storageDisk = Storage::disk('blog');

		$oldImg = $this->img_web_blog;
		if ($oldImg) {
			$oldImg = str_replace($storageDisk->url(''), '', $oldImg);
			$storageDisk->delete($oldImg);
		}

		if (!File::exists($publicPath)) {
			File::makeDirectory($publicPath, 0775, true, true);
		}

		$path = $file->store('/', 'blog');
		$urlPath = $storageDisk->url($path);

		$this->img_web_blog = $urlPath;
		$this->save();

		return $urlPath;
	}
}
