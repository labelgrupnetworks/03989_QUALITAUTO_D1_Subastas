<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Carbon\Carbon;
use Carbon\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FxDvc0Seg extends Model
{
    protected $table = 'FXDVC0SEG';
    protected $primaryKey = 'EMP_DVC0SEG, ANUM_DVC0SEG, NUM_DVC0SEG, LIN_DVC0SEG';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];

	#fuerza el mostrar este atributo recuperado de la funcion
	protected $appends = ['long_description'];

	protected $casts = [
		'fecha_dvc0seg' => 'datetime:d/m/Y'
	];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_dvc0seg' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_dvc0seg', \Config::get("app.emp"));
        });
	}

	public function scopeJoinFxEstadosSeg($query){
        return $query->join("FXESTADOSSEG", "IDSEG_DVC0SEG = ID_ESTADOSSEG");
    }

	public function getLongDescriptionAttribute()
	{
		return trans(Config::get('app.theme'). "-app.user_panel.estado_seg_$this->idseg_dvc0seg");
	}

	public function getSeguimientoEnvío($anum, $num)
	{
		return self::select('des_estadosseg', 'fecha_dvc0seg', 'lin_dvc0seg', 'idseg_dvc0seg')
		->joinFxEstadosSeg()
			->where([
				['anum_dvc0seg', $anum],
				['num_dvc0seg', $num]
			])
			->orderBy('lin_dvc0seg', 'desc')
			->get();

		/**
		 * SELECT ANUM_DVC0 , NUM_DVC0,DES_ESTADOSSEG, LIN_DVC0SEG, FECHA_DVC0SEG
		 * FROM FXDVC0
		 * LEFT JOIN FXDVC0SEG ON EMP_DVC0SEG = EMP_DVC0 AND ANUM_DVC0SEG = ANUM_DVC0 AND NUM_DVC0SEG = NUM_DVC0
		 * LEFT JOIN FXESTADOSSEG ON IDSEG_DVC0SEG = ID_ESTADOSSEG
		 */
	}

	public static function getFollowUpByBills($billsIds)
	{
		if((is_array($billsIds) && !$billsIds) || (is_collection($billsIds) && $billsIds->isEmpty())){
			return collect();
		}

		return self::select('anum_dvc0seg','num_dvc0seg', 'des_estadosseg', 'fecha_dvc0seg', 'lin_dvc0seg', 'idseg_dvc0seg')
			->joinFxEstadosSeg()
			->whereBills($billsIds)
			->whereOnlyMaxLine()
			->orderBy('lin_dvc0seg', 'desc')
			->get();
	}

	 function scopeWhereOnlyMaxLine($query)
	{
		$query->where('lin_dvc0seg', function($query){
			$query->selectRaw('max(FXDVC0SEG2.lin_dvc0seg)')
				->from('FXDVC0SEG as FXDVC0SEG2')
				->whereColumn('FXDVC0SEG.anum_dvc0seg', 'FXDVC0SEG2.anum_dvc0seg')
				->whereColumn('FXDVC0SEG.num_dvc0seg', 'FXDVC0SEG2.num_dvc0seg')
				->whereColumn('FXDVC0SEG.emp_dvc0seg', 'FXDVC0SEG2.emp_dvc0seg');
		});
	}

	public function scopeWhereBills($query, $billsIds)
	{
		return $query->where(function($query) use ($billsIds){
			foreach ($billsIds as $bill) {
				$query->orWhere([
					['anum_dvc0seg', $bill['afra']],
					['num_dvc0seg', $bill['nfra']]
				]);
			}
			return $query;
		});
	}

	/**
	 * Fechas de entrega estimada para Tauler
	 */
	private static function estimatedsDeliveryDates()
	{
		$dates = [
			[ 'application_date' => '2024-12-10', 'delivery_date' => '2024-12-27' ],
			[ 'application_date' => '2025-01-21', 'delivery_date' => '2025-01-31' ],
			[ 'application_date' => '2025-02-13', 'delivery_date' => '2025-02-23' ],
			[ 'application_date' => '2025-03-13', 'delivery_date' => '2025-03-23' ],
			[ 'application_date' => '2025-04-10', 'delivery_date' => '2025-04-20' ],
			[ 'application_date' => '2025-05-13', 'delivery_date' => '2025-05-23' ],
			[ 'application_date' => '2025-06-17', 'delivery_date' => '2025-06-27' ],
			[ 'application_date' => '2025-07-22', 'delivery_date' => '2025-07-27' ],
			[ 'application_date' => '2025-09-16', 'delivery_date' => '2025-09-26' ],
			[ 'application_date' => '2025-10-16', 'delivery_date' => '2025-10-26' ],
			[ 'application_date' => '2025-11-20', 'delivery_date' => '2025-11-30' ],
			[ 'application_date' => '2025-12-16', 'delivery_date' => '2025-12-26' ],
		];

		return collect($dates);
	}

	/**
	 * Tauler muestra una fecha de entrega estimada según la fecha de la subasta
	 */
	public static function getEstimatedDeliveryDate($auctionDate)
	{
		$deliveryDate = self::estimatedsDeliveryDates()->filter(function ($date) use ($auctionDate){
			return $date['application_date'] > $auctionDate;
		})->first();

		if(!$deliveryDate){
			return null;
		}

		$carbonDate = Carbon::parse($deliveryDate['delivery_date']);

		//8 de julio de 2022
		if(config('app.locale') == 'es'){
			$factory = new Factory([
				'locale' => 'es_ES',
				'timezone' => 'Europe/Madrid',
			]);

			return $factory->make($carbonDate)->isoFormat('LL');
		}

		//July 08th, 2022
		return $carbonDate->format('F jS, Y');
	}

}

