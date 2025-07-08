<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Modelo para las órdenes de licitación (FgOrlic)
 *
 * @property string emp_orlic
 * @property string sub_orlic
 * @property string ref_orlic
 * @property int lin_orlic
 * @property string licit_orlic
 * @property float himp_orlic
 * @property string tipop_orlic
 * @property string operador_orlic
 * @property string usr_update_orlic
 * @property FsOperadores phoneBiddingAgent
 */
class FgOrlic extends Model
{
    protected $table = 'FGORLIC';
    protected $primaryKey = 'EMP_ORLIC, SUB_ORLIC, REF_ORLIC, LIN_ORLIC';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_orlic' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_orlic', Config::get("app.emp"));
        });
    }
    protected $casts = [
        'ref_orlic' => 'float',
        'himp_orlic' => 'float',

    ];


    #devulve las ordenes de una subasta organizadas por referencia y licitador
    static function arrayByRef($idAuction){
        $lotsTmp =self::select(" ref_orlic, licit_orlic")

                        ->where("sub_orlic", $idAuction)->get();
        $lots = array();
        foreach($lotsTmp as $lot) {
			#es necesario convertir la cadena en string para que los valores decimales no los convierta en enteros, ya que es posible que haya referencias con decimales
			$ref = (string)$lot->ref_orlic;
            if(empty($lots[$ref])){
                $lots[$ref] = array();
            }
            $lots[$ref][$lot->licit_orlic] = $lot;
        }

        return $lots;

     }
     #devuelve la linea máxima
     static function getMaxLinArrayRef($idAuction){
        $lotsTmp =self::select("max(lin_orlic) max_lin,ref_orlic")
                        ->where("sub_orlic", $idAuction)
                        ->groupBy("ref_orlic")
                        ->get();
        $lots = array();
        foreach($lotsTmp as $lot) {
			#es necesario convertir la cadena en string para que los valores decimales no los convierta en enteros, ya que es posible que haya referencias con decimales
			$ref = (string)$lot->ref_orlic;
            $lots[$ref]= $lot->max_lin;
        }

        return $lots;

	 }

	 /**
	  * Obtiene la suma de ordenes de la subasta
	  * @return int Retorna valor de la suma
	  */
	 static function getTotalOrdersInAuction($sub_orlic, $ref_orlic, $cod_cli, $isAward = false){

		$lotsAward = [];
		if($isAward){
			$lotsAward = FgCsub::select('REF_CSUB')->where('SUB_CSUB', $sub_orlic)->get()->toArray();
		}

		return self::JoinCli()
			->where([
				['COD_CLI', '=', $cod_cli],
				['SUB_ORLIC', '=', $sub_orlic],
				['REF_ORLIC', '<>', $ref_orlic]
			])
			->whereNotIn('REF_ORLIC', $lotsAward)
			->sum('HIMP_ORLIC');

	 }


       #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
    public function scopeWhereUpdateApi($query, $item) {
        return $query->where('sub_orlic', $item["sub_orlic"])
                    ->where('ref_orlic', $item["ref_orlic"])
                    ->where('licit_orlic', $item["licit_orlic"]);
    }

	public function scopeJoinLicit($query)
	{
		return $query->join("fglicit", function ($join) {
			$join->on("sub_licit", "sub_orlic")
				->on("cod_licit", "licit_orlic")
				->on("emp_licit", "emp_orlic");
		});
	}

    public function scopeJoinCli($query){
        return $query->join("FGLICIT", "EMP_LICIT = EMP_ORLIC AND SUB_LICIT = SUB_ORLIC AND COD_LICIT = LICIT_ORLIC ")
                     ->join("FXCLI", "GEMP_CLI = '". Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT");

    }

	public function scopeleftJoinCli($query){
        return $query->join("FGLICIT", "EMP_LICIT = EMP_ORLIC AND SUB_LICIT = SUB_ORLIC AND COD_LICIT = LICIT_ORLIC ")
                     ->leftjoin("FXCLI", "GEMP_CLI = '". Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT");

    }

    public function scopeJoinAsigl0($query){
        return $query->join("FGASIGL0", "EMP_ASIGL0 = EMP_ORLIC AND SUB_ASIGL0 = SUB_ORLIC AND REF_ASIGL0 = REF_ORLIC ");
    }

	//no funcionara sino se hace el antes el join con Asigl0
	public function scopeJoinFghces1($query)
	{
        return $query->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
    }

	public function scopeAddSelectHasMaxBidds($query)
	{
		return $query->addSelect([
			'has_max_bidds' => function ($subQuery) {
				$subQuery->select(DB::raw('CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END'))
					->from('fgasigl1 as bids')
					->whereColumn('bids.emp_asigl1', 'fgorlic.emp_orlic')
					->whereColumn('bids.sub_asigl1', 'fgorlic.sub_orlic')
					->whereColumn('bids.ref_asigl1', 'fgorlic.ref_orlic')
					->whereColumn('bids.imp_asigl1', '>', 'fgorlic.himp_orlic');
			}
		]);
	}

	public function scopeAddSelectIsMaxOrder($query)
	{
		return $query->addSelect([
			'is_max_order' => function ($subQuery) {
				$subQuery->select(DB::raw('CASE WHEN max_order.licit_orlic = fgorlic.licit_orlic THEN 1 ELSE 0 END'))
					->from('fgorlic as max_order')
					->whereColumn('max_order.emp_orlic', 'fgorlic.emp_orlic')
					->whereColumn('max_order.sub_orlic', 'fgorlic.sub_orlic')
					->whereColumn('max_order.ref_orlic', 'fgorlic.ref_orlic')
					->orderBy('max_order.himp_orlic', 'desc')
					->orderBy('max_order.fec_orlic', 'desc')
					->limit(1);
			}
		]);
	}

	public function getTipoOrderTypeAttribute()
	{
		return $this->getTipoOrderType()[$this->tipop_orlic] ?? $this->tipop_orlic;
	}

	public function getTipoOrderType(){

		return [
			'W' => "Web",
			'T' => "Teléfono",
			'S' => "Sala",
			'I' => "Internacional",
			'E' => "Libro",
			'O' => "Libro Web",
			'P' => "Puja",
			'R' => "Réplica",
			'U' => "Subalia",
		];

	}

	public function scopelog($query){
        return $query->joinUsr()->LeftJoinCli()->select("FXCLI.NOM_CLI, FXCLI.CIF_CLI,FSUSR.NOM_USR, FGORLIC.*");
	}

	public function scopeJoinUsr($query){
        return $query->leftjoin("FSUSR","FSUSR.COD_USR = FGORLIC.USR_UPDATE_ORLIC");
	}

	/**
	 * Relación con el modelo FsOperadores
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function phoneBiddingAgent()
	{
		return $this->belongsTo(FsOperadores::class, 'operador_orlic', 'cod_operadores')
			->where('emp_operadores', Config::get("app.emp"));

	}


}
