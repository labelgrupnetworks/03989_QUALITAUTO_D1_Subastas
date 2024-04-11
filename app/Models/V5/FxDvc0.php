<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
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


	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_dvc0' => Config::get("app.emp"),
			'gemp_dvc0' => Config::get("app.gemp"),

		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_dvc0', Config::get("app.emp"))
				->where('gemp_dvc0', Config::get("app.gemp"));
		});
	}

	public function scopeWhereUpdateApi($query, $item)
	{
		return $query->where('anum_dvc0', $item["anum_dvc0"])
			->where('num_dvc0', $item["num_dvc0"]);
	}

	public static function getInvoicesByOwnerQuery($ownerCod)
	{
		return self::query()
			->select('anum_dvc0', 'num_dvc0', 'fecha_dvc0', 'base_dvc0', 'impiva_dvc0', 'total_dvc0', 'iva_dvc0', 'basea_dvc1l')
			->addSelect('impsalhces_asigl0', 'ref_asigl0', 'sub_asigl0', 'implic_hces1', 'num_hces1', 'lin_hces1')
			->addSelect(DB::raw('(select sum(imp_pcob) from fxpcob where emp_pcob = emp_dvc0 and anum_pcob = anum_dvc0 and num_pcob = num_dvc0) as imp_pending'))
			->joinDvc1lDvc0()
			->joinLotesDvc1L()
			->IsSettled()
			->whereOwner($ownerCod);
	}

	public static function getInvoicesYearsAvailables($ownerCod)
	{
		return self::selectRaw('to_char(fecha_dvc0, \'YYYY\') as year')
			->whereOwner($ownerCod)
			->groupBy('to_char(fecha_dvc0, \'YYYY\')')
			->orderBy('to_char(fecha_dvc0, \'YYYY\')', 'desc')
			->pluck('year');
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

	public function scopeWhereDateIsGreaterThan($query, $date)
	{
		if (!$date) {
			$date = date("Y-m-d", strtotime("-1 year"));
		}
		return $query->where('fecha_dvc0', '>=', $date);
	}

	public function scopeWhereYearsDates($query, $yearDates)
	{
		$datesIntervals = array_map(function($year){
			return [
				$year . '-01-01',
				$year . '-12-31'
			];
		}, $yearDates);

		return $query->where(function($query) use ($datesIntervals){
			foreach($datesIntervals as $interval){
				$query->orWhereBetween('fecha_dvc0', $interval);
			}
		});
	}

	public function scopeWhereOwner($query, $cod_cli)
	{
		return $query->where('tipo_dvc0', self::TIPO_PROPIETARIO)
			->where('cod_dvc0', $cod_cli);
	}

	/**
	 * Si una factura no esta asentada es que aún se está trabajando en ella
	 */
	public function scopeIsSettled($query)
	{
		return $query->where('asent_dvc0', 'S');
	}

	public function scopeJoinDvc1lDvc0($query)
	{
		return $query->leftjoin('FGDVC1L', 'FGDVC1L.EMP_DVC1L = FXDVC0.EMP_DVC0 AND FGDVC1L.ANUM_DVC1L = FXDVC0.ANUM_DVC0 AND FGDVC1L.NUM_DVC1L = FXDVC0.NUM_DVC0');
	}

	/**
	 * Necesario antes haber realizado el join con fgdvc1l
	 */
	public function scopeJoinLotesDvc1L($query)
	{
		$isLocale = Config::get('app.locale') == Config::get('app.fallback_locale');
		//$isDebug = (Config::get('app.debug') || Config::get("app.clobToVarchar")) && empty(Config::get("app.NoclobToVarchar"));

		return $query
		->leftjoin('FGHCES1', 'FGHCES1.EMP_HCES1 = FGDVC1L.EMP_DVC1L AND FGHCES1.NUM_HCES1 = FGDVC1L.NUMHCES_DVC1L AND FGHCES1.LIN_HCES1 = FGDVC1L.LINHCES_DVC1L')
		->leftjoin('FGASIGL0', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0')
		->leftjoin('FGSUB', 'FGSUB.EMP_SUB = FGASIGL0.EMP_ASIGL0 AND FGSUB.COD_SUB = FGASIGL0.SUB_ASIGL0')
		->join('"auc_sessions" auc', 'auc."company" = FGASIGL0.EMP_ASIGL0 AND auc."auction" = FGASIGL0.SUB_ASIGL0 and auc."init_lot" <= ref_asigl0 and   auc."end_lot" >= ref_asigl0')
		->selectRaw("(SELECT COUNT(DISTINCT(LICIT_ASIGL1)) FROM FGASIGL1 WHERE EMP_ASIGL1 = EMP_ASIGL0 AND SUB_ASIGL1 = SUB_ASIGL0 AND REF_ASIGL1 = REF_ASIGL0) licits")
		->selectRaw("(SELECT COUNT(LIN_ASIGL1) FROM FGASIGL1 WHERE EMP_ASIGL1 = EMP_ASIGL0 AND SUB_ASIGL1 = SUB_ASIGL0 AND REF_ASIGL1 = REF_ASIGL0) bids")
		->when($isLocale, function ($query) {
			return $query->addSelect('fgsub.des_sub', 'FGHCES1.desc_hces1', 'FGHCES1.descweb_hces1', 'FGHCES1.webfriend_hces1');
		}, function ($query) {
			$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
			return $query->selectRaw('NVL(FGSUB_LANG.des_sub_lang, fgsub.des_sub) des_sub')
			->selectRaw('NVL(FGHCES1_LANG.webfriend_hces1_lang, fghces1.webfriend_hces1) webfriend_hces1')
			->selectRaw('NVL(FGHCES1_LANG.descweb_hces1_lang, fghces1.descweb_hces1) descweb_hces1')
			->selectRaw('NVL(FGHCES1_LANG.desc_hces1_lang, fghces1.desc_hces1) desc_hces1')
			->leftjoin('FGHCES1_LANG', "FGHCES1_LANG.EMP_HCES1_LANG = FGHCES1.EMP_HCES1 AND FGHCES1_LANG.NUM_HCES1_LANG = FGHCES1.NUM_HCES1 AND FGHCES1_LANG.LIN_HCES1_LANG = FGHCES1.LIN_HCES1 AND FGHCES1_LANG.LANG_HCES1_LANG = '$lang'")
			->leftjoin('FGSUB_LANG', "FGSUB_LANG.EMP_SUB_LANG = FGASIGL0.EMP_ASIGL0 AND FGSUB_LANG.COD_SUB_LANG = FGASIGL0.SUB_ASIGL0 AND FGSUB_LANG.LANG_SUB_LANG = '$lang'");
		});

		/*
		->leftjoin('FGHCES1_LANG', "FGHCES1_LANG.EMP_HCES1_LANG = FGHCES1.EMP_HCES1 AND FGHCES1_LANG.NUM_HCES1_LANG = FGHCES1.NUM_HCES1 AND FGHCES1_LANG.LIN_HCES1_LANG = FGHCES1.LIN_HCES1 AND FGHCES1_LANG.LANG_HCES1_LANG = '" . $lang . "'")
		->when($isDebug, function ($query) {
			return $query->addSelect("dbms_lob.substr(NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1), 4000, 1 ) DESCWEB_HCES1")
			->addSelect(" dbms_lob.substr(NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1), 4000, 1 ) DESC_HCES1");
		}, function ($query) {
			return $query->addSelect(" NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) DESCWEB_HCES1")
			->addSelect(" NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1) DESC_HCES1");
		}); */
	}
}
