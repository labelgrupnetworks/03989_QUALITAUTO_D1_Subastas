<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class Web_Config extends Model
{
	protected $table = 'web_config';
	protected $primaryKey = 'id_web_config';
	public $timestamps = false;
	public $incrementing = false;
	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp', Config::get("app.emp"));
		});
	}

	/**
	 * Función encargada de codificar/encriptar un password
	 *
	 * @var string $password - Password del usuario
	 * @var string $emp - Empresa
	 * @return string|false - Devuelve el password encriptado o false si no se encuentra la configuración
	 */
	static function password_encrypt($password, $emp)
	{
		$res = 	WEB_CONFIG::select("VALUE")
			->where("KEY", "password_MD5")
			->where("EMP", $emp)->first();
		if (empty($res)) {
			return false;
		}
		$v = trim(md5($res->value . $password));

		return $v;
	}
}
