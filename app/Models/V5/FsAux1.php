<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Support\Localization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * Class FsAux1
 *
 * @package App\Models\V5
 *
 * @property string $cod1_aux1 Document type code
 * @property string $des_aux1 Document type description
 * @property string $emp_aux1 Company code
 * @property string $idioma_aux1 Language code
 * @property string $baja_aux1 Deletion flag (S/N)
 * @property string $cod2_aux1 Secondary code (optional)
 */
class FsAux1 extends Model
{
    // Variables propias de Eloquent para poder usar el ORM de forma correcta.
    protected $table = 'fsaux1';
    protected $primaryKey = 'cod1_aux1';
    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];

    public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_aux1' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_aux1', Config::get("app.emp"));
		});
	}

    /**
     * Get document types by language and company
     *
     * @param string $company Company code
     * @return \Illuminate\Support\Collection
     */
    public static function getDocumentTypes()
    {
		$locale = Localization::getUpperLocale();
        return self::select('cod1_aux1', 'des_aux1')
            ->where([
                ['idioma_aux1', $locale],
                ['baja_aux1', 'N']
            ])
            ->pluck('des_aux1', 'cod1_aux1');
    }
}
