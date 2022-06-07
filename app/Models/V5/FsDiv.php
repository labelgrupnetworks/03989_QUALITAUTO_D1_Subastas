<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;


class FsDiv extends Model
{
	
	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FsDiv';
	protected $primaryKey = 'COD_DIV';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;	

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva
   
        
    # SELECTS
    public function scopeSelectBasicDiv($query){
        return  $query->select("cod_div", "des_div");
    }
    


}