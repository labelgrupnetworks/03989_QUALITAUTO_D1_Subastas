<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;


class Web_Keywords_Search extends Model
{
	protected $table = 'Web_Keywords_Search';
	protected $primaryKey = 'id';

	public $timestamps = false;
	public $incrementing = false;
	//public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_keywords_search' => Config::get("app.main_emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_keywords_search', Config::get("app.main_emp"));
		});
	}

}
