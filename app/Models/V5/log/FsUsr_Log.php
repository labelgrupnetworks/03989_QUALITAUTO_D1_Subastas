<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

use Illuminate\Database\Eloquent\Model;


class FsUsr_Log extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FSUSR_LOG';
	protected $primaryKey = 'COD_USR';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function scopelog($query){

	 #saco todos los campos a mano para poder ocultar password, el password lo habia convertido a utf-8 pero han pedido quitarlo CONVERT(STANDARD_HASH(PSW_USR), 'utf8', 'us7ascii' )  PSW_USR    ,

	 return $query->select(" COD_USR,NOM_USR, ACC_USR,SERVS_USR,CTAEM_USR,PSWEM_USR,AUTEN_USR,NOMEM_USR,DIREM_USR,REFRESMM_USR,TIEMPOPM_USR,ACMENU_USR,IDIOMA_USR,TENVMAIL_USR,TENVFAX_USR,POPUP_USR,BAJA_USR,GESDOC_USR,ALMDEF_USR,TYPE_UPDATE_USR,DATE_UPDATE_USR,USR_UPDATE_USR");

	}

}
