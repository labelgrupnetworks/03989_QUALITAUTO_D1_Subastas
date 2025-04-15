<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cod_iva
 * @property string $des_iva
 * @property string $dfec_iva
 * @property string $hfec_iva
 * @property string $iva_iva
 * @property string $lin_iva
 * @property string $pais_iva
 * @property string $re_iva
 */
class FsIva extends Model
{
	protected $table = 'fsiva';
	protected $primaryKey = 'cod_iva';
	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	protected $attributes = [];


	/**
	 * @return self
	 */
	public static function getIvaByDefaultType() :self
	{
		$defaultType = FxPrmgt::getDefaultTypeIvaParam();
		return self::where('cod_iva', $defaultType)->first() ?? new self();
	}
}
