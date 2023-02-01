<?php

namespace App\Http\Controllers\admin\bi;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Models\V5\FgLicit;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgSub;
use App\Models\V5\FxCliWeb;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;

class AdminBiReports extends Controller
{
	public function index()
	{
		#cargamos listado de reports y ningu nreport
		$formulario=array();
		return view('admin::pages.bi.reports.index', compact('formulario'));
	}

	#Cargamos un report y los filtros que puede tener
	public function report(Request $request)
	{
		$function = request("report");
		//Llamamos a la función seleccionada
		$report= $this->{$function."Report"}();
		$formulario = $this->formFilters($request);


		return view('admin::pages.bi.reports.index', compact('formulario','report'));

	}

	private function formFilters(Request $request)
	{
		$defaultYear = date('Y');

		$subastasFilters = FgSub::joinSessionSub()->orderBy('session_start', 'desc')->get();
		$auctions = $subastasFilters->pluck('cod_sub')->map(function ($codSub) {
			return $codSub;
		})->unique()->toArray();
		$years = $subastasFilters->pluck('session_start')->map(function ($date) {
			return date("Y", strtotime($date));
		})->unique()->toArray();

		$years = array_combine($years, $years);
		$months = array();
		for($i = 1;$i<=12;$i++){
			$months[$i] = date("M", strtotime("1-".$i."-".date("Y")));
		}

		$auctionsTypes = (new FgSub())->getTipoSubTypes();

		return (object)[
			'years' => FormLib::Select("years[]", 0, request("years",[date("Y")]), $years, 'multiple', '', false),
			'months' => FormLib::Select("months[]", 0, "", $months, 'multiple', '', false),
			'tipo_subs' => FormLib::Select("tipo_subs[]", 0, "", $auctionsTypes, 'multiple', '', false),
			'auctions' => FormLib::Select("auctions[]", 0, "", $auctions, 'multiple', '', false),
			//'lin_ortsec0' => FormLib::Select("lin_ortsec0[]", 0, "", [], 'multiple', '', false),
		];
	}



	private function typeBidsReport(){
		$name = trans("admin-app.reportsBi.reports.type_bids");

		$where = $this->allFilters("fecha_csub");

		$sql="select tipopuja ,sum(imp_asigl1) suma,count(*) cuantos, '".trans("admin-app.reportsBi.titlesReport.num_bids")."' as title    from (
			select case when pujrep_asigl1 = 'A' then nvl((select max(a2.pujrep_asigl1) from fgasigl1 a2 where a2.emp_Asigl1 = emp_csub and a2.sub_asigl1 = sub_csub and a2.ref_asigl1 = ref_csub and a2.licit_asigl1 != fgcsub.licit_csub and fgcsub.himp_csub = a2.imp_asigl1),'A')
			else pujrep_asigl1 end as tipopuja
			,imp_asigl1,fgcsub.sub_csub,ref_asigl1
			from fgcsub
			join fgasigl1 a1 on emp_Asigl1 = emp_csub and sub_asigl1 = sub_csub and ref_asigl1 = ref_csub and licit_asigl1 = fgcsub.licit_csub and fgcsub.himp_csub = imp_asigl1
			where emp_csub = :emp
			$where
			order by 1
			)group by tipopuja";

		$params = array(
			"emp" => \Config::get("app.emp"),

		);

		$result = \DB::select($sql, $params);

