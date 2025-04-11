<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class WebTranslateHeaders extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'web_translate_headers';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_headers';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'web_translate_headers.id_emp' => Config::get("app.main_emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('web_translate_headers.id_emp', Config::get("app.main_emp"));
		});
	}

	/**
	 * Scope a query to join with web_translate_key.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $empId
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeJoinTranslateKey(Builder $query): Builder
	{
		return $query->join('web_translate_key', function ($join) {
			$join->on('web_translate_headers.id_headers', '=', 'web_translate_key.id_headers_translate')
				->on('web_translate_key.id_emp', '=', 'web_translate_headers.id_emp');
		});
	}

	/**
	 * Scope a query to join with web_translate.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $language
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeJoinTranslate(Builder $query, string $language): Builder
	{
		$language = mb_strtoupper($language);
		return $query->join('web_translate', function ($join) use ($language) {
			$join->on('web_translate.id_key_translate', '=', 'web_translate_key.id_key')
				->on('web_translate.id_emp', '=', 'web_translate_headers.id_emp')
				->where('web_translate.lang', '=', $language);
		});
	}

	/**
	 * Scope a query to get all translations for a specific language and company.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $empId
	 * @param string $language
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeGetTranslations(Builder $query, string $language): Builder
	{
		return $query->select([
			'web_translate_headers.key_header',
			'web_translate_key.key_translate',
			'web_translate.web_translation'
		])
			->joinTranslateKey()
			->joinTranslate($language)
			->orderBy('key_header')
			->orderBy('key_translate');
	}
}
