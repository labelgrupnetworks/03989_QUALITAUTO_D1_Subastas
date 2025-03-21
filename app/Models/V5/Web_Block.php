<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class Web_Block extends Model
{
	protected $table = 'web_block';
	protected $primaryKey = 'id_web_block';

	public $timestamps = false;
	public $incrementing = false;
	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'id_emp' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('id_emp', Config::get("app.emp"));
		});
	}
}
