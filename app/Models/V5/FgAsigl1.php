<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgAsigl1 extends Model
{
    protected $table = 'FGASIGL1';
    protected $primaryKey = 'EMP_ASIGL1, SUB_ASIGL1, REF_ASIGL1, LIN_ASIGL1';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];


	const TYPE_NORMAL = "N";
	const TYPE_AUTO = "A";
	#Puja Creada al adjudicar un lote, DESDE LA Api, a un licitador que no tiene puja por el importe adjudicado
	const TYPE_AWARD = "Z";

	//Para contraofertas de Carlandia
	const PUJAREP_ASIGL1_CONTRAOFERTA = 'C';
	const PUJREP_ASIGL1_INFERIOR = 'L';
	const PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA = 'K';
	const PUJREP_ASIGL1_COMPRAR_ONLINE = 'Y';
	const PUJREP_ASIGL1_COMPRAR_VD = 'B';

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_asigl1' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }
/*
	 #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
	 public function scopeWhereUpdateApi($query, $item){
        return $query->where('sub_asigl1', $item["sub_asigl1"])
                    ->where('ref_asigl1', $item["ref_asigl1"])
                    ->where('lin_asigl1', $item["lin_asigl1"]);
    }
*/
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_asigl1', \Config::get("app.emp"));
        });
    }
    protected $casts = [
        'ref_asigl1' => 'float',
        'imp_asigl1' => 'float',

    ];


     #devuelve la linea máxima
     static function getMaxLinArrayRef($idAuction){
        $lotsTmp =self::select("max(lin_asigl1) max_lin,ref_asigl1")
                        ->where("sub_asigl1", $idAuction)
                        ->groupBy("ref_asigl1")
                        ->get();
        $lots = array();
        foreach($lotsTmp as $lot) {
			#es necesario convertir la cadena en string para que los valores decimales no los convierta en enteros, ya que es posible que haya referencias con decimales
			$ref = (string)$lot->ref_asigl1;
            $lots[$ref]= $lot->max_lin;
        }

        return $lots;

     }




    public function scopeJoinCli($query){
        return $query->join("FGLICIT", "EMP_LICIT = EMP_ASIGL1 AND SUB_LICIT = SUB_ASIGL1 AND COD_LICIT = LICIT_ASIGL1 ")
                     ->join("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT");

	}

	public function scopeLeftJoinCli($query){
        return $query->join("FGLICIT", "EMP_LICIT = EMP_ASIGL1 AND SUB_LICIT = SUB_ASIGL1 AND COD_LICIT = LICIT_ASIGL1 ")
                     ->leftjoin("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT");

    }

    public function scopeJoinAsigl0($query){
        return $query->join("FGASIGL0", "EMP_ASIGL0 = EMP_ASIGL1 AND SUB_ASIGL0 = SUB_ASIGL1 AND REF_ASIGL0 = REF_ASIGL1 ");
	}

	public function scopeJoinFghces1Asigl0($query){
		return $query->joinAsigl0()
        		->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
    }

	public function scopeJoinFgAsigl0($query)
	{
		return $query->join(FgAsigl0::class, "SUB_ASIGL0 = SUB_ASIGL1 AND REF_ASIGL0 = REF_ASIGL1 ");
	}

	public static function pujrepTypes()
	{
		return [
			'A' => trans('admin-app.values.pujrep_A'),
			self::PUJREP_ASIGL1_COMPRAR_VD => trans('admin-app.values.pujrep_B'),
			self::PUJAREP_ASIGL1_CONTRAOFERTA => trans('admin-app.values.pujrep_C'),
			'E' => trans('admin-app.values.pujrep_E'),
			'I' => trans('admin-app.values.pujrep_I'),
			self::PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA => trans('admin-app.values.pujrep_K'),
			self::PUJREP_ASIGL1_INFERIOR => trans('admin-app.values.pujrep_L'),
			'O' => trans('admin-app.values.pujrep_O'),
			'P' => trans('admin-app.values.pujrep_P'),
			'R' => trans('admin-app.values.pujrep_R'),
			'S' => trans('admin-app.values.pujrep_S'),
			'T' => trans('admin-app.values.pujrep_T'),
			'U' => trans('admin-app.values.pujrep_U'),
			'W' => trans('admin-app.values.pujrep_W'),
			self::PUJREP_ASIGL1_COMPRAR_ONLINE => trans('admin-app.values.pujrep_Y'),
		];
	}

	public static function types()
	{
		return [
			self::TYPE_NORMAL => trans('admin-app.values.type_asigl1_N'),
			self::TYPE_AUTO => trans('admin-app.values.type_asigl1_A'),
			self::TYPE_AWARD => trans('admin-app.values.type_asigl1_Z'),
		];
	}


	static function depositBid($licit,$codSub,$ref,$impBid,$date){




		#buscar pujas del lote, ordenadas de mayor a menor, para que al ampliar el num no esté repetido
		$bids = self::where("SUB_ASIGL1", $codSub)->where("REF_ASIGL1",$ref)->orderby("IMP_ASIGL1","DESC")->orderby("LIN_ASIGL1","DESC")->get();

		$moveBids=[];
		$linAsigl1 = 1;
		foreach($bids as $bid ){
			if($bid->imp_asigl1 >= $impBid){

				self::where("SUB_ASIGL1", $codSub)->where("REF_ASIGL1", $ref)->where("LIN_ASIGL1", $bid->lin_asigl1)->update(["LIN_ASIGL1" => ($bid->lin_asigl1 + 1)]);
			}else{
				$linAsigl1= $bid->lin_asigl1 +1;
				break;
			}
		}

		self::create( [ "SUB_ASIGL1" => $codSub,
							"REF_ASIGL1" =>$ref,
							"LIN_ASIGL1" =>$linAsigl1,
							"LICIT_ASIGL1" =>$licit,
							"IMP_ASIGL1" =>$impBid,
							"FEC_ASIGL1" =>$date,
							"HORA_ASIGL1" =>date("H:i:s", strtotime($date)),
							"PUJREP_ASIGL1" => "E"
						]);
		#ver la posición de la puja, modificar la posición de las pujas que mueve
	}

	public function scopelog($query){
        return $query->joinUsr()->LeftJoinCli()->select("FXCLI.NOM_CLI, FXCLI.CIF_CLI,FSUSR.NOM_USR, FGASIGL1.*");
	}

	public function scopeJoinUsr($query){
        return $query->leftjoin("FSUSR","FSUSR.COD_USR = FGASIGL1.USR_UPDATE_ASIGL1");
	}

}
