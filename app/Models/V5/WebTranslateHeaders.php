<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key_header',
        'description_header'
    ];

    /**
     * Scope a query to join with web_translate_key.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $empId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinTranslateKey(Builder $query, int $empId): Builder
    {
        return $query->join('web_translate_key', function($join) use ($empId) {
            $join->on('web_translate_headers.id_headers', '=', 'web_translate_key.id_headers_translate')
                 ->where('web_translate_key.id_emp', '=', $empId);
        });
    }

    /**
     * Scope a query to join with web_translate.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $empId
     * @param string $language
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinTranslate(Builder $query, int $empId, string $language = null): Builder
    {
        return $query->join('web_translate', function($join) use ($empId, $language) {
            $join->on('web_translate_key.id_key', '=', 'web_translate.id_key_translate')
                 ->where('web_translate.id_emp', '=', $empId)
                 ->when($language, function($query) use ($language) {
                     return $query->where('web_translate.lang', '=', $language);
                 });
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
    public function scopeGetTranslations(Builder $query, int $empId, string $language): Builder
    {
        return $query->select([
                'web_translate_headers.key_header',
                'web_translate_key.key_translate',
                'web_translate.web_translation'
            ])
            ->joinTranslateKey($empId)
            ->joinTranslate($empId, $language)
            ->orderBy('key_header')
            ->orderBy('key_translate');
    }
}
