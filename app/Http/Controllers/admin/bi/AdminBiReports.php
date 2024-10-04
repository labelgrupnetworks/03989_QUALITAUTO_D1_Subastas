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
use App\Exports\AwardsExport;
use stdClass;

class AdminBiReports extends Controller
{

	var $params =[];
	public function index()
	{

		#cargamos listado de reports y ningu nreport
		$formulario=array();
		return view('admin::pages.bi.reports.index', compact('formulario'));
	}



	#Cargamos un report y los filtros que puede tener
	public function report(Request $request)
	{
		$this->params["emp"] = \Config::get("app.emp");
		$function = request("report");

		//Llamamos a la función seleccionada
		$report= $this->{$function."Report"}();

		if(request("export")=="excel"){
			return $report;
		}

		$formulario = $this->formFilters($request);


		return view('admin::pages.bi.reports.index', compact('formulario','report'));

	}

	private function formFilters(Request $request)
	{
		$auctionsRequest = request("auctions",[]);

		$subastasFilters = FgSub::joinSessionSub()->where("cod_sub","!=","0")->where("subc_sub","!=","A")->where("subc_sub","!=","N")->orderBy('session_start', 'desc')->get();
		#filtramos las subastas y los tipos de subastas por el año

		$auctionsByYear = $subastasFilters->map(function ($auction,$key) {

			#si hay años seleccionado que la subasta esté en esos años
			if( count(request("years",[]))==0 ||    in_array(date("Y", strtotime($auction->session_start)), request("years",[]))){
				return $auction;
			}

		});

		$auctionsTypes_tmp = (new FgSub())->getTipoSubTypes();
		foreach($auctionsByYear as $auction){

			if(!empty($auction)){

				$auctions[$auction->cod_sub] =$auction->description;
				#si no hay subatas seleccionada o si esta en la lista de las seleccionadas
				if( (count($auctionsRequest )==0 || in_array($auction->cod_sub,$auctionsRequest)) && !empty($auctionsTypes_tmp[$auction->tipo_sub])){
					$auctionsTypes[$auction->tipo_sub] = $auctionsTypes_tmp[$auction->tipo_sub];
				}
			}

		}


		$years = $subastasFilters->pluck('session_start')->map(function ($date) {
			return date("Y", strtotime($date));
		})->unique()->toArray();

		$years = array_combine($years, $years);
		$months = array();
		for($i = 1;$i<=12;$i++){
			$months[$i] = date("M", strtotime("1-".$i."-".date("Y")));
		}



		return (object)[

			'years' => FormLib::Select("years[]", 0, request("years"), $years, 'multiple', '', false),
			'months' => FormLib::Select("months[]", 0,  request("months"), $months, 'multiple', '', false),
			'auctions' => FormLib::Select("auctions[]", 0,  request("auctions"),$auctions, 'multiple', '', false),
			'tipo_subs' => FormLib::Select("tipo_subs[]", 0, request("tipo_subs"), $auctionsTypes,'multiple', '',false),

		];
	}



	#Adjudicaciones por tipo de pujas
	private function typeBidsReport(){
		$name = trans("admin-app.reportsBi.reports.type_bids");


		$where = $this->allFilters("fecha_csub","tipo_sub", "cod_sub");

		$sql="select tipopuja ,sum(imp_asigl1) suma,count(*) cuantos  from (
			select case when pujrep_asigl1 = 'A' then nvl((select max(a2.pujrep_asigl1) from fgasigl1 a2 where a2.emp_Asigl1 = emp_csub and a2.sub_asigl1 = sub_csub and a2.ref_asigl1 = ref_csub and a2.licit_asigl1 != fgcsub.licit_csub and fgcsub.himp_csub = a2.imp_asigl1),'A')
			else pujrep_asigl1 end as tipopuja
			,imp_asigl1,fgcsub.sub_csub,ref_asigl1
			from fgcsub
			join fgsub on emp_sub = emp_csub and cod_sub = sub_csub
			join fgasigl1 a1 on emp_Asigl1 = emp_csub and sub_asigl1 = sub_csub and ref_asigl1 = ref_csub and licit_asigl1 = fgcsub.licit_csub and fgcsub.himp_csub = imp_asigl1
			where emp_csub = :emp
			$where
			order by 1
			)group by tipopuja";




		$result = \DB::select($sql, $this->params);

		foreach($result as $item){
			$item->tipopuja = trans('admin-app.values.pujrep_'.$item->tipopuja);
		}

		$charts[] = $this->generateData(["doughnut"],trans("admin-app.reportsBi.titlesReport.imp_bids"),$result, trans("admin-app.reportsBi.titlesReport.imp_bids"), 'tipopuja','suma' );
		$charts[] = $this->generateData(["doughnut"],trans("admin-app.reportsBi.titlesReport.num_bids"),$result, trans("admin-app.reportsBi.titlesReport.num_bids"), 'tipopuja','cuantos' );

		#HAY MUY POCOS DATOS QUEDA MUY MAL LA TABLA TAN PEQUEÑA
		/*
				$datatable = $result;

				$titles = ["tipopuja"=>"Tipo de puja","suma" =>"Importe puja","cuantos"=> "Nº de pujas"];
				$classes=["tipopuja"=>"td_text","suma"=> "td_number","cuantos" => "td_number"];
					//$width=['family'=>'25%','sub_family'=>'25%','lots'=>'10%', "sold" => '10%', "sold_pct"=> '10%',  "price_format" => '15%', "sold_price_format" => '15%',"revaluation" =>'10%'];
		*/


		if(request("export")=="excel"){
			$headers = ["Tipo de Puja","Importe adjudicación", "Número de pujas"];
			return (new AwardsExport($result, $headers ))->download("report_".$name."_" . date("Ymd-H:m:s") . ".xlsx");

		}

		$params = $this->params;

