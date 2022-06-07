<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Web_Images_Size extends Model
{
    protected $table = 'WEB_IMAGES_SIZE';
    protected $primaryKey = 'ID_WEB_IMAGES_SIZE';
    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'id_emp' => \Config::get("app.main_emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('id_emp', \Config::get("app.main_emp"));
        });
    }

    static function getSizes(){
        $sizes = array();
        foreach (self::get() as $size){
            $sizes[$size->name_web_images_size] = $size->size_web_images_size;
        }

        return $sizes;
    }


}
