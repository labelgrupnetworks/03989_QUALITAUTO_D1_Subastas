<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgPujaMax extends Model
{
	protected $table = 'fgpujamax';
	protected $primaryKey = null;
	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_pujamax' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_pujamax', Config::get("app.emp"));
		});
	}

	/**
	 * Realiza la suma antes de intenear una puja u orden.
	 * La referencia se utiliza para evitar el lote actual por el que se realiza la puja.
	 * @return string
	 */
	public static function sumBidderAmountForAuction($subasta, $removeAwardLots)
	{
		return self::where('licit_pujamax', $subasta->licit)
			->where('sub_pujamax', $subasta->cod)
			->where('ref_pujamax', '!=', $subasta->ref)
			->when($removeAwardLots, function ($query) {
				$query->leftJoin('fgcsub', 'emp_csub = emp_pujamax and sub_csub = sub_pujamax and ref_csub = ref_pujamax')
					->whereNull('ref_csub');
			})
			->sum('imp_pujamax');
	}

	public static function getBiddersWithTotalBidAmountForAuction($codSub)
	{
		return self::select('licit_pujamax', 'sum(imp_pujamax) as imp_pujamax')
			->where('sub_pujamax', $codSub)
			->groupBy('licit_pujamax')
			->orderBy('licit_pujamax')
			->get();
	}

}
