<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class Web_Scheduled_Task extends Model
{
	protected $table = 'web_scheduled_tasks';
	protected $primaryKey = 'id_scheduled_tasks';

	public $timestamps = false;

	protected $fillable = [
		'emp_scheduled_tasks',
		'task_name_scheduled_tasks',
		'command_scheduled_tasks',
		'cron_expression_scheduled_tasks',
		'is_active_scheduled_tasks'
	];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_scheduled_tasks' => Config::get("app.emp"),
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_scheduled_tasks', Config::get("app.emp"));
		});
	}

	public function scopeWhereActive($query)
	{
		return $query->where('is_active_scheduled_tasks', 1);
	}
}
