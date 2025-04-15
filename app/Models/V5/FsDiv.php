<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;


/**
 * @property string $cod_div código de la divisa
 * @property string $des_div descripción de la divisa
 * @property string $impd_div ratio conversión de la divisa
 * @property string $impm_div con valor 1 divisa primaria
 * @property string $comi_div comision de la divisa
 * @property string $divori_div divisa origen
 * @property string $simbol_div símbolo de la divisa
 * @property string $symbolhtml_div símbolo de la divisa en codificación HTML
 * @property string $pos_div posición del símbolo, D derecha, I izquierda
 */
class FsDiv extends Model
{
	protected $table = 'fsdiv';
	protected $primaryKey = 'cod_div';
	protected $dateFormat = 'U';
	protected $attributes = false;

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];


	public function scopeSelectBasicDiv($query)
	{
		return  $query->select("cod_div", "des_div");
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Collection<int, static>
	 */
	public static function getDivisas() : \Illuminate\Database\Eloquent\Collection
	{
		return self::select(['cod_div','des_div','impd_div','symbolhtml_div'])
			->get();
	}
}
