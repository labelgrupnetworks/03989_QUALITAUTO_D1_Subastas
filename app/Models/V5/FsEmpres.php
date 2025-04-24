<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * @property string cod_emp
 * @property string gemp_emp
 * @property string nom_emp
 * @property string dir_emp
 * @property string cp_emp
 * @property string pob_emp
 * @property string pais_emp
 * @property string tel1_emp
 * @property string email_emp
 */
class FsEmpres extends Model
{
	protected $table = 'fsempres';
	protected $primaryKey = 'cod_emp';

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'cod_emp' => Config::get("app.emp"),
			'gemp_emp' => Config::get("app.gemp"),
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('cod_emp', Config::get("app.emp"));
			$builder->where('gemp_emp', Config::get("app.gemp"));
		});
	}
}
