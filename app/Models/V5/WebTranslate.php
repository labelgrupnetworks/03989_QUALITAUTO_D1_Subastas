<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

class WebTranslate extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'web_translate';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_translate';

	/**
	 * The format for the date attributes.
	 *
	 * @var string
	 */

	protected $dateFormat = 'Y-m-d H:i:s';

	const CREATED_AT = null;
	const UPDATED_AT = 'date_modificacion';

	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'id_emp' => Config::get("app.main_emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('id_emp', Config::get("app.main_emp"));
		});
	}

	/**
	 * Get the translate key that owns this translation.
	 */
	public function translateKey(): BelongsTo
	{
		return $this->belongsTo(WebTranslateKey::class, 'id_key_translate', 'id_key');
	}

	/**
	 * Scope a query to filter by language.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $language
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeLanguage($query, string $language)
	{
		return $query->where('lang', $language);
	}

	/**
	 * Scope a query to filter by company.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param int $empId
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeCompany($query, int $empId)
	{
		return $query->where('id_emp', $empId);
	}
}
