<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxClid extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FxClid';
	protected $primaryKey = 'GEMP_CLID, CLI_CLID, CODD_CLID';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva



	public function __construct(array $vars = []){
        $this->attributes=[
			'gemp_clid' => \Config::get("app.gemp"),
			'codd_clid'=> 'W1' #valor por defecto de la direccion, significa que es la principal de web
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('gemp', function(Builder $builder) {
            $builder->where('gemp_clid', \Config::get("app.gemp"));
        });
	}

	public function scopeWhereUpdateApi($query, $item){
		return $query->where('cli2_clid', $item["cli2_clid"])->where("codd_cliD",'W1');
	}

	public function getForSelectHTML( $cod_user){
		#ordenamos descendientemente codd_clid para que salga primero W1, que es la predeterminada
		$fxclid = $this->select("CODD_CLID, DIR_CLID, CP_CLID")->where("CLI_CLID",$cod_user )->orderby("CODD_CLID", "DESC")->get();
		$address = array();
		foreach ($fxclid as $clid){
			$address[$clid->codd_clid] = $clid->dir_clid." (".$clid->cp_clid.")";
		}
		 return $address;
	}
}
