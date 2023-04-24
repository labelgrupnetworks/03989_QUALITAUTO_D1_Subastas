<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class AppUsersToken extends Model
{
    protected $table = 'APP_USERS_TOKEN';
    protected $primaryKey = 'GEMP_USERS_TOKEN, CLI_USERS_TOKEN, SO_USERS_TOKEN  ';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

      #definimos la variable emp para no tener que indicarla cada vez
      public function __construct(array $vars = []){
        $this->attributes=[
            'GEMP_USERS_TOKEN' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('gemp', function(Builder $builder) {
            $builder->where('GEMP_USERS_TOKEN', \Config::get("app.gemp"));
        });
    }

    



}
