<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

use Illuminate\Database\Eloquent\Model;


class FxCliWeb_Log extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FXCLIWEB_LOG';
	protected $primaryKey = 'USRW_CLIWEB';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function scopelog($query){
			 #saco todos los campos a mano para poder ocultar password encriptado y token ya que lo ha pedido servihabitat
        return $query->select(" GEMP_CLIWEB,COD_CLIWEB,USRW_CLIWEB,EMP_CLIWEB,TIPACCESO_CLIWEB,TIPO_CLIWEB,DIRM_CLIWEB,NOM_CLIWEB,EMAIL_CLIWEB,PER_CLIWEB,FECALTA_CLIWEB,USRALTA_CLIWEB,FECMODI_CLIWEB,USRMODI_CLIWEB,FECMODIPWD_CLIWEB,USRMODIPWD_CLIWEB,NLLIST1_CLIWEB,NLLIST2_CLIWEB,NLLIST3_CLIWEB,NLLIST4_CLIWEB,NLLIST5_CLIWEB,NLLIST6_CLIWEB,NLLIST7_CLIWEB,NLLIST8_CLIWEB,NLLIST9_CLIWEB,NLLIST10_CLIWEB,IDIOMA_CLIWEB,NLLIST11_CLIWEB,NLLIST12_CLIWEB,NLLIST13_CLIWEB,NLLIST14_CLIWEB,NLLIST15_CLIWEB,NLLIST16_CLIWEB,NLLIST17_CLIWEB,NLLIST18_CLIWEB,NLLIST19_CLIWEB,NLLIST20_CLIWEB,PUBLI_CLIWEB,COD2_CLIWEB,TIENDA_CLIWEB,TYPE_UPDATE_CLIWEB,DATE_UPDATE_CLIWEB,USR_UPDATE_CLIWEB,PERMISSION_ID_CLIWEB");
	}

}
