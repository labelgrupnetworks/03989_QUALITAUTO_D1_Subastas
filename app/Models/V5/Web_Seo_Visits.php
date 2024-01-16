<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;


class Web_Seo_Visits extends Model
{
	protected $table = 'Web_Seo_Visits';
	protected $primaryKey = 'id_seo_visits';

	public $timestamps = false;
	public $incrementing = false;
	//public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_seo_visits' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_seo_visits', Config::get("app.emp"));
		});
	}



}
