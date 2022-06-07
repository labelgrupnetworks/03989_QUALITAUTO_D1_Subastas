<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\RoutingServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Web_Page extends Model
{
    protected $table = 'Web_Page';
    protected $primaryKey = 'ID_WEB_PAGE';
    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	protected $appends = [
		'url_page',
	];

     #definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = []){
        $this->attributes=[
            'emp_web_page' => \Config::get("app.main_emp")
        ];
        parent::__construct($vars);
	}

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_web_page', \Config::get("app.main_emp"));
        });
    }

	public function getUrlPageAttribute()
	{
		if(empty($this->key_web_page) || empty($this->lang_web_page)){
			return '';
		}

		$provisionalUrl = config('app.url') . RoutingServiceProvider::translateSeo('pagina') . $this->key_web_page;

		return str_replace('/es/', "/" . mb_strtolower($this->lang_web_page) . "/", $provisionalUrl);
	}
}
