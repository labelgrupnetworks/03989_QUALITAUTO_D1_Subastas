<?php

# Ubicacion del modelo
namespace App\Models\webservice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class WebServiceUser extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'WEB_WEBSERVICE_USER';
    protected $primaryKey = 'LOGIN_API_USER';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
            'GEMP_WEBSERVICE_USER' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('gemp', function(Builder $builder) {
            $builder->where('GEMP_WEBSERVICE_USER', \Config::get("app.gemp"));
        });
    }


    static function loginWebServiceUser($login, $pass)
    {
          $webServicePermissions = self::select("FUNCTION_WEBSERVICE_PERMISSION")
		->leftjoin("WEB_WEBSERVICE_PERMISSION", "IDUSER_WEBSERVICE_PERMISSION = ID_WEBSERVICE_USER ")
		->where("LOGIN_WEBSERVICE_USER", $login)
		->where("PASSWORD_WEBSERVICE_USER", hash("sha256",$pass)  )
		->get();

		$permissions = [];
		foreach ($webServicePermissions as $webServicePermission) {
			$permissions[] = $webServicePermission->function_webservice_permission;
		}



		return $permissions ;

    }
}
