<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cod_paises código del país
 * @property string $des_paises descripción del país
 */
class FsPaises extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FsPaises';
	protected $primaryKey = 'COD_PAISES';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


    # SELECTS
    public function scopeSelectBasicPaises($query){
        return  $query->select("cod_paises", "des_paises");
    }

    # JOINS
    public function scopeJoinLangPaises($query){

        $query->select("cod_paises", "nvl(FSPAISES_LANG.DES_PAISES_LANG,FSPAISES.des_paises) des_paises");
        $query->orderBy("des_paises","ASC");
        $query->leftJoin('FSPAISES_LANG', function ($join) {

            $join   ->on("FSPAISES_LANG.COD_PAISES_LANG", "=", "FSPAISES.cod_paises")
                    ->on("FSPAISES_LANG.LANG_PAISES_LANG", "=", "'".ToolsServiceProvider::getLanguageComplete(\Config::get('app.locale'))."'");

        });

        return  $query;

    }



}
