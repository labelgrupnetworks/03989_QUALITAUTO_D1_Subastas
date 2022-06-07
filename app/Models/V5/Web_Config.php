<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;


class Web_Config extends Model
{
    protected $table = 'Web_Config';
    protected $primaryKey = 'ID_WEB_CONFIG';
    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];
   
    



	//
    //   password_encrypt - Función encargada de codificar/encriptar un password 
    //
    //   @password - Password del usuario
    //   @emp - Empresa 
    //   
    //   Devuelve el user encontrado. Se pasa la empresa porque esta función se utiliza para validar en casas de subastas
    //


    static function password_encrypt($password, $emp) {


		$res = 	WEB_CONFIG::select("VALUE")
				->where("KEY","password_MD5")
				->where("EMP",$emp)->first();
		if (empty($res)) {
			return false;
		}
		$v = trim(md5($res->value.$password));

		return $v;

    }









}