<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * Modelo para la tabla fgcsub0
 * @package App\Models\V5
 * @property string $emp_csub0
 * @property string $apre_csub0
 * @property int $npre_csub0
 * @property string $fecha_csub0
 * @property string $usr_csub0
 * @property string $cli_csub0
 * @property string $idtrans_csub0
 * @property string $estado_csub0
 * @property float $imp_csub0
 * @property float $impgas_csub0
 * @property float $impcob_csub0
 * @property float $tax_csub0
 * @property string $extrainf_csub0
 * @property string $tk_csub0
 * @property float $gastos_csub0
 * @property float $taxg_csub0
 * @property float $exp_csub0
 * @property float $impdiv_csub0
 * @property float $impextra_csub0
 * @property array $extra_info_format
 * @property array $info_format
 */
class FgCsub0 extends Model
{
	protected $table = 'fgcsub0';
	protected $primaryKey = false;

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_csub0' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_csub0', Config::get("app.emp"));
		});
	}

	#HACE JOIN CON LA PUJA GANADORA
	public function scopeJoinCsub($query)
	{
		return $query->join("FGCSUB", "EMP_CSUB = EMP_CSUB0  AND APRE_CSUB = APRE_CSUB0 AND NPRE_CSUB = NPRE_CSUB0  ");
	}

	#NECESITA EL JOIN CON CSUB PARA FUBNCIONAR
	public function scopeJoinSub($query)
	{
		return $query->join("FGSUB", "EMP_SUB = EMP_CSUB0  AND COD_SUB = SUB_CSUB ");
	}

	public function scopeJoinCli($query)
	{
		return $query->join("FXCLI", "GEMP_CLI = '" . Config::get("app.gemp") . "' AND COD_CLI = CLI_CSUB0");
	}

	#NECESITA EL JOIN CON CSUB PARA FUBNCIONAR
	public function scopeJoinAsigl0($query)
	{
		return $query->join("FGASIGL0", "EMP_ASIGL0 = EMP_CSUB0 AND SUB_ASIGL0 = SUB_CSUB AND REF_ASIGL0 = REF_CSUB ");
	}
	#NECESITA EL JOIN DE ASIGL0
	public function scopeJoinHces1($query)
	{
		return $query->join("FGHCES1", "EMP_HCES1 = EMP_CSUB0 AND NUM_HCES1 = NUMHCES_ASIGL0 AND LIN_HCES1 = LINHCES_ASIGL0 ");
	}

	#NECESITA EL JOIN DE HCES1
	public function scopeJoinAlm($query)
	{
		return $query->leftjoin("FXALM", "EMP_ALM = EMP_CSUB0 AND COD_ALM = ALM_HCES1 ");
	}

	public static function getCsub($apre, $npre): ?FgCsub0
	{
		return self::where('apre_csub0', $apre)
			->where('npre_csub0', $npre)
			->first();
	}

	public static function getCsubByIdtrans($idtrans): ?FgCsub0
	{
		return self::where('idtrans_csub0', $idtrans)
			->first();
	}

	public function getExtraInfoFormatAttribute()
	{
		$extraInfo = $this->extrainf_csub0;
		return json_decode($extraInfo, true);
	}

	public function getInfoFormatAttribute()
	{
		$info = data_get($this->extra_info_format, '*.*.inf', []);
		return collect($info)->first() ?? [];
	}

}