		return compact('name','sql', 'params', 'charts'); #, 'datatable','titles','classes'
	}

		#Pujadores
		private function biddersReport(){
			$name = trans("admin-app.reportsBi.reports.bidders");

			#Como hay dos queries, debemos reiniciar los params antes de llamar a la segunda query
			$params_anteriores = $this->params;

			#no podemso usar los filtros generelaes ya que ha subqueries en el select
			/*
			$whereAsigl1 = $this->allFilters("fec_asigl1",null, "sub_asigl1");
			$whereSub = $this->allFilters(null,"tipo_sub", null);
			*/
			$where = $this->allFilters('"start"',"tipo_sub", "cod_sub");

			$this->params["gemp"] = \Config::get("app.gemp");
			//$where_month= $this->monthFilter("fec_asigl1");
			#PUJAS SUBSCRITOS AL ATALOGO
			#hay que ir cuidado con los where por que no podemos restringir los usuarios, si no las pujas, por lo que todos los where deben ir en los left join
			$sql="select cod_cli,rsoc_cli, cod_sub, max(des_sub) des_sub,max(envcat_cli2) catalogo, count(pujas) as lotes_pujados, sum(pujas) as pujas_realizadas, sum(importe) importe_pujas
			from fglicit
			inner join fxcli on  cod_cli = cli_licit
            inner join fxcli2 on gemp_cli2 = gemp_cli and cod_cli2 = cod_cli
			--es necesaria la subquery para sacar el numero de lotes pujados
			join (select max(imp_asigl1)as importe, count(imp_asigl1) as pujas, max(fec_asigl1) as fec_asigl1  ,emp_asigl1,sub_asigl1,ref_asigl1,licit_asigl1 from  fgasigl1 group by emp_asigl1,sub_asigl1,ref_asigl1,licit_asigl1)on emp_asigl1 = emp_licit and  sub_asigl1 = sub_licit and licit_asigl1 = cod_licit
			join fgsub on emp_sub = emp_asigl1 and cod_sub = sub_asigl1
			inner join \"auc_sessions\" auc on auc.\"company\" = EMP_LICIT AND auc.\"auction\" = sub_licit and auc.\"init_lot\" <= ref_asigl1 and   auc.\"end_lot\" >= ref_asigl1
			where gemp_cli = :gemp
            and emp_licit = :emp
			$where
			group by cod_cli, rsoc_cli,cod_sub
			order by max(\"start\") asc,max(fec_asigl1)
				";




			$pujas = \DB::select($sql, $this->params);





			$clients = [];
			$auctions=[];
			$pujadores=0;

			$pujas_realizadas=0;
			$importe_pujas=0;
			$ganadores=0;
			$lotes_adjudicados=0;
			$importe_ganado=0;
			$totales=["cod_sub"=> "","auction"=> "Total:","pujas_realizadas" => 0, "importe_pujas" => 0,"num_adjudicaciones"=>0,"importe_adjudicaciones" => 0];
			foreach($pujas as $puja){


				$clients[$puja->cod_cli."-".$puja->cod_sub] = $puja;

				$suscrito = $puja->catalogo =='S'?1:0;
				if(empty($auctions[$puja->cod_sub])){
					$auctions[$puja->cod_sub] = (object) ["cod_sub" => $puja->cod_sub,"auction" => $puja->des_sub,"pujas_realizadas"=>$puja->pujas_realizadas,"importe_pujas"=>round($puja->importe_pujas),"suscritos" =>$suscrito,"pujadores"=>1 ,"num_adjudicaciones" => 0,"importe_adjudicaciones" => 0,"ganadores" => 0,"bidders" =>[] ];
					$auctions[$puja->cod_sub]->bidders[$puja->cod_cli] = $puja;
				}else{
					$auctions[$puja->cod_sub]->pujas_realizadas += $puja->pujas_realizadas;
					$auctions[$puja->cod_sub]->suscritos += $suscrito;
					$auctions[$puja->cod_sub]->pujadores ++;

					$auctions[$puja->cod_sub]->importe_pujas += round($puja->importe_pujas);
					$auctions[$puja->cod_sub]->bidders[$puja->cod_cli] = $puja;
				}


					$pujadores++;

					$pujas_realizadas+= $puja->pujas_realizadas;
					$totales["pujas_realizadas"] +=$puja->pujas_realizadas;
					$importe_pujas+= $puja->importe_pujas;
					$totales["importe_pujas"] +=$puja->importe_pujas;

			}
			#Como hay dos queries, debemos reiniciar los params antes de llamar a la segunda query
			$this->params = $params_anteriores;
			$where = $this->allFilters('"start"',"tipo_sub", "cod_sub");
			/*
			$whereAsigl1 = $this->allFilters("fec_asigl1",null, "sub_asigl1");
			$whereSub = $this->allFilters(null,"tipo_sub", null);
			*/
			$this->params["gemp"] = \Config::get("app.gemp");
			#ADJUDICACIONES pujadores

			$sql="select cod_cli,rsoc_cli,cod_sub, count(himp_csub) as num_adjudicaciones, sum(nvl(himp_csub,0)) importe_adjudicaciones
			from fglicit
			inner join fxcli on  cod_cli = cli_licit
			inner join fgsub on emp_sub = emp_licit and cod_sub = sub_licit
			join (select emp_asigl1,sub_asigl1,ref_asigl1,licit_asigl1, max(fec_asigl1) as fec_asigl1 from  fgasigl1 group by emp_asigl1,sub_asigl1,ref_asigl1,licit_asigl1)on emp_asigl1 = emp_licit and  sub_asigl1 = sub_licit and licit_asigl1 = cod_licit
			--no quitar left join ya que deben salir tantos usuarios como pujas hayan hecho
			left join fgcsub on emp_csub = emp_asigl1 and ref_csub = ref_asigl1 and clifac_csub = cod_cli
			inner join \"auc_sessions\" auc on auc.\"company\" = EMP_LICIT AND auc.\"auction\" = sub_licit and auc.\"init_lot\" <= ref_asigl1 and   auc.\"end_lot\" >= ref_asigl1

			where gemp_cli = :gemp
			and emp_licit = :emp
			$where
			group by cod_cli, rsoc_cli,cod_sub";

			$adjudicaciones = \DB::select($sql, $this->params);


			foreach($adjudicaciones as $adjudicacion){
				$clients[$adjudicacion->cod_cli."-".$adjudicacion->cod_sub]->num_adjudicaciones =$adjudicacion->num_adjudicaciones;
				$auctions[$adjudicacion->cod_sub]->bidders[$adjudicacion->cod_cli]->num_adjudicaciones =$adjudicacion->num_adjudicaciones;
				$auctions[$adjudicacion->cod_sub]->num_adjudicaciones += $adjudicacion->num_adjudicaciones;
				$totales["num_adjudicaciones"]+=$adjudicacion->num_adjudicaciones;

				$clients[$adjudicacion->cod_cli."-".$adjudicacion->cod_sub]->importe_adjudicaciones = $adjudicacion->importe_adjudicaciones;
				$auctions[$adjudicacion->cod_sub]->bidders[$adjudicacion->cod_cli]->importe_adjudicaciones =$adjudicacion->importe_adjudicaciones;
				$auctions[$adjudicacion->cod_sub]->importe_adjudicaciones += round($adjudicacion->importe_adjudicaciones);
				$totales["importe_adjudicaciones"]+=$adjudicacion->importe_adjudicaciones;
				#este valor no tiene sentido en las agrupacioens de suabstas

				if($adjudicacion->num_adjudicaciones > 0){
					$auctions[$adjudicacion->cod_sub]->ganadores ++;
					$ganadores++;
					$lotes_adjudicados+= $adjudicacion->num_adjudicaciones ;
					$importe_ganado+= $adjudicacion->importe_adjudicaciones ;
				}


			}
			$totales["importe_adjudicaciones"] = ToolsServiceProvider::moneyFormat($totales["importe_adjudicaciones"], " €",0);
			$totales["importe_pujas"] = ToolsServiceProvider::moneyFormat($totales["importe_pujas"], " €",0);

			$chart=[];
		foreach($auctions as $auction){
			$chart[]=$this->chartData("Pujadores", $auction->auction, $auction->pujadores);


			$chart[]=$this->chartData("pujas realizadas", $auction->auction, $auction->pujas_realizadas);

			$chart[]=$this->chartData("Ganadores", $auction->auction, $auction->ganadores);
			$chart[]=$this->chartData("Lotes adjudicados", $auction->auction, $auction->num_adjudicaciones);
		}
				$charts[] = $this->generateDataMultidimensional(["bar"],trans("admin-app.reportsBi.titlesReport.bidders_award"),$chart,'title', 'label','value',1);

				#no tiene mucho sentido estos datos
/*
			$chart3[]=$this->chartData("Importe Pujas máximas", "", $importe_pujas,"bar");
			$chart3[]=$this->chartData("Importe adjudicado", "", $importe_ganado,"bar");
				$charts[] = $this->generateDataMultidimensional(["bar"],trans("admin-app.reportsBi.titlesReport.amount_bidders_award"),$chart3,'title', 'label','value');
*/

			$datatable = $auctions;
			$subtable="bidders";

			$titles = ["cod_sub"=>"Código subasta","auction" =>"Subasta","pujas_realizadas" => "Pujas realizadas", "importe_pujas" =>"Importe pujas","num_adjudicaciones" =>"Lotes adjudicados","importe_adjudicaciones"=>"Importe adjudicación"];
			$subtitles = ["cod_cli"=>"Código cliente","rsoc_cli" =>"Cliente","pujas_realizadas" => "Pujas realizadas", "importe_pujas" =>"Importe pujas","num_adjudicaciones" =>"Lotes adjudicados","importe_adjudicaciones"=>"Importe adjudicación"];
			$classes=["cod_sub"=>"td_number","auction" =>"td_text","pujas_realizadas" => "td_number", "importe_pujas" =>"td_number","num_adjudicaciones" =>"td_number","importe_adjudicaciones"=>"td_number","cod_cli" => "td_number", "rsoc_cli" => "td_text"];

			$width=["cod_sub"=>"15%","auction" =>"35%","pujas_realizadas" => "10%", "importe_pujas" =>"15%","num_adjudicaciones" =>"10%","importe_adjudicaciones"=>"15%","cod_cli"=>"15%","rsoc_cli" =>"35%"];
			//	'family'=>'25%','sub_family'=>'25%','lots'=>'10%', "sold" => '10%', "sold_pct"=> '10%',  "price_format" => '15%', "sold_price_format" => '15%',"revaluation" =>'10%'];



			if(request("export")=="excel"){
				$headers = ["Código cliente","nombre cliente", "Código subasta" , "Subasta", "Subscrito Catálogo" , "Lotes pujados", "Pujas realizadas", "importe pujas","lotes adjudicados","Importe adjudicación"];
				return (new AwardsExport($clients, $headers ))->download("report_".$name."_" . date("Ymd-H:m:s") . ".xlsx");
			}

			$params = $this->params;
			return compact('name','sql', 'params', 'charts','datatable','subtable','titles','classes','totales','subtitles','width');
		}

	#Clientes con catalogo
	private function catalogClientsReport(){
		$name = trans("admin-app.reportsBi.reports.catalog_subscribed");

		#Como hay dos queries, debemos reiniciar los params antes de llamar a la segunda query
		$params_anteriores = $this->params;

		#no podemso usar los filtros generelaes ya que ha subqueries en el select
		$whereAsigl1 = $this->allFilters("fec_asigl1",null, "sub_asigl1");
		$whereSub = $this->allFilters(null,"tipo_sub", null);

		$this->params["gemp"] = \Config::get("app.gemp");
		//$where_month= $this->monthFilter("fec_asigl1");
		#PUJAS SUBSCRITOS AL ATALOGO
		#hay que ir cuidado con los where por que no podemos restringir los usuarios, si no las pujas, por lo que todos los where deben ir en los left join
		$sql="select cod_cli,rsoc_cli,  count(pujas) as lotes_pujados, sum(nvl(pujas,0)) as pujas_realizadas, sum(nvl(importe,0)) importe_pujas

		from fxcli
		inner join fxcli2 on gemp_cli2 = gemp_cli and cod_cli2 = cod_cli

		left join fglicit on emp_licit = :emp and   cli_licit = cod_cli
		--es necesaria la subquery para sacar el numero de lotes pujados
		left join (select max(imp_asigl1)as importe, count(imp_asigl1) as pujas, max(fec_asigl1) as fec_asigl1  ,emp_asigl1,sub_asigl1,ref_asigl1,licit_asigl1 from  fgasigl1 group by emp_asigl1,sub_asigl1,ref_asigl1,licit_asigl1)on emp_asigl1 = emp_licit and  sub_asigl1 = sub_licit and licit_asigl1 = cod_licit $whereAsigl1
		left join fgsub on emp_sub = emp_asigl1 and cod_sub = sub_asigl1 $whereSub
		where gemp_cli = :gemp
		and fxcli2.envcat_cli2 = 'S'
		group by cod_cli, rsoc_cli
			";




		$pujas = \DB::select($sql, $this->params);





		$clients = [];
		$suscritos=0;
		$pujadores=0;
		$lotes_pujados=0;
		$pujas_realizadas=0;
		$importe_pujas=0;
		$ganadores=0;
		$lotes_adjudicados=0;
		$importe_ganado=0;
		$totales=["cod_cli"=> "","rsoc_cli"=> "Total:","lotes_pujados" => 0,"pujas_realizadas" => 0, "importe_pujas" => 0,"num_adjudicaciones"=>0,"importe_adjudicaciones" => 0];
		foreach($pujas as $puja){
			$clients[$puja->cod_cli] = $puja;
			$suscritos++;
			if($puja->pujas_realizadas > 0){
				$pujadores++;
				$lotes_pujados+= $puja->lotes_pujados;
				$totales["lotes_pujados"] +=$puja->lotes_pujados;
				$pujas_realizadas+= $puja->pujas_realizadas;
				$totales["pujas_realizadas"] +=$puja->pujas_realizadas;
				$importe_pujas+= $puja->importe_pujas;
				$totales["importe_pujas"] +=$puja->importe_pujas;
			}
		}
		#Como hay dos queries, debemos reiniciar los params antes de llamar a la segunda query
		$this->params = $params_anteriores;
		$whereCsub = $this->allFilters("fecha_csub",null, "sub_csub");
		$whereSub = $this->allFilters(null,"tipo_sub", null);
		$this->params["gemp"] = \Config::get("app.gemp");
		#ADJUDICACIONES SUBSCRITOS AL ATALOGO
		#hay que ir cuidado con los where por que no podemos restringir los usuarios, si no las pujas, por lo que todos los where deben ir en los left join
		$sql="select cod_cli,rsoc_cli, count(himp_csub) as num_adjudicaciones, sum(nvl(himp_csub,0)) importe_adjudicaciones
		from fxcli
		inner join fxcli2 on gemp_cli2 = gemp_cli and cod_cli2 = cod_cli
		left join fgcsub on emp_csub = :emp and clifac_csub = cod_cli $whereCsub
		left join fgsub on emp_sub = emp_csub and cod_sub = sub_csub $whereSub
		where gemp_cli = :gemp
		and fxcli2.envcat_cli2 = 'S'
		group by cod_cli, rsoc_cli";

		$adjudicaciones = \DB::select($sql, $this->params);


		foreach($adjudicaciones as $adjudicacion){
			$clients[$adjudicacion->cod_cli]->num_adjudicaciones =$adjudicacion->num_adjudicaciones;
			$totales["num_adjudicaciones"]+=$adjudicacion->num_adjudicaciones;
			$clients[$adjudicacion->cod_cli]->importe_adjudicaciones = $adjudicacion->importe_adjudicaciones;


			$totales["importe_adjudicaciones"]+=$adjudicacion->importe_adjudicaciones;
			$clients[$adjudicacion->cod_cli]->importe_pujas = $clients[$adjudicacion->cod_cli]->importe_pujas;

			if($adjudicacion->num_adjudicaciones > 0){
				$ganadores++;
				$lotes_adjudicados+= $adjudicacion->num_adjudicaciones ;
				$importe_ganado+= $adjudicacion->importe_adjudicaciones ;
			}
		}
		$totales["importe_adjudicaciones"] = ToolsServiceProvider::moneyFormat($totales["importe_adjudicaciones"], " €",0);
		$totales["importe_pujas"] = ToolsServiceProvider::moneyFormat($totales["importe_pujas"], " €",0);


		$chart[]=$this->chartData("Suscritos", "", $suscritos);
		$chart[]=$this->chartData("Pujadores", "", $pujadores);
		$chart[]=$this->chartData("Ganadores", "", $ganadores);
			$charts[] = $this->generateDataMultidimensional(["bar"],trans("admin-app.reportsBi.titlesReport.subscribed_clients"),$chart,'title', 'label','value');

		$chart2[]=$this->chartData("pujas realizadas", "", $pujas_realizadas);
		$chart2[]=$this->chartData("Lotes pujados", "", $lotes_pujados);
		$chart2[]=$this->chartData("Lotes adjudicados", "", $lotes_adjudicados);

			$charts[] = $this->generateDataMultidimensional(["bar"],trans("admin-app.reportsBi.titlesReport.subscribed_clients_award"),$chart2,'title', 'label','value');


		$chart3[]=$this->chartData("Importe Pujas máximas", "", $importe_pujas,"bar");
		$chart3[]=$this->chartData("Importe adjudicado", "", $importe_ganado,"bar");
			$charts[] = $this->generateDataMultidimensional(["bar"],trans("admin-app.reportsBi.titlesReport.amount_subscribed_clients_award"),$chart3,'title', 'label','value');


		$datatable = $clients;

		$titles = ["cod_cli"=>"Código cliente","rsoc_cli" =>"Cliente","lotes_pujados"=> "Lotes pujados","pujas_realizadas" => "Pujas realizadas", "importe_pujas" =>"Importe pujas","num_adjudicaciones" =>"Lotes adjudicados","importe_adjudicaciones"=>"Importe adjudicación"];
		$classes=["cod_cli"=>"td_number","rsoc_cli" =>"td_text","lotes_pujados"=> "td_number","pujas_realizadas" => "td_number", "importe_pujas" =>"td_number","num_adjudicaciones" =>"td_number","importe_adjudicaciones"=>"td_number"];

		//$width=['family'=>'25%','sub_family'=>'25%','lots'=>'10%', "sold" => '10%', "sold_pct"=> '10%',  "price_format" => '15%', "sold_price_format" => '15%',"revaluation" =>'10%'];



		if(request("export")=="excel"){
			$headers = ["Código cliente","nombre cliente", "Lotes pujados", "Pujas realizadas", "importe pujas","lotes adjudicados","Importe adjudicación"];
			return (new AwardsExport($clients, $headers ))->download("report_".$name."_" . date("Ymd-H:m:s") . ".xlsx");
		}
		$pagingDatatable=true;
		$params = $this->params;
		return compact('name','sql', 'params', 'charts','datatable','titles','classes','pagingDatatable','totales');
	}
	#Registro BI
	private function logAccessReport(  ){
		$name="Registro de Acceso al BI de Gutinvest";
		$sql="select cod_cli, usrw_cliweb, nom_cli, 'Administrador' as tipo_usuario, date_web_login_log as fecha, ip_web_login_log as ip from web_login_log
		join fxcli on cod_cli = codcli_web_login_log
		join fxcliweb on cod_cliweb=cod_cli and emp_cliweb=emp_web_login_log
		where gemp_cli ='01' and web_login_log.EMP_WEB_LOGIN_LOG='001' and tipacceso_cliweb = 'S'
		and usrw_cliweb like '%gutinvest.es'
		and cod_cli != '000001'
		and date_web_login_log > '2024/05/01'
		order by date_web_login_log ";
		$datatable = \DB::select($sql);
		$titles=["fecha" => "Fecha","cod_cli"=>"Código Cliente", "nom_cli" => "Nombre", "usrw_cliweb" => "Email",   "tipo_usuario" => "Tipo de Usuario",  "ip" => "IP" ];
		$classes=["cod_cli"=>"td_number",  "usrw_cliweb" => "td_text",  "nom_cli" =>"td_text","tipo_usuario"=> "td_text","fecha" => "td_text", "ip" =>"td_text"];
		$pagingDatatable=true;
		$charts=[];
		$params = $this->params;
		return compact('name','sql', 'params', 'charts','datatable','titles','classes','pagingDatatable');

	}

	private function categoryAwardsSalesReport( $type ="NUM"){
		return $this->categoryAwards( "SALES");
	}

	private function categoryAwardsAmountReport( $type ="NUM"){
		return $this->categoryAwards( "AMOUNT");
	}



	#Adjudicaciones por familias
	private function categoryAwards( $type ){


		if($type=="SALES"){
			$name = trans("admin-app.reportsBi.reports.sale_category");
		}elseif($type=="AMOUNT"){
			$name = trans("admin-app.reportsBi.reports.amount_sale_category");
		}

		$this->params["gemp"] = \Config::get("app.gemp");

		$where = $this->allFilters('"start"',"tipo_sub", "cod_sub");

		$sql="
		select  cod_sub,  impsalhces_asigl0 price,HIMP_CSUB sold_price ,lin_ortsec0 cod_family, des_ortsec0 family,  cod_sec cod_subfamily, des_sec subfamily
		from FGASIGL0
		inner join FGHCES1 on FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0
		inner join \"auc_sessions\" auc on auc.\"company\" = FGASIGL0.EMP_ASIGL0 AND auc.\"auction\" = FGASIGL0.SUB_ASIGL0 and auc.\"init_lot\" <= ref_asigl0 and   auc.\"end_lot\" >= ref_asigl0

		inner join FGSUB on FGSUB.EMP_SUB = FGASIGL0.EMP_ASIGL0 AND FGSUB.COD_SUB = FGASIGL0.SUB_ASIGL0
		left join FGCSUB on EMP_CSUB = EMP_SUB AND SUB_CSUB = COD_SUB AND REF_CSUB = REF_ASIGL0
		inner join FGORTSEC1 ON EMP_ORTSEC1 = EMP_ASIGL0 AND SUB_ORTSEC1 = '0' AND SEC_ORTSEC1 = SEC_HCES1
		INNER JOIN FGORTSEC0 ON EMP_ORTSEC0 = EMP_ASIGL0 AND LIN_ORTSEC0 = LIN_ORTSEC1
		inner join FXSEC on  cod_sec = sec_hces1
		where
		gemp_sec = :gemp
		and
		emp_asigl0 = :emp
		and
		subc_sub in ('S','H')
		and
		cerrado_asigl0 = 'S'
		$where
		";




		$result = \DB::select($sql, $this->params);
		$datatable = [] ;

		$families=[];

		foreach($result as $item){
			if(empty($families[$item->cod_family])){
				$families[$item->cod_family]=(object) ["family" => $item->family,"lots"=>0, "sold"=>0, "sold_price"=>0, "price"=>0, "sub_family"=>[]] ;
			}

			if(empty($families[$item->cod_family]->sub_families[$item->cod_subfamily])){
				$families[$item->cod_family]->sub_families[$item->cod_subfamily]= (object) ["family" => $item->family,"sub_family" => $item->subfamily,"lots"=>1, "sold"=>0, "sold_price"=>0, "price"=>0] ;
			}

				$families[$item->cod_family]->sub_families[$item->cod_subfamily]->lots+=1;
				if(!empty($item->sold_price)){
					$families[$item->cod_family]->sub_families[$item->cod_subfamily]->sold++;
					$families[$item->cod_family]->sub_families[$item->cod_subfamily]->sold_price += round($item->sold_price);
					$families[$item->cod_family]->sub_families[$item->cod_subfamily]->price += $item->price;
				}
		}
		$excel=[];
		$totales=["family"=>"Totales:","sold" => 0,"lots" => 0,"price" => 0,"sold_price" => 0 ];

		#rellenamos los datos de las familias
		foreach($families as $cod_family => $family){
			foreach($family->sub_families as $cod_subfamily => $sub_family){


				$families[$cod_family]->lots += $sub_family->lots;
				$families[$cod_family]->sold += $sub_family->sold;
				$families[$cod_family]->sold_price += $sub_family->sold_price;
				$families[$cod_family]->price += $sub_family->price;

				$families[$cod_family]->sub_families[$cod_subfamily]->sold_pct =  round( (($sub_family->sold / $sub_family->lots) ) *100, 2) ."%";


				if($sub_family->price >0){
					$families[$cod_family]->sub_families[$cod_subfamily]->revaluation =  round( (($sub_family->sold_price / $sub_family->price) -1) *100, 2) ."%";
				}else{
					$families[$cod_family]->sub_families[$cod_subfamily]->revaluation =  "0%";
				}

				#ponemos los datos en la variable a imprimir en excel, lo clonamos para que no se añadan los campos formateados que vienen despues
				$excel[$cod_subfamily]= clone $families[$cod_family]->sub_families[$cod_subfamily];

				#formateamos el precio de venta en subfamilias y calculamos la revalorización y el porcentaje de adjudicados
				$families[$cod_family]->sub_families[$cod_subfamily]->sold_price_format =ToolsServiceProvider::moneyFormat($sub_family->sold_price," €",0);
				$families[$cod_family]->sub_families[$cod_subfamily]->price_format =ToolsServiceProvider::moneyFormat($sub_family->price," €",0);


			}
			$totales["lots"] +=$families[$cod_family]->lots;
			$totales["sold"] +=$families[$cod_family]->sold;
			$totales["sold_price"] +=$families[$cod_family]->sold_price;
			$totales["price"] +=$families[$cod_family]->price;
			$families[$cod_family]->sold_price_format = ToolsServiceProvider::moneyFormat($families[$cod_family]->sold_price," €",0);
			$families[$cod_family]->price_format = ToolsServiceProvider::moneyFormat($families[$cod_family]->price," €",0);


			$families[$cod_family]->sold_pct =  round( (($families[$cod_family]->sold / $families[$cod_family]->lots) ) *100, 2) ."%";


			if($families[$cod_family]->price >0){
				$families[$cod_family]->revaluation =  round( (($families[$cod_family]->sold_price / $families[$cod_family]->price) -1) * 100 , 2) ."%";
			}else{
				$families[$cod_family]->revaluation = "0%";
			}
		}
		if($totales["lots"] >0){
			$totales["sold_pct"] = round( (($totales["sold"]  / $totales["lots"]) ) *100, 2) ."%";
		}else{
			$totales["revaluation"] = "0%";
		}
		if($totales["price"] >0){
			$totales["revaluation"] =  round( (($totales["sold_price"] / $totales["price"]) -1) * 100 , 2) ."%";
		}else{
			$totales["revaluation"] = "0%";
		}
		$totales["sold_price_format"] = ToolsServiceProvider::moneyFormat($totales["sold_price"]," €",0);
		$totales["price_format"] = ToolsServiceProvider::moneyFormat($totales["price"]," €",0);




		$chartFamilies=[];
		foreach($families as $family){

			if($type=="SALES"){

				$chartFamilies[]=$this->chartData("Lotes", $family->family, $family->lots);
				$chartFamilies[]=$this->chartData("Lotes Adjudicados", $family->family, $family->sold);
			}elseif($type=="AMOUNT"){
				$chartFamilies[]=$this->chartData("Importe Salida", $family->family, $family->price);
				$chartFamilies[]=$this->chartData("Importe Ventas", $family->family, $family->sold_price);
			}
		}

		$charts[] = $this->generateDataMultidimensional(["bar"],$name,$chartFamilies, 'title', 'label','value',1 );
		#generamos un grafico para cada familia con los datos de sus subfamilias
		foreach($families as $family){
			if($type=="SALES"){
				$charts[] = $this->generateData(["doughnut"],$family->family,$family->sub_families, 'title', 'sub_family','sold' );
			}elseif($type=="AMOUNT"){
				$charts[] = $this->generateData(["doughnut"],$family->family,$family->sub_families, 'title', 'sub_family','sold_price' );
			}
		}


		$datatable = $families;
		$subtable = "sub_families";

		$titles = ['family'=>'Familia','lots'=>"lotes", "sold" => "Adjudicados", "sold_pct"=> "%Adjudicados", "price_format" => "Importe Salida", "sold_price_format" => "Importe Venta", "revaluation" => "Revalorización"];
		$subtitles = ['sub_family'=>'Subfamilia','lots'=>"lotes", "sold" => "Adjudicados", "sold_pct"=> "%Adjudicados", "price_format" => "Importe Salida",  "sold_price_format" => "Importe Venta", "revaluation" => "Revalorización"];

		$classes=['family'=>'td_text','sub_family'=>'td_text','lots'=>'td_number', "sold" => 'td_number', "sold_pct"=> "td_number", "price_format" => 'td_number', "sold_price_format" => 'td_number', "revaluation" => "td_number"];
		$width=['family'=>'25%','sub_family'=>'25%','lots'=>'10%', "sold" => '10%', "sold_pct"=> '10%',  "price_format" => '15%', "sold_price_format" => '15%',"revaluation" =>'10%'];

		if(request("export")=="excel"){
			$headers =[];
			if(!empty($excel)){
				#cojemos los campos
				$headers = array_keys((array)head($excel));
				#ponemos el campo como indice
				$headers =array_combine($headers,$headers );
				#reemplazamos los campos por nombre compresibles
				$headers =array_replace($headers, ['family'=>'Familia','sub_family'=>'Subfamilia','lots'=>"lotes", "sold" => "Adjudicados", "sold_pct"=> "% Adjudicados", "price" => "Importe Salida",  "sold_price" => "Importe Venta", "revaluation" => "Revalorización"]);

			}

			return (new AwardsExport($excel, $headers ))->download("report_".$name."_" . date("Ymd-H:m:s") . ".xlsx");
		}

		$params = $this->params;

		return compact('name','sql', 'params', 'charts','datatable','titles','subtitles','classes','width','totales', 'subtable');


	}

	private function auctionAwardsSalesReport( $type ="NUM"){
		return $this->auctionAwards( "SALES");
	}

	private function auctionAwardsAmountReport( $type ="NUM"){
		return $this->auctionAwards( "AMOUNT");
	}

#Adjudicaciones por Subastas
private function auctionAwards( $type ){


	if($type=="SALES"){
		$name = trans("admin-app.reportsBi.reports.sale_auction");
	}elseif($type=="AMOUNT"){
		$name = trans("admin-app.reportsBi.reports.amount_sale_auction");
	}

	$this->params["gemp"] = \Config::get("app.gemp");

	$where = $this->allFilters('"start"',"tipo_sub", "cod_sub");

	$sql="
	select  cod_sub, des_sub,  impsalhces_asigl0 price,HIMP_CSUB sold_price ,lin_ortsec0 cod_family, des_ortsec0 family
	from FGASIGL0
	inner join FGHCES1 on FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0
	inner join \"auc_sessions\" auc on auc.\"company\" = FGASIGL0.EMP_ASIGL0 AND auc.\"auction\" = FGASIGL0.SUB_ASIGL0 and auc.\"init_lot\" <= ref_asigl0 and   auc.\"end_lot\" >= ref_asigl0

	inner join FGSUB on FGSUB.EMP_SUB = FGASIGL0.EMP_ASIGL0 AND FGSUB.COD_SUB = FGASIGL0.SUB_ASIGL0
	left join FGCSUB on EMP_CSUB = EMP_SUB AND SUB_CSUB = COD_SUB AND REF_CSUB = REF_ASIGL0
	inner join FGORTSEC1 ON EMP_ORTSEC1 = EMP_ASIGL0 AND SUB_ORTSEC1 = '0' AND SEC_ORTSEC1 = SEC_HCES1
	INNER JOIN FGORTSEC0 ON EMP_ORTSEC0 = EMP_ASIGL0 AND LIN_ORTSEC0 = LIN_ORTSEC1
	inner join FXSEC on  cod_sec = sec_hces1
	where
	gemp_sec = :gemp
	and
	emp_asigl0 = :emp
	and
	subc_sub in ('S','H')
	and
	cerrado_asigl0 = 'S'
	$where
	order by \"start\"
	";




	$result = \DB::select($sql, $this->params);
	$datatable = [] ;

	$auctions=[];

	foreach($result as $item){
		if(empty($auctions[$item->cod_sub])){
			$auctions[$item->cod_sub]=(object) ["auction" => $item->des_sub,"lots"=>0, "sold"=>0, "sold_price"=>0, "price"=>0, "family"=>[]] ;
		}

		if(empty($auctions[$item->cod_sub]->families[$item->cod_family])){
			$auctions[$item->cod_sub]->families[$item->cod_family]= (object) ["auction" => $item->des_sub,"family" => $item->family,"lots"=>1, "sold"=>0, "sold_price"=>0, "price"=>0] ;
		}

			$auctions[$item->cod_sub]->families[$item->cod_family]->lots+=1;
			if(!empty($item->sold_price)){
				$auctions[$item->cod_sub]->families[$item->cod_family]->sold++;
				$auctions[$item->cod_sub]->families[$item->cod_family]->sold_price += round($item->sold_price);
				$auctions[$item->cod_sub]->families[$item->cod_family]->price += $item->price;
			}
	}
	$excel=[];
	$totales=["auction"=>"Totales:","sold" => 0,"lots" => 0,"price" => 0,"sold_price" => 0 ];

	#rellenamos los datos de las subastas
	foreach($auctions as $cod_auction => $auction){
		foreach($auction->families as $cod_family => $family){


			$auctions[$cod_auction]->lots += $family->lots;
			$auctions[$cod_auction]->sold += $family->sold;
			$auctions[$cod_auction]->sold_price += $family->sold_price;
			$auctions[$cod_auction]->price += $family->price;

			$auctions[$cod_auction]->families[$cod_family]->sold_pct =  round( (($family->sold / $family->lots) ) *100, 2) ."%";


			if($family->price >0){
				$auctions[$cod_auction]->families[$cod_family]->revaluation =  round( (($family->sold_price / $family->price) -1) *100, 2) ."%";
			}else{
				$auctions[$cod_auction]->families[$cod_family]->revaluation =  "0%";
			}

			#ponemos los datos en la variable a imprimir en excel, lo clonamos para que no se añadan los campos formateados que vienen despues
			$excel[$cod_auction."-".$cod_family]= clone $auctions[$cod_auction]->families[$cod_family];

			#formateamos el precio de venta en subfamilias y calculamos la revalorización y el porcentaje de adjudicados
			$auctions[$cod_auction]->families[$cod_family]->sold_price_format =ToolsServiceProvider::moneyFormat($family->sold_price," €",0);
			$auctions[$cod_auction]->families[$cod_family]->price_format =ToolsServiceProvider::moneyFormat($family->price," €",0);


		}
		$totales["lots"] +=$auctions[$cod_auction]->lots;
		$totales["sold"] +=$auctions[$cod_auction]->sold;
		$totales["sold_price"] +=$auctions[$cod_auction]->sold_price;
		$totales["price"] +=$auctions[$cod_auction]->price;
		$auctions[$cod_auction]->sold_price_format = ToolsServiceProvider::moneyFormat($auctions[$cod_auction]->sold_price," €",0);
		$auctions[$cod_auction]->price_format = ToolsServiceProvider::moneyFormat($auctions[$cod_auction]->price," €",0);


		$auctions[$cod_auction]->sold_pct =  round( (($auctions[$cod_auction]->sold / $auctions[$cod_auction]->lots) ) *100, 2) ."%";


		if($auctions[$cod_auction]->price >0){
			$auctions[$cod_auction]->revaluation =  round( (($auctions[$cod_auction]->sold_price / $auctions[$cod_auction]->price) -1) * 100 , 2) ."%";
		}else{
			$auctions[$cod_auction]->revaluation = "0%";
		}
	}
	if($totales["price"] >0){
		$totales["sold_pct"] = round( (($totales["sold"]  / $totales["lots"]) ) *100, 2) ."%";
	}else{
		$totales["sold_pct"] = "0%";
	}
	if($totales["price"] >0){
		$totales["revaluation"] =  round( (($totales["sold_price"] / $totales["price"]) -1) * 100 , 2) ."%";
	}else{
		$totales["revaluation"] = "0%";
	}
	$totales["sold_price_format"] = ToolsServiceProvider::moneyFormat($totales["sold_price"]," €",0);
	$totales["price_format"] = ToolsServiceProvider::moneyFormat($totales["price"]," €",0);




	$chartAuctions=[];
	foreach($auctions as $auction){

		if($type=="SALES"){

			$chartAuctions[]=$this->chartData("Lotes", $auction->auction, $auction->lots);
			$chartAuctions[]=$this->chartData("Lotes Adjudicados", $auction->auction, $auction->sold);
		}elseif($type=="AMOUNT"){
			$chartAuctions[]=$this->chartData("Importe Salida", $auction->auction, $auction->price);
			$chartAuctions[]=$this->chartData("Importe Ventas", $auction->auction, $auction->sold_price);
		}
	}

	$charts[] = $this->generateDataMultidimensional(["bar"],$name,$chartAuctions, 'title', 'label','value',1 );
	#generamos un grafico para cada subasta con los datos de sus familias
	foreach($auctions as $auction){
		if($type=="SALES"){
			$charts[] = $this->generateData(["doughnut"],"Adjudicados ". $auction->auction,$auction->families, 'title', 'family','sold' );
		}elseif($type=="AMOUNT"){
			$charts[] = $this->generateData(["doughnut"],"Adjudicados ".$auction->auction,$auction->families, 'title', 'family','sold_price' );
		}
	}


	$datatable = $auctions;
	$subtable = "families";
	$titles = ['auction'=>'Subasta','lots'=>"lotes", "sold" => "Adjudicados", "sold_pct"=> "%Adjudicados", "price_format" => "Importe Salida", "sold_price_format" => "Importe Venta", "revaluation" => "Revalorización"];
	$subtitles = ['family'=>'Familia','lots'=>"lotes", "sold" => "Adjudicados", "sold_pct"=> "%Adjudicados", "price_format" => "Importe Salida",  "sold_price_format" => "Importe Venta", "revaluation" => "Revalorización"];

	$classes=['auction'=>'td_text','family'=>'td_text','lots'=>'td_number', "sold" => 'td_number', "sold_pct"=> "td_number", "price_format" => 'td_number', "sold_price_format" => 'td_number', "revaluation" => "td_number"];
	$width=['auction'=>'25%','family'=>'25%','lots'=>'10%', "sold" => '10%', "sold_pct"=> '10%',  "price_format" => '15%', "sold_price_format" => '15%',"revaluation" =>'10%'];

	if(request("export")=="excel"){
		$headers =[];

		if(!empty($excel)){
			#cojemos los campos
			$headers = array_keys((array)head($excel));
			#ponemos el campo como indice
			$headers =array_combine($headers,$headers );
			#reemplazamos los campos por nombre compresibles
			$headers =array_replace($headers, ['auction'=>'Subasta','family'=>'familia','lots'=>"lotes", "sold" => "Adjudicados", "sold_pct"=> "% Adjudicados", "price" => "Importe Salida",  "sold_price" => "Importe Venta", "revaluation" => "Revalorización"]);

		}

		return (new AwardsExport($excel, $headers ))->download("report_".$name."_" . date("Ymd-H:m:s") . ".xlsx");
	}

	$params = $this->params;

	return compact('name','sql', 'params', 'charts','datatable','titles','subtitles','classes','width','totales','subtable');


}









	#filters
	private function allFilters($dateField, $typeSub, $codSub ){
		$where = "";
		if(!empty($dateField)){
			$where .= $this->dateFilter($dateField);
			$where .= $this->monthFilter($dateField);
		}
		if(!empty($typeSub)){
			$where .= $this->typeAucFilter($typeSub);
		}
		if(!empty($codSub)){
			$where .= $this->auctionFilter($codSub);
		}
		return $where;
	}

	private function dateFilter($field){


		#cargamos los años o el año actual
		$years = request("years",[date("Y")]);
		$where =" and (";
		$or="";
		#filtro por años
		foreach ($years as $year){
			if(!empty($year)){
				$where.=" $or ($field >= '$year-01-01' and $field <= '$year-12-31')";
				$or="or";
			}
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
				foreach ($months as $month){
					if(!empty($month)){
						$where.=" $or month($field) = $month";
						$or="or";
					}

				}
				$where .=") ";
		}
		return $where;
	}

	private function typeAucFilter($field){


		#cargamos los tipos de subastas o nada
		$typesAuc = request("tipo_subs",[]);
		$where ="";
		$i=1;
		if(count($typesAuc) > 0){
			$where =" and (";
			$or="";
			#filtro por typo de subasta
			foreach ($typesAuc as $typeAuc){
				$where.=" $or $field = :".$field."_".$i;
				$or="or";
				$this->params[$field."_".$i]= $typeAuc;
				$i++;
			}
			$where .=") ";
		}

		return $where;
	}

	private function auctionFilter($field){


		#cargamos los tipos de subastas o nada
		$auctions = request("auctions",[]);
		$where ="";
		$i=1;
		if(count($auctions) > 0){
			$where =" and (";
			$or="";
			#filtro por código de subasta
			foreach ($auctions as $typeAuc){
				$where.=" $or $field = :".$field."_".$i;
				$or="or";
				$this->params[$field."_".$i]= $typeAuc;
				$i++;
			}
			$where .=") ";
		}

		return $where;
	}

	private function chartData($title, $label, $value, $type = null){
		return (object) ["title" => $title, "label" => $label, "value" => $value, "type" => $type ];
	}


	#fin filters
	private function generateData($types ,$name ,$result, $varTitle, $varLabel, $varData, $columns = 2 )
	{

		$titles = array();
		$labels = array();

		foreach ($result as $record){
			$labels[$record->{$varLabel}]= $record->{$varLabel};
			if(empty($titles[$varTitle])){
				$titles[$varTitle] = array();
			}
			$titles[$varTitle][$record->{$varLabel}] = $record->{$varData};

		}



		$chart = new Chart($types,$name, $labels, $varLabel,$varData, $columns);

			if(count($titles)>0){
				foreach($titles as $key => $labels){
					$chart->insertData($labels, $key);
				}
			}
			return $chart;

	}


	private function generateDataMultidimensional($types ,$name,$result, $varTitle, $varLabel, $varData, $columns = 2 )
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
			#podemos definir un tipo especial de grafico, por ejemplo poner lineas en un grafico de barras
			if(!empty($record->type)){
				$subtypes[$record->{$varTitle}] =$record->type;
			}

		}
		#rellenar a 0 los valores que faltan
		foreach ($titles as $key => $title){
			foreach ($labels as $label){
				if(empty($titles[$key][$label])){
					$titles[$key][$label] = 0;
				}
			}
			#es necesario ordenar para que los valores correspondan con las etiquetas
			ksort($titles[$key]);
		}


		$chart = new Chart($types,$name, $labels, $varLabel,$varData, $columns);


			foreach($titles as $key => $labels){
				$chart->insertData($labels, $key,$subtypes[$key]?? null);
			}
			return $chart;

	}



}

