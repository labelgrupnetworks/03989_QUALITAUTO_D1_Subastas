<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class Web_Artist_Article extends Model
{
    protected $table = 'WEB_ARTIST_ARTICLE';
    protected $primaryKey = 'ID_ARTIST_ARTICLE, EMP_ARTIST_ARTICLE';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_ARTIST_ARTICLE' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('EMP_ARTIST_ARTICLE', \Config::get("app.emp"));
        });
    }

}
