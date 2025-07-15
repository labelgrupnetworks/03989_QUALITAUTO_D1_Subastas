<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Http\Controllers\MailController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Boolean;

class FgDeposito extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FgDeposito';
	protected $primaryKey = 'cod_deposito';
	protected $dateFormat = 'Y-m-d H:i:s';

	//public $timestamps = false;
	const CREATED_AT = null;
	const UPDATED_AT = 'fecha_deposito';

	public $incrementing = true;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	//P- Pendiente, V- Validado, E- Erroneo, B- Borrado
	const ESTADO_PENDIENTE = 'P';
	const ESTADO_VALIDO = 'V';
	const ESTADO_ERRONEO = 'E';
	const ESTADO_BORRADO = 'B';

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_deposito' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_deposito', Config::get("app.emp"));
		});

		static::saved(function ($fgDeposito) {

			$sendNotification = Config::get('app.send_valid_deposit_notification', false) && !$fgDeposito->isSended && $fgDeposito->isValidState;
			if ($sendNotification) {
				self::sendDepositNotification($fgDeposito);
			}
		});
	}

	/**
	 * @param string $cli_deposito
	 * @param string $sub_deposito
	 * @param string $ref_deposito
	 * @param string|null $representado_deposito
	 * @return Boolean
	 */
	public function isValid($cli_deposito, $sub_deposito, $ref_deposito, $representado_deposito = null)
	{
		if (!$cli_deposito) {
			return false;
		}

		$deposit = self::query()
			->where('CLI_DEPOSITO', $cli_deposito)
			->when($representado_deposito, function ($query) use ($representado_deposito) {
				$query->where('REPRESENTADO_DEPOSITO', $representado_deposito);
			}, function ($query) {
				$query->where(function (Builder $query) {
					$query->whereNull('REPRESENTADO_DEPOSITO')
						->orWhere('REPRESENTADO_DEPOSITO', 0);
				});
			})
			->whereValidConditions($sub_deposito, $ref_deposito)
			->first();

		return !empty($deposit);
	}

	public function getAllClientsWithValidDepositInLotQuery($sub_deposito, $ref_deposito)
	{
		return self::distinct()->whereValidConditions($sub_deposito, $ref_deposito);
	}

	public function scopeWhereValidConditions($query, $sub_deposito, $ref_deposito)
	{
		return $query
			->where('ESTADO_DEPOSITO', self::ESTADO_VALIDO)
			->where(function (Builder $query) use ($sub_deposito, $ref_deposito) {

				$query->where(function (Builder $query) use ($sub_deposito) {
					$query->where('SUB_DEPOSITO', $sub_deposito)
						->orWhereNull('SUB_DEPOSITO');
				})
					->where(function (Builder $query) use ($ref_deposito) {
						$query->where('REF_DEPOSITO', $ref_deposito)
							->orWhereNull('REF_DEPOSITO');
					});
			});
	}

	public static function getEstados()
	{
		return [
			self::ESTADO_PENDIENTE => trans("admin-app.fields.estado_deposito_p"),
			self::ESTADO_VALIDO => trans("admin-app.fields.estado_deposito_v"),
			self::ESTADO_ERRONEO => trans("admin-app.fields.estado_deposito_e"),
			self::ESTADO_BORRADO => trans("admin-app.fields.estado_deposito_b")
		];
	}

	public function getEstadoAttribute()
	{
		return $this->getEstados()[$this->estado_deposito];
	}

	public function getIsSendedAttribute()
	{
		if (!$this->enviado_deposito) {
			return false;
		}

		return $this->enviado_deposito == 'S';
	}

	public function getIsValidStateAttribute()
	{
		return $this->estado_deposito == self::ESTADO_VALIDO;
	}

	static function getAllUsersWithValidDepositInAuctions($auctions)
	{
		return self::distinct()
			->whereIn('SUB_DEPOSITO', $auctions)
			->where('ESTADO_DEPOSITO', self::ESTADO_VALIDO)
			->get();
	}

	public function scopeJoinCli($query)
	{
		return $query->join("FXCLI", "GEMP_CLI = '" . Config::get("app.gemp") . "' AND COD_CLI = CLI_DEPOSITO");
	}

	public function scopeJoinAsigl0($query)
	{
		return $query->join("FGASIGL0", "EMP_ASIGL0 = EMP_DEPOSITO AND SUB_ASIGL0 = SUB_DEPOSITO AND REF_ASIGL0 = REF_DEPOSITO ");
	}


	#esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
	public function scopeWhereUpdateApi($query, $item)
	{
		return $query->where('sub_deposito', $item["sub_deposito"])->where('ref_deposito', $item["ref_deposito"])->where('cli_deposito', $item["cli_deposito"]);
	}

	//relation one to one with fgrepresntados
	public function represented()
	{
		return $this->hasOne(FgRepresentados::class, 'id', 'representado_deposito');
	}

	private static function sendDepositNotification($fgDeposito)
	{
		try {
			(new MailController())->sendValidDepositNotification($fgDeposito->cli_deposito, $fgDeposito->sub_deposito, $fgDeposito->ref_deposito);
		} catch (\Throwable $th) {
			Log::error('Error al enviar mensaje de deposito valido', ['error' => $th->getMessage()]);
			return false;
		}

		$fgDeposito->enviado_deposito = 'S';
		$fgDeposito->fechaenvio_deposito = date('Y-m-d H:i:s');
		$fgDeposito->usuarioenvio_deposito = 'WEB';
		$fgDeposito->save();

		return true;
	}
}
