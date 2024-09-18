<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class Web_Artist extends Model
{
    protected $table = 'WEB_ARTIST';
    protected $primaryKey = 'ID_ARTIST, EMP_ARTIST';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_ARTIST' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('EMP_ARTIST', \Config::get("app.emp"));
        });
    }

    public function scopeJoinArticles($query){
        return $query->join("WEB_ARTIST_ARTICLE", "EMP_ARTIST_ARTICLE = EMP_ARTIST  AND IDARTIST_ARTIST_ARTICLE = ID_ARTIST");
    }

	public function scopeLeftJoinLang($query){
		$lang = ToolsServiceProvider::getLanguageComplete(\Config::get('app.locale'));
        return $query->addselect("nvl(INFO_ARTIST_LANG,INFO_ARTIST) AS INFO_ARTIST")->
		addselect("nvl(BIOGRAPHY_ARTIST_LANG, BIOGRAPHY_ARTIST) AS BIOGRAPHY_ARTIST")->
		addselect("nvl(EXTRA_ARTIST_LANG, EXTRA_ARTIST) AS EXTRA_ARTIST")->
			leftjoin("WEB_ARTIST_LANG", "EMP_ARTIST_LANG = EMP_ARTIST  AND ID_ARTIST_LANG = ID_ARTIST AND LANG_ARTIST_LANG = '" . $lang."'" );
    }

}
