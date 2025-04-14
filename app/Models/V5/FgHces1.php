<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Models\V5\Traits\Hces1Asigl0Methods;
use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgHces1 extends Model
{
	use Hces1Asigl0Methods;

	protected $table = 'fghces1';
	protected $primaryKey = 'EMP_HCES1, NUM_HCES1, LIN_HCES1';

	public $timestamps = false;
	public $incrementing = false;
	//   public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $casts = [
		"ref_hces1" => "float",
		"impsal_hces1" => "float",
		"imptasini_hces1" => "float",
		"imptash_hces1" => "float",
		"impres_hces1" => "float",
		"pc_hces1" => "float",
		"coml_hces1" => "float",
		"comlini_hces1" => "float",
		"comp_hces1" => "float",
		"compini_hces1" => "float",
		"nobj_hces1" => "int",
		"alto_hces1" => "float",
		"ancho_hces1" => "float",
		"diam_hces1" => "float",
		"grueso_hces1" => "float",
		"peso_hces1" => "float",
		"pesovol_hces1" => "float",
	];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_hces1' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_hces1', Config::get("app.emp"));
		});
	}

	#esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
	public function scopeWhereUpdateApi($query, $item)
	{
		return $query->where('idorigen_hces1', $item["idorigen_hces1"]);
	}

	static function getlinHces($codSub, $numHces)
	{
		#si la subasta ya tiene uno lo devuelve
		$lin = self::select("max(lin_hces1) as maxnum")->where("sub_hces1", $codSub)->where("num_hces1", $numHces)->first()->maxnum;
		#si no
		if (empty($lin)) {
			$lin = 1;
		} else {
			$lin = $lin + 1;
		}
		return $lin;
	}

	public function scopeGetRarity($query)
	{
		return $query->join('"object_types_values" otv', 'otv."company" =  emp_HCES1 and  otv."transfer_sheet_number" =NUM_HCES1 AND  otv."transfer_sheet_line" = lin_HCES1')
			->leftJoin('"object_types_values_lang" otv_lang', 'otv_lang."company_lang" =  emp_HCES1 and  otv_lang."transfer_sheet_number_lang" = NUM_HCES1 AND  otv_lang."transfer_sheet_line_lang" = lin_HCES1 AND otv_lang."lang_object_types_values_lang" = \'' . Config::get('app.language_complete')[Config::get('app.locale')] . '\'')
			->addSelect('nvl(otv_lang."rarity_lang",otv."rarity") AS RARITY');
	}

	public function scopeGetOwner($query)
	{
		return $query->join('FXCLI', 'FXCLI.COD_CLI = FGHCES1.PROP_HCES1 AND FXCLI.GEMP_CLI = ' . Config::get('app.gemp'))
			->addSelect('FXCLI.RSOC_CLI');
	}

	public function scopeWhereOwner($query, $cod_cli, $withRatio = true)
	{
		return $query
			->when($withRatio, fn ($query) => $query->addSelect('COALESCE(FGHCESMT.ratio_hcesmt, MT0.ratio_hcesmt) as ratio_hcesmt'))
			->leftJoin('FGHCESMT', "FGHCESMT.EMP_HCESMT = FGHCES1.EMP_HCES1 AND FGHCESMT.NUM_HCESMT = FGHCES1.NUM_HCES1 AND FGHCESMT.CLI_HCESMT = '$cod_cli' AND FGHCESMT.LIN_HCESMT = FGHCES1.LIN_HCES1")
			->leftJoin('FGHCESMT MT0', "MT0.EMP_HCESMT = FGHCES1.EMP_HCES1 AND MT0.NUM_HCESMT = FGHCES1.NUM_HCES1 AND MT0.CLI_HCESMT = '$cod_cli' AND MT0.LIN_HCESMT = 0")
			->whereNotNull('COALESCE(FGHCESMT.ratio_hcesmt, MT0.ratio_hcesmt)');
	}

	public function scopeNotInAuction($query)
	{
		return $query->leftJoinFghces1Fgsub()
			->where(function ($query) {
				$query->where('sub_hces1', '=', 'AGRUP17')
					->orWhereIn('fgsub.subc_sub', [FgSub::SUBC_SUB_INACTIVO, FgSub::SUBC_SUB_ADMINISITRADOR]);
			});
	}

	public function scopeIsVisibleWeb($query)
	{
		return $query->where('web_hces1', 'S');
	}

	public function scopeIsNotReturnedOrWithdrawn($query)
	{
		return $query->where('fac_hces1', '!=', 'D')
			->where('fac_hces1', '!=', 'R');

	}

	public function scopelog($query)
	{
		return $query->joinUsr()->select("FSUSR.NOM_USR, FGHCES1.*");
	}

	public function scopeJoinUsr($query)
	{
		return $query->leftjoin("FSUSR", "FSUSR.COD_USR = FGHCES1.USR_UPDATE_HCES1");
	}

	public function scopeLeftJoinFghces1Asigl0($query)
	{
		return $query->leftjoin('fgasigl0', function ($join) {
			$join->on('fgasigl0.emp_asigl0', '=', 'fghces1.emp_hces1')
				->on('fgasigl0.numhces_asigl0', '=', 'fghces1.num_hces1')
				->on('fgasigl0.linhces_asigl0', '=', 'fghces1.lin_hces1');
		});
	}

	public function scopeLeftJoinFghces1Fgsub($query)
	{
		return $query->leftjoin('fgsub', function ($join) {
			$join->on('fgsub.emp_sub', '=', 'fghces1.emp_hces1')
				->on('fgsub.cod_sub', '=', 'fghces1.sub_hces1');
		});
	}

	public function scopeAddSelectTranslationsAttributes($query)
	{
		return $query
			->when(Config::get('app.locale') != Config::get('app.fallback_locale'), function ($query) {
				return $query
					//->selectRaw('NVL(FGHCES1_LANG.TITULO_HCES1_LANG, FGHCES1.TITULO_HCES1) AS TITULO_HCES1')
					->selectRaw('NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1) AS DESC_HCES1')
					->selectRaw('NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) AS DESCWEB_HCES1')
					->selectRaw('NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) AS WEBFRIEND_HCES1')
					->joinFghces1LangHces1();
			}, function ($query) {
				return $query->addSelect('DESC_HCES1', 'DESCWEB_HCES1', 'WEBFRIEND_HCES1');
			});
	}

	public function scopeJoinFghces1LangHces1($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		return $query->leftjoin('FGHCES1_LANG', "FGHCES1_LANG.EMP_HCES1_LANG = FGHCES1.EMP_HCES1 AND FGHCES1_LANG.NUM_HCES1_LANG = FGHCES1.NUM_HCES1 AND FGHCES1_LANG.LIN_HCES1_LANG = FGHCES1.LIN_HCES1 AND FGHCES1_LANG.LANG_HCES1_LANG = '" . $lang . "'");
	}
}
