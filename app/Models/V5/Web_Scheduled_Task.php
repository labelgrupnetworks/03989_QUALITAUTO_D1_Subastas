<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class Web_Scheduled_Task extends Model
{
	protected $table = 'web_scheduled_tasks';
	protected $primaryKey = 'id';

	public $timestamps = false;

	protected $fillable = [
		'emp',
		'task_name',
		'command',
		'cron_expression',
		'is_active'
	];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp' => Config::get("app.emp"),
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

	public function scopeWhereActive($query)
	{
		return $query->where('is_active', 1);
	}
}
