<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $enviodef_prmgt
 * @property string $documentaciongemp_prmgt
 * @property string tiva_prmgt
 */
class FxPrmgt extends Model
{
	protected $table = 'fxprmgt';
	protected $primaryKey = false;
	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];

	#definimos la variable gemp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_prmgt' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_prmgt', Config::get("app.emp"));
		});
	}

	/**
	 * @return string
	 */
	public static function getDefaultEnvioParam(): ?string
	{
		return self::value('enviodef_prmgt');
	}

	/**
	 * @return string
	 */
	public static function getDefaultDocumentacionParam(): ?string
	{
		return self::value('documentaciongemp_prmgt');
	}

	/**
	 * @return bool
	 */
	public static function useGempInDocumentacion(): bool
	{
		return self::getDefaultDocumentacionParam() === 'S';
	}

	/**
	 * @return string
	 */
	public static function getDefaultTypeIvaParam(): ?string
	{
		return self::value('tiva_prmgt');
	}
}
