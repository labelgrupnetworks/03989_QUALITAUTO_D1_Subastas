<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

class WebTranslateKey extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'web_translate_key';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_key';

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
     * Get the header that owns the translation key.
     */
    public function header(): BelongsTo
    {
        return $this->belongsTo(WebTranslateHeaders::class, 'id_headers_translate', 'id_headers');
    }

    /**
     * Get the translations for this key.
     */
    public function translations()
    {
        return $this->hasMany(WebTranslate::class, 'id_key_translate', 'id_key');
    }
}
