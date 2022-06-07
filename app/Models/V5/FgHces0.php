<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
use DB;



class FgHces0 extends Model
{
    protected $table = 'fghces0';
    protected $primaryKey = 'EMP_HCES0, NUM_HCES0';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_hces0' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_hces0', \Config::get("app.emp"));
        });
    }

    #devuelve el numhces que hay que usar
    static function getNumHces($codSub){
            #si la subasta ya tiene uno lo devuelve
            $num = self::select("max(num_hces0) as maxnum")->where("sub_hces0", $codSub)->first()->maxnum;
            #si no, le suma 1 al próximo y
            if(empty($num)){

				#no podemos usar el siguiente num_hces0 por que borraban subastas y se volvian a asignar valores ya existentes y daba problemas  con las imagenes cargadas
				//$num = self::select("max(num_hces0) as maxnum")->first()->maxnum;
				#no tocar valores ya que si no el contador vuelve a empezar
				$contador = DB::select("select CONTADOR2_ORA('c01','001','2020-01-01','A') as num  from dual");

				#esta funcion siempre debe devolver un valor por lo que si fallara mejor que de una excepcion y el cliente nos avise
                $num = $contador[0]->num;

            }
            return $num;
    }

    static function setNumHces($codSub, $numHces){
        #si la subasta ya tiene uno lo devuelve
        $num = self::select("max(num_hces0) as maxnum")->where("sub_hces0", $codSub)->where("num_hces0", $numHces)->first()->maxnum;
        #si no, le suma 1 al próximo y
        if(empty($num)){
            $hces0 = array("sub_hces0" => $codSub, "num_hces0" => $numHces );

			if(\Config::get("app.propHces0")){
				$hces0["prop_hces0"] =  \Config::get("app.propHces0");
			}
            self::create($hces0);
        }

    }


	public function hojaCesionLineas()
	{
		return $this->hasMany(FgHces1::class, 'num_hces1', 'num_hces0');
	}

}