		$charts[] = $this->generateData(["bar","doughnut"],$result, "title", 'tipopuja','suma' );
		/*
		$labels =[];
		foreach ($result as $record){
			$labels[$record->tipopuja]= $record->tipopuja;
		}
		$charts[] = new Chart(["bar","doughnut"], $labels, "tipopuja","suma");
		$charts[] = new Chart(["bar"], $labels, "tipopuja","cuantos");



		foreach ($charts as $key=> $chart) {

			$charts[$key]->insertData($result, trans("admin-app.reportsBi.titlesReport.num_bids"));
		}
		*/
		return compact('name','sql', 'params', 'charts');
	}

	private function salesSecsReport(){
		$name = trans("admin-app.reportsBi.reports.type_bids");

		$where = $this->allFilters("fecha_csub");


		$sql="select lin_ortsec0, max(des_ortsec0) des_ortsec0,fxcli.codpais_cli,Max(pais_cli) pais /* ,fxcli.pob_cli,fxcli.pro_cli */,sum(base_csub) comisiones, sum(himp_csub) adj

		from fgcsub
		join fgasigl1 a1 on emp_Asigl1 = emp_csub and sub_asigl1 = sub_csub and ref_asigl1 = ref_csub and licit_asigl1 = fgcsub.licit_csub and fgcsub.himp_csub = imp_asigl1
		join fgasigl0 on emp_asigl0 = emp_csub and sub_asigl0 = sub_csub and ref_asigl0 = ref_csub
		join fghces1 on emp_hces1 = emp_csub and num_hces1 = numhces_asigl0 and lin_hces1 = linhces_asigl0
		join fxsec on gemp_sec ='01' and  cod_sec = sec_hces1

		join fgortsec1 on emp_ortsec1 = emp_csub and sub_ortsec1=0 and sec_ortsec1 = sec_hces1

		join fgortsec0 on emp_ortsec0 = emp_ortsec1 and sub_ortsec0=sub_ortsec1  and lin_ortsec0 = lin_ortsec1

		join fglicit on emp_licit = emp_csub and sub_licit = sub_csub and cod_licit = licit_csub
		join fxcli on gemp_cli = '01' and cod_cli = cli_licit
		where emp_csub = '001' and sub_asigl1 in ('416') and codpais_cli != 'ES'
		$where
		group by lin_ortsec0,fxcli.codpais_cli  ";
		$params = array(

		);




		$result = \DB::select($sql, $params);
/*
		$paises = array();
		$labels = array();
		#generar array con paises y array de label
		foreach ($result as $record){
			$labels[$record->des_ortsec0]= $record->des_ortsec0;
			if(empty($paises[$record->pais])){
				$paises[$record->pais] = array();
			}
			$paises[$record->pais][$record->des_ortsec0] = $record;

		}
		#rellenar a 0 los valores que faltan
		foreach ($paises as $keyPais => $pais){
			foreach ($labels as $label){
				if(empty($paises[$keyPais][$label])){
					$paises[$keyPais][$label] = new \StdClass();
					$paises[$keyPais][$label]->adj = 0;
				}
			}
			#es necesario ordenar para que los bvalores correspondan con las etiquetas
			ksort($paises[$keyPais]);
		}
		ksort($labels);
		$charts[] = new Chart(["radar"], $labels, "des_ortsec0","adj");

		foreach ($charts as $key=> $chart) {
			foreach($paises as $namePais => $dataPais){
				$charts[$key]->insertData($dataPais, $namePais);
			}

		}
*/
	$charts[] = $this->generateData(["radar"],$result, "pais", 'des_ortsec0','adj' );
	$charts[] = $this->generateData(["radar"],$result,  'des_ortsec0', "pais",'adj' );
		return compact('name', 'charts');
	}

	#filters
	private function allFilters($dateField){
		$where = "";
		$where .= $this->dateFilter($dateField);
		$where .= $this->monthFilter($dateField);
		return $where;
	}
	
	private function dateFilter($field){


		#cargamos los años o el año actual
		$years = request("years",[date("Y")]);
		$where =" and (";
		$or="";
		#filtro por años
		foreach ($years as $year){
			$where.=" $or ($field >= '$year-01-01' and $field <= '$year-12-31')";
			$or="or";
		}
		$where .=") ";

		return $where;
	}

	private function monthFilter($field){

		$where ="";
		#cargamos los meses consultado o ninguno
		$months = request("months",[]);

		if(count($months) > 0){
				$where =" and (";
				$or="";
				#filtro por años
				foreach ($months as $year){
					$where.=" $or month($field) = $year";
					$or="or";
				}
				$where .=") ";
		}
		return $where;
	}

	#fin filters


	private function generateData($types ,$result, $varTitle, $varLabel, $varData )
	{

		$titles = array();
		$labels = array();
		#generar array con paises y array de label
		foreach ($result as $record){
			$labels[$record->{$varLabel}]= $record->{$varLabel};
			if(empty($titles[$record->{$varTitle}])){
				$titles[$record->{$varTitle}] = array();
			}
			$titles[$record->{$varTitle}][$record->{$varLabel}] = $record->{$varData};

		}
		#rellenar a 0 los valores que faltan
		foreach ($titles as $key => $title){
			foreach ($labels as $label){
				if(empty($titles[$key][$label])){
					$titles[$key][$label] = 0;
				}
			}
			#es necesario ordenar para que los bvalores correspondan con las etiquetas
			ksort($titles[$key]);
		}
		ksort($labels);

		$chart = new Chart($types, $labels, $varLabel,$varData);


			foreach($titles as $key => $labels){
				$chart->insertData($labels, $key);
			}
			return $chart;

	}

}

class Chart{
	public $types =[];

	public $varLabel ="";
	public $varData ="";
	public $datasets =[];
	public $labels =[];
	/*

	public $data =[];
*/
	public function __construct($types, $labels,  $varLabel, $varData)
	{
		$this->types = $types;

$this->varLabel = $varLabel;
		$this->varData = $varData;
		$this->labels= $labels;

	}

	public function insertData($records, $title){
		$dataset = ["title"=>"",
					"data" => []];

		foreach ( $records as $record){
		//	echo $this->varLabel."  ".$record->{$this->varLabel}." <br>";

			$dataset["title"] =$title;
			$dataset["data"][] = $record; /*  $record->{$this->varData}; */
		}
		$this->datasets[] = $dataset;
	}
}
