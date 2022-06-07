<?php

# Ubicacion del modelo
namespace App\Models\apilabel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class WebApiUser extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'WEB_API_USER';
    protected $primaryKey = 'LOGIN_API_USER';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_api_user' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('gemp', function(Builder $builder) {
            $builder->where('gemp_api_user', \Config::get("app.gemp"));
        });
    }


    static function loginApiUser($login, $pass)
    {
        $apiUser = self::where("LOGIN_API_USER", $login)->where("PASSWORD_API_USER", $pass)->first();
        if (empty($apiUser)) {
            return false;
        } else {
            return true;
        }
    }
}
