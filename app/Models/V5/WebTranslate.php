<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_key_translate',
        'id_emp',
        'lang',
        'web_translation'
    ];

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
