<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Carbon\Carbon;
use Carbon\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
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

	/**
	 * Fechas de entrega estimada para Tauler
	 */
	private static function estimatedsDeliveryDates2022()
	{
		$dates = [
			[ 'application_date' => '2022-01-12', 'delivery_date' => '2022-01-29' ],
			[ 'application_date' => '2022-02-09', 'delivery_date' => '2022-02-26' ],
			[ 'application_date' => '2022-03-09', 'delivery_date' => '2022-03-26' ],
			[ 'application_date' => '2022-04-08', 'delivery_date' => '2022-04-25' ],
			[ 'application_date' => '2022-05-11', 'delivery_date' => '2022-05-28' ],
			[ 'application_date' => '2022-06-10', 'delivery_date' => '2022-06-27' ],
			[ 'application_date' => '2022-07-10', 'delivery_date' => '2022-07-30' ],
			[ 'application_date' => '2022-09-07', 'delivery_date' => '2022-09-24' ],
			[ 'application_date' => '2022-10-11', 'delivery_date' => '2022-10-29' ],
			[ 'application_date' => '2022-11-09', 'delivery_date' => '2022-11-26' ],
			[ 'application_date' => '2022-12-09', 'delivery_date' => '2022-12-27' ]
		];

		return collect($dates);
	}

	/**
	 * Tauler muestra una fecha de entrega estimada según la fecha de la subasta
	 */
	public static function getEstimatedDeliveryDate($auctionDate)
	{
		$deliveryDate = self::estimatedsDeliveryDates2022()->filter(function ($date) use ($auctionDate){
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

