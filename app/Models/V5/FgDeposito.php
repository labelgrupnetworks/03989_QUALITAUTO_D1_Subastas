<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use phpDocumentor\Reflection\Types\Boolean;

class FgDeposito extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FgDeposito';
	protected $primaryKey = 'COD_DEPOSITO';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	//P- Pendiente, V- Validado, E- Erroneo, B- Borrado
	const ESTADO_PENDIENTE = 'P';
	const ESTADO_VALIDO = 'V';
	const ESTADO_ERRONEO = 'E';
	const ESTADO_BORRADO = 'B';

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_deposito' => \Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_deposito', \Config::get("app.emp"));
		});
	}

	/**
	 * @param string $cli_deposito
	 * @param string $sub_deposito
	 * @param string $ref_deposito
	 * @return Boolean
	 */
	public function isValid($cli_deposito, $sub_deposito, $ref_deposito)
	{
		if(!$cli_deposito){
            return false;
        }

		$deposit = self::
			where([
				['CLI_DEPOSITO', $cli_deposito],
				['ESTADO_DEPOSITO', $this::ESTADO_VALIDO],
			])
			->where(function(Builder $query) use ($sub_deposito, $ref_deposito){

				$query->where(function (Builder $query) use ($sub_deposito){
					$query->where('SUB_DEPOSITO', $sub_deposito)
					->orWhereNull('SUB_DEPOSITO');
				})
				->where(function (Builder $query) use ($ref_deposito){
					$query->where('REF_DEPOSITO', $ref_deposito)
					->orWhereNull('REF_DEPOSITO');
				});
			})
			->first();

		if ($deposit) {
			return true;
		}
		return false;
	}


	public function getEstados(){
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


	static function getAllUsersWithValidDepositInAuctions($auctions)
	{
		return self::
			distinct()
			->whereIn('SUB_DEPOSITO', $auctions)
			->where('ESTADO_DEPOSITO', self::ESTADO_VALIDO)
			->get();
	}

}