class Chart{
	public $columns = "";
	public $types =[];
	public $name ="";
	public $varLabel ="";
	public $varData ="";
	public $datasets =[];
	public $labels =[];
	#contienen transparencia
	public $colors =[

		'#ff717190',
		'#6868ec90',

		'#008e8e90',
		'#803b6890',
		'#bf978090',
		'#40687f90',
		'#e7b97990',
		'#f4fab490',
		'#f7cae790',
		'#95fab990',
];
	public $indexActualcolor = 0;
	/*

	public $data =[];
*/
	public function __construct($types,$name, $labels,  $varLabel, $varData, $columns = 2)
	{
		$this->columns = $columns;

		$this->types = $types;
		$this->name = $name;

		$this->varLabel = $varLabel;
		$this->varData = $varData;

		$this->labels= $labels;

	}

	public function insertData($records, $title, $type = null){
		$dataset = ["title"=>"",
					"data" => []];
		if(!empty($type)){
			$dataset["type"] = $type;
		}

		foreach ( $records as $record){
		//	echo $this->varLabel."  ".$record->{$this->varLabel}." <br>";

			$dataset["title"] =$title;
			$dataset["data"][] = $record; /*  $record->{$this->varData}; */
		}
		$this->datasets[] = $dataset;
	}
	public function getColors($type) {

		if(in_array($type,["polarArea", "doughnut"]) ){
			return "'".implode("','",$this->colors)."'";
		}else{
			#cogemos el modulo para no pasarnos nunca del indice
			$index = $this->indexActualcolor % count($this->colors);
			$color = $this->colors[$index];
			$this->indexActualcolor++;

			return "'".$color."'";
		}

	}
}
