<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;


class FgDvc1l extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'FgDvc1l';
    protected $primaryKey = 'EMP_DVC1L, ANUM_DVC1L, NUM_DVC1L, LIN_DVC1L';
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
			'emp_dvc1l' => Config::get("app.emp")

        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_dvc1l', Config::get("app.emp"));
        });
	}

	public function getPrimeraLinea($anum, $num, $cod_subs = null){

		$lineas = self::select('titulo_hces1', 'sub_hces1', 'num_hces1', 'lin_hces1', 'auc."name"')
						->joinLotesDvc1L()
						->whereFactura($anum, $num);

		if($cod_subs){
			$lineas->whereIn('sub_hces1', $cod_subs);
		}

		return $lineas->first();
	}

	public function getFacturasLineasPropietario($anum, $num, $cod_cli){

		return self::select('basea_dvc1l', 'implic_hces1', 'impsalhces_asigl0', 'titulo_hces1', 'ref_hces1', 'sub_hces1', 'num_hces1', 'lin_hces1', 'auc."name"')
				->joinLotesDvc1L()
				->joinDvc1lDvc0($cod_cli)
				->whereFactura($anum, $num)
				->get();

	}

	public function scopeWhereFactura($query, $anum, $num)
	{
		return $query->where('anum_dvc1l', $anum)
			->where('num_dvc1l', $num)
			->where('tl_dvc1l', self::TIPO_PROPIETARIO);
	}


	public function scopeJoinDvc1lDvc0($query, $cod_cli)
	{
		return $query->addSelect('cod_dvc0', 'anum_dvc0', 'num_dvc0', 'base_dvc0', 'total_dvc0', 'iva_dvc0')
				->leftjoin('FXDVC0','FGDVC1L.EMP_DVC1L = FXDVC0.EMP_DVC0 AND FGDVC1L.ANUM_DVC1L = FXDVC0.ANUM_DVC0 AND FGDVC1L.NUM_DVC1L = FXDVC0.NUM_DVC0')
				->where('cod_dvc0', $cod_cli);
	}

	public function scopeJoinLotesDvc1L($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		$query->addSelect("NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1");



		#reducimos mucho los tiempos de carga si no cargamos los clob y los convertimos a varchar de 4000
		if ( (env('APP_DEBUG') || Config::get("app.clobToVarchar")) && empty(Config::get("app.NoclobToVarchar"))) {
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

	public function scopeWithBuyerLotsInfo($query)
	{
		return $query->addSelect('FGASIGL0.IMPSALHCES_ASIGL0')
		->leftJoin('FGHCES1', function ($join) {
			$join->on('tl_dvc1l', '=', "'P'")
				->on('FGHCES1.emp_hces1', '=', 'EMP_DVC1L')
				->on('FGHCES1.num_hces1', '=', 'numhces_dvc1l')
				->on('FGHCES1.lin_hces1', '=', 'linhces_dvc1l');
		})
		->leftJoin('FGSUB', 'FGSUB.EMP_SUB = EMP_DVC1L AND FGSUB.COD_SUB = SUB_DVC1L')
		->leftJoin('FGASIGL0', 'FGASIGL0.EMP_ASIGL0 = EMP_DVC1L AND FGASIGL0.NUMHCES_ASIGL0 = NUMHCES_DVC1L AND FGASIGL0.LINHCES_ASIGL0 = LINHCES_DVC1L AND FGASIGL0.SUB_ASIGL0 = SUB_DVC1L')
		->when(Config::get('app.locale') != Config::get('app.fallback_locale'), function ($query) {
			$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
			return $query
				->selectRaw('NVL(FGHCES1_LANG.TITULO_HCES1_LANG, FGHCES1.TITULO_HCES1) AS TITULO_HCES1')
				->selectRaw('NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1) AS DESC_HCES1')
				->selectRaw('NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) AS DESCWEB_HCES1')
				->selectRaw('NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) AS WEBFRIEND_HCES1')
				->selectRaw('NVL(FGSUB_LANG.DES_SUB_LANG, FGSUB.DES_SUB) AS DES_SUB')
				->leftJoin('FGHCES1_LANG', "FGHCES1_LANG.EMP_HCES1_LANG = FGHCES1.EMP_HCES1 AND FGHCES1_LANG.NUM_HCES1_LANG =  FGHCES1.NUM_HCES1 AND FGHCES1_LANG.LIN_HCES1_LANG = FGHCES1.LIN_HCES1 AND FGHCES1_LANG.LANG_HCES1_LANG = '$lang'")
				->leftJoin('FGSUB_LANG', "FGSUB_LANG.EMP_SUB_LANG = FGSUB.EMP_SUB AND FGSUB_LANG.COD_SUB_LANG = FGSUB.COD_SUB AND FGSUB_LANG.LANG_SUB_LANG = '$lang'");
		}, function ($query) {
			return $query->addSelect('FGHCES1.TITULO_HCES1','FGHCES1.DESC_HCES1', 'FGHCES1.DESCWEB_HCES1', 'FGHCES1.WEBFRIEND_HCES1')
				->addSelect('FGSUB.DES_SUB');
		});
	}

	public function scopeWhereMultiplesSeriesAndLines($query, $seriesAndLines = [])
	{
		return $query->when($seriesAndLines, function ($query, $seriesAndLines) {
			foreach ($seriesAndLines as $serieAndLine) {
				$query->orWhere(function ($query) use ($serieAndLine) {
						$query->where('ANUM_DVC1L', $serieAndLine['serie'])
							->where('NUM_DVC1L', $serieAndLine['line']);
				});
			}
			return $query;
		}, function ($query) {
			return $query;
		});
	}
}
