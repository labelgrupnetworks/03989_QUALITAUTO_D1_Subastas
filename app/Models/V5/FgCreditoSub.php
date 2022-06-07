<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Config;
use Illuminate\Support\Carbon;
class FgCreditoSub extends Model
{
    protected $table = 'FGCREDITOSUB';
    protected $primaryKey = 'ID_CREDITOSUB, EMP_CREDITOSUB';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_creditosub' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_creditosub', \Config::get("app.emp"));
        });
	}

	public static function getCurrentCredit($cli_creditosub, $codsub_creditosub){

		$currentCredit = self::where([
			['CLI_CREDITOSUB', $cli_creditosub],
			['SUB_CREDITOSUB', $codsub_creditosub]
		])->max('nuevo_creditosub');

		return $currentCredit;
	}

	public static function getCreditBySub($codsub_creditosub){

		//añadir join con cli para el nombre, el credito inicial y el maximo
		$clientsCredit = self::select('FXCLI.COD_CLI', 'FXCLI.RSOC_CLI', 'FXCLI.RIESMAX_CLI', 'FXCLI.RIES_CLI', DB::raw('max(FGCREDITOSUB.NUEVO_CREDITOSUB) as current_credit, max(FGCREDITOSUB.FECHA_CREDITOSUB) as fecha_credit'))
		->join('FXCLI', 'FXCLI.COD_CLI = FGCREDITOSUB.CLI_CREDITOSUB')
		->join('FXCLIWEB', 'FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI AND FXCLIWEB.EMP_CLIWEB = FGCREDITOSUB.EMP_CREDITOSUB AND FXCLI.COD_CLI = FXCLIWEB.COD_CLIWEB ')
		->where([
			['SUB_CREDITOSUB', $codsub_creditosub]
		])
		->groupBy('FXCLI.COD_CLI', 'FXCLI.RSOC_CLI', 'FXCLI.RIESMAX_CLI', 'FXCLI.RIES_CLI')
		->orderBy('fecha_credit', 'desc')
		->get();

		foreach ($clientsCredit as $clientCredit) {
			$clientCredit->riesmax_cli = ToolsServiceProvider::moneyFormat($clientCredit->riesmax_cli, ' €');
			$clientCredit->ries_cli = ToolsServiceProvider::moneyFormat($clientCredit->ries_cli, ' €');
			$clientCredit->current_credit = ToolsServiceProvider::moneyFormat($clientCredit->current_credit, ' €');
			$clientCredit->fecha_credit_forHumans = Carbon::createFromFormat('Y-m-d H:i:s', $clientCredit->fecha_credit)->locale('es')->diffForHumans();// ->format('H:i');
			$clientCredit->fecha_credit = Carbon::createFromFormat('Y-m-d H:i:s', $clientCredit->fecha_credit)->format('d/m H:i');
		}

		return $clientsCredit;
	}



}

