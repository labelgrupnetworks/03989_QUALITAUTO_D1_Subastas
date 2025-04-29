<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Config;

/**
 * @property string emp_ortsec0_lang
 * @property string sub_ortsec0_lang
 * @property int lin_ortsec0_lang
 * @property string lang_ortsec0_lang
 * @property string des_ortsec0_lang
 * @property string key_ortsec0_lang
 * @property int orden_ortsec0_lang
 * @property string meta_titulo_ortsec0_lang
 * @property string meta_description_ortsec0_lang
 * @property string meta_contenido_ortsec0_lang
 */
class FgOrtsec0_Lang extends Model
{
	protected $table = 'fgortsec0_lang';
	protected $primaryKey = 'lin_ortsec0_lang';

	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];

	const SUB_ORTSEC0_DEPARTAMENTOS = 'DEP';

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_ortsec0_lang' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected $casts = [
		'lin_ortsec0_lang' => 'int',
		'orden_ortsec0_lang' => 'int',
	];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_ortsec0_lang', Config::get("app.emp"));
		});
	}

	public function departmentRoutePage(): Attribute
	{
		return Attribute::make(
			get: fn () => route('department', ['text' => $this->key_ortsec0_lang])
		);
	}
}
