<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgAsigl1_Aux extends Model
{
    protected $table = 'FGASIGL1_AUX';
    protected $primaryKey = 'EMP_ASIGL1, SUB_ASIGL1, REF_ASIGL1, LIN_ASIGL1';

    public $timestamps = false;
    public $incrementing = false;
 	//public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	const PUJREP_ASIGL1_INFERIOR = 'L';
	const PUJREP_ASIGL1_CONTRAOFERTA = 'C';
	const PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA = 'K';
	const PUJREP_ASIGL1_COMPRAR_ONLINE = 'Y';
	const PUJREP_ASIGL1_COMPRAR_VD = 'B';

	const TYPE_ASIGL1_NORMAL = "N";
	const TYPE_ASIGL1_AUTO = "A";

	#Puja Creada al adjudicar un lote, DESDE LA Api, a un licitador que no tiene puja por el importe adjudicado
	const TYPE_AWARD = "Z";

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_asigl1' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }

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

	public function scopeJoinAsigl0($query){
        return $query->join("FGASIGL0", "EMP_ASIGL0 = EMP_ASIGL1 AND SUB_ASIGL0 = SUB_ASIGL1 AND REF_ASIGL0 = REF_ASIGL1 ");
	}

	public function scopeJoinFghces1Asigl0($query){
		return $query->joinAsigl0()
        		->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
	}

	public function scopeJoinCli($query){
        return $query->join("FGLICIT", "EMP_LICIT = EMP_ASIGL1 AND SUB_LICIT = SUB_ASIGL1 AND COD_LICIT = LICIT_ASIGL1 ")
                     ->join("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT");

    }



	public static function getPujasAuxiliares($cod_cli, $types = [])
	{
		return self::select('fgasigl1_aux.imp_asigl1', 'fgasigl1_aux.type_asigl1', 'fgasigl1_aux.fec_asigl1', 'fgasigl1_aux.pujrep_asigl1')
			->addSelect('fghces1.num_hces1', 'fghces1.lin_hces1', 'fghces1.prop_hces1', 'fghces1.descweb_hces1', 'fghces1.titulo_hces1')
			->addSelect('fgasigl0.sub_asigl0', 'fgasigl0.ref_asigl0', 'fgasigl0.cerrado_asigl0', 'fgasigl0.impsalhces_asigl0', 'fgasigl0.imptas_asigl0', 'fgasigl0.impadj_asigl0', 'fgasigl0.idorigen_asigl0')
			->addSelect('fgsub.cod_sub', 'fgsub.tipo_sub')
			->addSelect('auc."name"', 'auc."id_auc_sessions"')
			->joinCli()
			->joinFghces1Asigl0()
			->join('fgsub', 'fgsub.emp_sub = fgasigl1_aux.emp_asigl1 AND fgsub.cod_sub = fgasigl1_aux.sub_asigl1')
			->join('"auc_sessions" auc','auc."company" = FGASIGL0.EMP_ASIGL0 AND auc."auction" = FGASIGL0.SUB_ASIGL0 and auc."init_lot" <= ref_asigl0 and auc."end_lot" >= ref_asigl0')
			->where([
				['fxcli.cod_cli', $cod_cli],
				['fgasigl0.cerrado_asigl0', 'N'],
			])
			->whereIn('fgasigl1_aux.pujrep_asigl1', $types)
			->orderBy('fgasigl1_aux.fec_asigl1', 'desc')
			->get();
	}

}
