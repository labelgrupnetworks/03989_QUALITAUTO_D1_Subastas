<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class Fx_Newsletter extends Model
{
	protected $table = 'fx_newsletter';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $guarded = [];

	const GENERAL = 1;

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'gemp_newsletter' => Config::get("app.gemp"),
			'emp_newsletter' => Config::get("app.emp"),
			'id' => self::withoutGlobalScope('gemp')->max('id') + 1
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('gemp', function (Builder $builder) {
			$builder->where('gemp_newsletter', Config::get("app.gemp"));
			$builder->where('emp_newsletter', Config::get("app.emp"));
		});
	}

	public function scopeWhereLang($query, $lang = null)
	{
		$lang = $lang ?? config('app.locale');
		return $query->where('lower(lang_newsletter)', mb_strtolower($lang));
	}

	public function languages()
	{
		return $this->hasMany(Fx_Newsletter::class, 'id_newsletter', 'id_newsletter')->where('lang_newsletter', '!=', mb_strtoupper(config('app.locale')));
	}

	public function suscriptors()
	{
		return $this->hasMany(Fx_Newsletter_Suscription::class, 'id_newsletter', 'id_newsletter');
	}
}
