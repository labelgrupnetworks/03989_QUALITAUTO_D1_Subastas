<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Enums\User\UserKycStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * Kyc
 * Modelo para la tabla de Kyc
 * @property int $id
 * @property string $gemp
 * @property string $cli
 * @property string $kyc
 * @property UserKycStatus $estado
 * @property date $fecha
 * @property date $fecha_mod
 * @property int $enviado
 */
class Kyc extends Model
{
	protected $table = 'kyc';
	protected $primaryKey = 'id';
	public $incrementing = true;

	protected $attributes = false;

	protected $guarded = [];
	protected $dateFormat = 'Y-m-d H:i:s';

	const CREATED_AT = 'fecha';
	const UPDATED_AT = 'fecha_mod';

	protected $casts = [
		'estado' => UserKycStatus::class,
		'fecha' => 'datetime:Y-m-d H:i:s',
	];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'gemp' => Config::get("app.gemp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('gemp', function (Builder $builder) {
			$builder->where('gemp', Config::get("app.gemp"));
		});
	}

	public static function getByAuthUuid(string $uuid): ?Kyc
	{
		return self::where('kyc', $uuid)->first();
	}

}
