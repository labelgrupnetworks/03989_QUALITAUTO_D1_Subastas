<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class Web_Blog_Lang extends Model
{
	protected $table = 'WEB_BLOG_LANG';
	protected $primaryKey = 'ID_WEB_BLOG_LANG';

	public $timestamps = false;
	public $incrementing = true;
	//   public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	public function otherLangs()
	{
		return $this->hasMany(Web_Blog_Lang::class, 'idblog_web_blog_lang', 'idblog_web_blog_lang')->where('lang_web_blog_lang', '!=', $this->lang_web_blog_lang);
	}


}
