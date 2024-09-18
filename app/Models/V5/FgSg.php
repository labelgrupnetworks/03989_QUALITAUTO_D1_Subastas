<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;


class FgSg extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FgSg';
	protected $primaryKey = 'COD_SG';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


    # SELECTS
    public function scopeSelectBasicSg($query){
        return  $query->select("cod_sg", "des_sg");
    }

	public static function getList()
	{
		return self::pluck('des_sg', 'cod_sg');
	}


    # JOINS
    public function scopeJoinLangSg($query){

        $query->select("cod_sg", "nvl(FGSG_LANG.DES_SG_LANG,FGSG.des_SG) des_SG");
        $query->leftJoin('FGSG_LANG', function ($join) {

            $join   ->on("FGSG_LANG.COD_SG_LANG", "=", "FGSG.cod_SG")
                    ->on("FGSG_LANG.LANG_SG_LANG", "=","'".ToolsServiceProvider::getLanguageComplete(\Config::get('app.locale'))."'");

        });
        return  $query;

    }



}
