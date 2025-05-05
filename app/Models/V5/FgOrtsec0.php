<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Override\RelationCollection;
use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * @property string emp_ortsec0
 * @property string sub_ortsec0
 * @property int lin_ortsec0
 * @property string des_ortsec0
 * @property string key_ortsec0
 * @property int orden_ortsec0
 * @property string meta_titulo_ortsec0
 * @property string meta_description_ortsec0
 * @property string meta_contenido_ortsec0
 */
class FgOrtsec0 extends Model
{
	protected $table = 'FGORTSEC0';
	protected $primaryKey = 'EMP_ORTSEC0, SUB_ORTSEC0, LIN_ORTSEC0';

	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];

	const SUB_ORTSEC0_DEPARTAMENTOS = 'DEP';

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_ortsec0' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}
	protected $casts = [
		'lin_ortsec0' => 'int',
		'orden_ortsec0' => 'int',
	];


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_ortsec0', Config::get("app.emp"));
		});
	}

	public function newCollection(array $models = [])
	{
		return new RelationCollection($models);
	}

	public function departmentRoutePage(): Attribute
	{
		return Attribute::make(
			get: fn() => route('department', ['text' => $this->key_ortsec0])
		);
	}

	#esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
	public function scopeWhereUpdateApi($query, $item)
	{
		#la subasta debe ser la 0
		return $query->where('sub_ortsec0', "0")->where('lin_ortsec0', $item["lin_ortsec0"]);
	}

	#listado de categorias, evitar sacar textos clob
	public function scopeGetAllFgOrtsec0($query, $sub_ortsec = 0)
	{
		return $query->JoinLangFgOrtsec0()
			->addSelect("FGORTSEC0.LIN_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.DES_ORTSEC0_LANG, FGORTSEC0.DES_ORTSEC0) DES_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.KEY_ORTSEC0_LANG, FGORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0")
			->where("FGORTSEC0.SUB_ORTSEC0", $sub_ortsec)
			->orderby("FGORTSEC0.ORDEN_ORTSEC0")
			->orderby("NVL(FGORTSEC0_LANG.DES_ORTSEC0_LANG, FGORTSEC0.DES_ORTSEC0) ");
	}

	public function scopeJoinLangFgOrtsec0($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		return $query->leftjoin('FGORTSEC0_LANG', "FGORTSEC0_LANG.EMP_ORTSEC0_LANG = FGORTSEC0.EMP_ORTSEC0 AND FGORTSEC0_LANG.SUB_ORTSEC0_LANG = FGORTSEC0.SUB_ORTSEC0 AND FGORTSEC0_LANG.LIN_ORTSEC0_LANG = FGORTSEC0.LIN_ORTSEC0 AND LANG_ORTSEC0_LANG  = '" . $lang . "'");
	}

	public function getOrtsec0LinFromKey($key)
	{
		return ($this->select("LIN_ORTSEC0")
			->where("KEY_ORTSEC0", $key)
			->first())?->lin_ortsec0;
	}


	#INFO de categorias
	public function GetInfoFgOrtsec0($key, $sub_ortsec = 0)
	{

		if (empty($key) || !is_numeric($key)) {
			return null;
		}

		return $this->JoinLangFgOrtsec0()
			->addSelect("FGORTSEC0.LIN_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.META_TITULO_ORTSEC0_LANG, FGORTSEC0.META_TITULO_ORTSEC0) META_TITULO_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.META_DESCRIPTION_ORTSEC0_LANG, FGORTSEC0.META_DESCRIPTION_ORTSEC0) META_DESCRIPTION_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.META_CONTENIDO_ORTSEC0_LANG, FGORTSEC0.META_CONTENIDO_ORTSEC0) META_CONTENIDO_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.DES_ORTSEC0_LANG, FGORTSEC0.DES_ORTSEC0) DES_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.KEY_ORTSEC0_LANG, FGORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0")
			->where("FGORTSEC0.LIN_ORTSEC0", $key)
			->where("FGORTSEC0.SUB_ORTSEC0", $sub_ortsec)
			->first();
	}

	public function getInfoWithKeyOrtsec($keyOrtsec)
	{

		return $this->JoinLangFgOrtsec0()
			->addSelect("FGORTSEC0.LIN_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.META_TITULO_ORTSEC0_LANG, FGORTSEC0.META_TITULO_ORTSEC0) META_TITULO_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.META_DESCRIPTION_ORTSEC0_LANG, FGORTSEC0.META_DESCRIPTION_ORTSEC0) META_DESCRIPTION_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.META_CONTENIDO_ORTSEC0_LANG, FGORTSEC0.META_CONTENIDO_ORTSEC0) META_CONTENIDO_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.DES_ORTSEC0_LANG, FGORTSEC0.DES_ORTSEC0) DES_ORTSEC0")
			->addSelect("NVL(FGORTSEC0_LANG.KEY_ORTSEC0_LANG, FGORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0")
			->where("nvl(FGORTSEC0_LANG.KEY_ORTSEC0_LANG,FGORTSEC0.KEY_ORTSEC0)", $keyOrtsec)
			->where("FGORTSEC0.SUB_ORTSEC0", self::SUB_ORTSEC0_DEPARTAMENTOS)
			->first();
	}


	public function getLinFgOrtsec($keyOrtsec)
	{
		$ortsec0 = $this->select("FGORTSEC0.LIN_ORTSEC0")
			->JoinLangFgOrtsec0()
			->where("nvl(FGORTSEC0_LANG.KEY_ORTSEC0_LANG,FGORTSEC0.KEY_ORTSEC0)", $keyOrtsec)
			->first();

		if (!empty($ortsec0)) {
			return $ortsec0->lin_ortsec0;
		} else {
			return null;
		}
	}

	public function scopeJoinOrtsec1FgOrtsec0($query)
	{
		return $query->leftjoin('FGORTSEC1', "FGORTSEC1.EMP_ORTSEC1 = FGORTSEC0.EMP_ORTSEC0 AND FGORTSEC1.SUB_ORTSEC1 = FGORTSEC0.SUB_ORTSEC0 AND FGORTSEC1.LIN_ORTSEC1 = FGORTSEC0.LIN_ORTSEC0");
	}

	public function scopeJoinSecOrtsec0($query)
	{
		return $query->join('FXSEC', "FXSEC.GEMP_SEC= '" . config('app.gemp') . "'  AND FXSEC.COD_SEC = FGORTSEC1.SEC_ORTSEC1");
	}

	public static function getCategoriesWhereAuctionsQuery($auctionsStatus = [FgSub::SUBC_SUB_ACTIVO, FgSub::SUBC_SUB_HISTORICO])
	{
		return self::query()
			->where('fgortsec0.sub_ortsec0', 0)
			->where(function ($query) use ($auctionsStatus) {
				$query->whereExists(function ($subquery) use ($auctionsStatus) {
					$subquery->select(DB::raw(1))
						->from('fgortsec1')
						->join('fghces1', function ($join) {
							$join->on('fghces1.emp_hces1', 'fgortsec1.emp_ortsec1')
								->on('fghces1.sec_hces1', 'fgortsec1.sec_ortsec1');
						})
						->join('fgasigl0', function ($join) {
							$join->on('fghces1.emp_hces1', 'fgasigl0.emp_asigl0')
								->on('fghces1.num_hces1', 'fgasigl0.numhces_asigl0')
								->on('fghces1.lin_hces1', 'fgasigl0.linhces_asigl0');
						})
						->join('fgsub', function ($join) {
							$join->on('fgsub.emp_sub', 'fgasigl0.emp_asigl0')
								->on('fgsub.cod_sub', 'fgasigl0.sub_asigl0');
						})
						->whereColumn('fgortsec1.emp_ortsec1', 'fgortsec0.emp_ortsec0')
						->whereColumn('fgortsec1.sub_ortsec1', 'fgortsec0.sub_ortsec0')
						->whereColumn('fgortsec1.lin_ortsec1', 'fgortsec0.lin_ortsec0')
						->whereIn('fgsub.subc_sub', $auctionsStatus);
				});
			});
	}


	#comento el código para ir usando solo las funciones  que necesitemos

	/* public function scopeJoinHces1FgOrtsec0($query)
	{
		return $query->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGORTSEC0.EMP_ORTSEC0 AND FGHCES1.SEC_HCES1 = FGORTSEC1.SEC_ORTSEC1');
	}

	#se consideraran activas todas las categorias de subastas activas o historicas ("S","H")
	public function scopeGetActiveFgOrtsec0($query)
	{
		return $query->select("KEY_ORTSEC0")
			->JoinOrtsec1FgOrtsec0()
			->JoinHces1FgOrtsec0()
			#en subalia la subasta del HCES1 no variará, po lo que podemos enlazar directamente a subastas sin usar asigl0
			->join("FGSUB", "FGSUB.EMP_SUB = FGORTSEC0.EMP_ORTSEC0 AND FGSUB.COD_SUB = FGHCES1.SUB_HCES1")
			->where("FGORTSEC0.SUB_ORTSEC0", 0)
			->wherein("FGSUB.SUBC_SUB", array("S", "H"))
			->groupby("KEY_ORTSEC0");
	} */
}
