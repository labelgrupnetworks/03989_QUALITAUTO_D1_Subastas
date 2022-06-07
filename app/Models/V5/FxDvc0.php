<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class FxDvc0 extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'Fxdvc0';
    protected $primaryKey = 'EMP_DVC0, ANUM_DVC0, NUM_DVC0';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public const TIPO_PROPIETARIO = "P";
	public const TIPO_GASTO = "G";
	public const FECHA_MIN_FACTURA = "2017-01-01";


	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_dvc0' => \Config::get("app.emp"),
			'gemp_dvc0' => \Config::get("app.gemp"),

        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_dvc0', \Config::get("app.emp"))
					->where('gemp_dvc0', \Config::get("app.gemp"))
			;
        });
	}



	public function scopeWhereUpdateApi($query, $item){
		return $query->where('anum_dvc0', $item["anum_dvc0"])
				->where('num_dvc0', $item["num_dvc0"]);

	}

	public function getFacturasPropietario($cod_cli){

		return self::select('anum_dvc0', 'num_dvc0', 'fecha_dvc0', 'base_dvc0', 'impiva_dvc0', 'total_dvc0', 'iva_dvc0', 'basea_dvc1l', 'implic_hces1', 'impsalhces_asigl0', 'ref_hces1', 'sub_hces1', 'num_hces1', 'lin_hces1', 'auc."name"')
			->joinDvc1lDvc0()
			->joinLotesDvc1L()
			->where('tl_dvc1l', self::TIPO_PROPIETARIO)
			->where('prop_hces1', $cod_cli)
			->where('fecha_dvc0', '>', date("Y-m-d",strtotime("-1 year")))
			->orderBy('fecha_dvc0', 'desc')
			->get();

	}

	public function getFacturasCabecerasPropietario($cod_cli, $period = null)
	{
		$facturas = self::select('anum_dvc0', 'num_dvc0')
		->where('tipo_dvc0', self::TIPO_PROPIETARIO)
			->where('cod_dvc0', $cod_cli);

		if ($period) {
			$facturas->where('fecha_dvc0', '>', date("Y-m-d", strtotime("-$period month")));
		}

		return $facturas->orderBy('fecha_dvc0', 'desc')->get();
	}

	public function scopeJoinDvc1lDvc0($query)
	{
		return $query->leftjoin('FGDVC1L','FGDVC1L.EMP_DVC1L = FXDVC0.EMP_DVC0 AND FGDVC1L.ANUM_DVC1L = FXDVC0.ANUM_DVC0 AND FGDVC1L.NUM_DVC1L = FXDVC0.NUM_DVC0');
	}



	/**
	 * Necesario antes haber realizado el join con fgdvc1l
	 */
	public function scopeJoinLotesDvc1L($query)
	{
		$lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
		$query->addSelect("NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1");



		#reducimos mucho los tiempos de carga si no cargamos los clob y los convertimos a varchar de 4000
		if ( env('APP_DEBUG') || \Config::get("app.clobToVarchar")) {
			$query = $query->addSelect("dbms_lob.substr(NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1), 4000, 1 ) DESCWEB_HCES1")
							->addSelect(" dbms_lob.substr(NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1), 4000, 1 ) DESC_HCES1");
		}else{
			$query = $query->addSelect(" NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) DESCWEB_HCES1")
					->addSelect(" NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1) DESC_HCES1");
		}

		$query->leftjoin('FGHCES1','FGHCES1.EMP_HCES1 = FGDVC1L.EMP_DVC1L AND FGHCES1.NUM_HCES1 = FGDVC1L.NUMHCES_DVC1L AND FGHCES1.LIN_HCES1 = FGDVC1L.LINHCES_DVC1L AND FGHCES1.PROP_HCES1 = FGDVC1L.COD_DVC1L');
		$query->leftjoin('FGHCES1_LANG',"FGHCES1_LANG.EMP_HCES1_LANG = FGHCES1.EMP_HCES1 AND FGHCES1_LANG.NUM_HCES1_LANG = FGHCES1.NUM_HCES1 AND FGHCES1_LANG.LIN_HCES1_LANG = FGHCES1.LIN_HCES1 AND FGHCES1_LANG.LANG_HCES1_LANG = '" . $lang . "'");
		$query->leftjoin('FGASIGL0', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
		return $query->join('"auc_sessions" auc','auc."company" = FGASIGL0.EMP_ASIGL0 AND auc."auction" = FGASIGL0.SUB_ASIGL0 and auc."init_lot" <= ref_asigl0 and   auc."end_lot" >= ref_asigl0');
	}

}
