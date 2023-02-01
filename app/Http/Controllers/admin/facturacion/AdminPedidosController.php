<?php

namespace App\Http\Controllers\admin\facturacion;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgHces1;
use App\Models\V5\FxCli;
use App\Models\articles\FgArt0;
use App\Models\articles\FgArt;
use App\Models\articles\FgPedc0;
use App\Models\articles\FgPedc1;
use Config;

use DB;

class AdminPedidosController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'facturacion']);
	}

	public function index(Request $request)
	{
		$pedidos = FgPedc0::select( "anum_pedc0,num_pedc0, max(cod_pedc0) cod_pedc0 , max(rsoc_pedc0) rsoc_pedc0, max(dto3_pedc1) dto3_pedc1, max(dto_pedc1) dto_pedc1, max(fecha_pedc0) fecha_pedc0, max(total_pedc0) total_pedc0, listagg('*' || des_pedc1,'<br>') within group (order by num_pedc0) obras ")
			->JoinPedc1()
			->when($request->anum_pedc0, function ($query, $anum_pedc0) {
				return $query->where('anum_pedc0',  "$anum_pedc0");
			})->when($request->num_pedc0, function ($query, $cod_pro) {
				return $query->where('num_pedc0',  "$cod_pro");
			})
			->when($request->cod_pedc0, function ($query, $nom_pro) {
				return $query->where('cod_pedc0', 'like', "%".mb_strtoupper($nom_pro)."%");
			})
			->when($request->rsoc_pedc0, function ($query, $rsoc_pedc0) {
				return $query->where('upper(rsoc_pedc0)', 'like', "%".mb_strtoupper($rsoc_pedc0)."%");
			})
			->when($request->fecha_pedc0, function ($query, $fecha_pedc0) {
				return $query->where('fecha_pedc0',  $fecha_pedc0);
			})
			->when($request->total_pedc0, function ($query, $total_pedc0) {
				return $query->where('total_pedc0', $total_pedc0);
			})

			->orderBy($request->input('order', 'anum_pedc0'), $request->input('order_dir', 'desc'))
			->orderBy('num_pedc0', 'desc')
			->groupby('anum_pedc0',"num_pedc0")
			->paginate(30);

		$tableParams = ['anum_pedc0' => 1,'num_pedc0' => 1, 'cod_pedc0' => 1, 'rsoc_pedc0' => 1, 'fecha_pedc0' => 1, 'total_pedc0' => 1, 'dto_pedc1' => 1, 'dto3_pedc1' => 1, 'obras' => 1];

		$formulario = (object)[
			'anum_pedc0' => FormLib::Text('anum_pedc0', 0, $request->anum_pedc0),
			'num_pedc0' => FormLib::Text('num_pedc0', 0, $request->num_pedc0),
			'cod_pedc0' => FormLib::Text('cod_pedc0', 0, $request->cod_pedc0),
			'rsoc_pedc0' => FormLib::Text('rsoc_pedc0', 0, $request->rsoc_pedc0),
			'fecha_pedc0' => FormLib::Text('fecha_pedc0', 0, $request->fecha_pedc0),
			'total_pedc0' => FormLib::Text('total_pedc0', 0,$request->total_pedc0),
			'dto3_pedc1' => FormLib::Text('dto3_pedc1', 0,$request->dto3_pedc1),
			'dto_pedc1' => FormLib::Text('dto_pedc1', 0,$request->dto_pedc1),

		];

		return view('admin::pages.facturacion.pedidos.index', compact('pedidos', 'tableParams', 'formulario'));
	}

	public function create(Request $request)
	{
		$formulario = (object)[
			'client'  => FormLib::Select2WithAjax('client', 1, '', '', route('client.list'), trans('admin-app.placeholder.cli_creditosub' )),
			'obras'     => FormLib::Select2WithAjax('obra', 1, '', '', route('lotListFondoGaleria'), trans('admin-app.placeholder.obra' ))


		];

		return view('admin::pages.facturacion.pedidos.create', compact( 'formulario'));

	}

	public function store()
	{

		$this->createPedidoFromLots( request("subastas"),  request("client"), request("impTotalForzado"));
		/*
		\Log::info(print_r($request->subastas, true));
		\Log::info(print_r($request->client, true));
		*/
	}

	#Los pedidos se realizan con articulos, esta funcion convierte lotes en articulos para poder realizar el pedido
	public function createPedidoFromLots($auctions, $cod_cli, $importeTotalForzado){


		$hoy = date("Y-m-d");
		$emp = Config::get('app.emp');
		$gemp  = Config::get('app.gemp');
		$paymentController = new PaymentsController();
		#saber iva del usuario
		$iva = $paymentController->getIva($emp, $hoy);
		$tipo = $paymentController->user_has_Iva($gemp, $cod_cli);
		$tipoIva = $tipo->tipo;

		$iva_cli = $paymentController->hasIvaReturnIva($tipoIva, $iva);


		$asigl0 = FgAsigl0::query();

		$refLots=" ( ";
		$or = "";
		#Los lotes vendrán en un array dentro de la subasta, por lo que deberemos convertir eso en condiciones desql válidas
		foreach ($auctions as $cod_sub => $lots){
			$refLots.= "$or (sub_asigl0 = '$cod_sub' and ref_asigl0 in (". implode(",", $lots) .") )";
			$or = " OR ";
		}
		$refLots.=" )";
		$lots = $asigl0->GetLotsByRefAsigl0( $refLots)->addselect("prop_hces1, pc_hces1, stock_hces1")->get();
		$precioBaseTotal = 0;

		# recorremos los lotes para calcular el importe total y así poder usar ese valor para calcular el importe a descontar de cada lotes
		foreach($lots as $lot){
			$precioBaseTotal += $lot->impsalhces_asigl0;
		}

		#Crear cabecera de pedidos
			$idPedido= FgPedc0::select("nvl(max(NUMFIC_PEDC0),0) +1 as NUMFIC")->first()->numfic;

			$hash = strtoupper(md5(time()));
			#el resultado debe ser de 17 carcateres
			$numOrder = substr($hash, 0, 16-(strlen($idPedido))) . "-".$idPedido;

			$user = FxCli::select("CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, CIF_CLI")->WHERE("COD_CLI", $cod_cli)->first();

			DB::select("call Crea_pedido.CREA_CAPCELERA(:gemp, :emp, :idPed, :codCli, :cp, :pob, :prov, :telf, :obs, :nif, :codDir, :transport, :payment, to_char(sysdate,'YYYY-MM-DD HH24:Mi:ss'), :numOrder)",
			array(

				'gemp'        => Config::get('app.gemp'),
				'emp'        => Config::get('app.emp'),
				'idPed'    => $idPedido,
				'codCli'        => $cod_cli,
				'cp'     =>$user->cp_cli,
				'pob'     =>$user->pob_cli,
				'prov'     =>$user->pro_cli,
				'telf'     =>$user->tel1_cli,
				'obs'     => request("observaciones"),
				'nif'     =>$user->cif_cli,
				'codDir'	=> '00',
				'transport' => '',
				'payment'	=> "",
				'numOrder'		=> $numOrder
				)

				);

		#importe que ha indicado el usuario, debemos quitarle el iva
		$importeTotalForzadoSinIva = round($importeTotalForzado /(1+ ($iva_cli/100)),2);
		#variable en la que sumamos los importes que llevamos para en el último calcular la diferencia con el total y así que no haya perdida de decimales
		$importeactual = 0;
		$contador = 0;
		foreach($lots as $lot){

			$contador++;
			# si no es el ultimo calculamos el importe
			if($contador != count($lots)){
				#calculamos el procentaje que representa este lote respecto al total
				$porcentaje = round(($lot->impsalhces_asigl0) / $precioBaseTotal,2);
				#echo "<br>porcentaje:".$porcentaje;

				$lot->impArticulo = round($importeTotalForzadoSinIva*$porcentaje,2);
				$importeactual += $lot->impArticulo;
			}else{
				# si es el último restamos el importe que falte, así no descuadran decimales
				$lot->impArticulo =$importeTotalForzadoSinIva - $importeactual;
			}


			#usamos la secuencia para calcular el siguiente
			$idArt =DB::select("SELECT FGART0_SEQ.NEXTVAL as idArt FROM SYS.DUAL")[0]->idart;   #FgArt0::select("nvl(max(id_art0),0) +1 as idart")->first()->idart;

			#crea articulo 0 a partir del lote
			$article0 = $this->lotToArticle0($lot, $idArt);
			#crea articulo apartir de articulo0
			$article = $this->Article0ToArticle($article0, $lot->prop_hces1);


			DB::select("call Crea_pedido.CREA_LINIES(:idPed, :emp, :seccio, :codi, :cant, to_char(sysdate,'YYYY-MM-DD'), :descuento)",
			array(
				'idPed'    => $idPedido,

				'emp'        => Config::get('app.emp'),
				'seccio'        => $article["sec_art"],
				'codi'     => $article["cod_art"],
				'cant'     =>  1,
				'descuento'=> 0,
				)
			);


			# si hay valor de stock mayor que cero Y TIENEN ACTIVADO EL CONTRO LDE STOCK le restamos 1
			if(!empty($lot->stock_hces1) && $lot->stock_hces1>0){
				FgHces1::where("NUM_HCES1", $lot->num_hces1)->where("LIN_HCES1", $lot->lin_hces1)->where("CONTROLSTOCK_HCES1", "S")->update(["stock_hces1"=> $lot->stock_hces1 -1]);
			}
		}
	}

	#convierte lotes en articulos
	public function lotToArticle0($lot, $idArt){

		#falta completar
		$new["emp_art0"] = $lot->emp_asigl0;
		$new["sec_art0"] = $lot->sec_hces1;

		$new["model_art0"] = $lot->sub_asigl0 . "-" . $lot->ref_asigl0 ;
		$new["des_art0"] = mb_substr($lot->descweb_hces1,0,255);
		$new["title_art0"] = mb_substr($lot->descweb_hces1,0,255);
		$new["pvp_art0"] = $lot->impArticulo;
		$new["cstk_art0"] ="N";
		$new["id_art0"] = $idArt;
		$new["pct_art0"] =$lot->pc_hces1;
		$new["dto1_art0"] = 0;
		$new["dto2_art0"] = 0;
		$new["dto3_art0"] = 0;
		$new["pc_art0"] = $lot->pc_hces1;
		$new["f_alta_art0"] = date('Y-m-d H:i:s');
		$new["pro_art0"] = $lot->prop_hces1;
		$new["tiva_art0"] = "01";
		#mantenemos el importe original
		$new["pvpt_art0"] = $lot->impsalhces_asigl0;
		$new["ctrcop_art0"] = "S";
		$new["web_art0"] = "N";
		$new["grupo_art0"] = "V";
		$new["produccion_art0"] = "N";
		$new["calcprecio_art0"] = "P";
		$new["udadxcaja_art0"] = 1;
		$new["revendedor_art0"] = "N";
		$new["baja_art0"] = 'N';
		$new["penbaja_art0"] = 'N';
		$new["comb_art0"] = 0;
		$new["tprod_art0"] = 'G';



		FgArt0::create($new);
		return $new;
	}
	public function Article0ToArticle($article, $prop){

		$new["emp_art"] = $article["emp_art0"] ;
		$new["sec_art"] = $article["sec_art0"] ;
		$new["cod_art"] = $article["id_art0"] ;
		$new["idart0_art"] = $article["id_art0"] ;
		$new["des_art"] = $article["des_art0"] ;
		$new["pro_art"] = $prop ;
		$new["carac_art"] = "" ;
		$new["usra_art"] = "auto" ;
		$new["f_alta_art"] = $article["f_alta_art0"] ;
		$new["tprod_art"] = $article["tprod_art0"] ;
		$new["marca_art"] = "" ;
		$new["pct_art"] = $article["pct_art0"] ;
		$new["dto1_art"] = $article["dto1_art0"] ;
		$new["dto2_art"] = $article["dto2_art0"] ;
		$new["dto3_art"] = $article["dto3_art0"] ;
		$new["pc_art"] = $article["pc_art0"] ;

		$new["pvp_art"] = $article["pvp_art0"] ;
		$new["tiva_art"] = $article["tiva_art0"] ;
		$new["stk_art"] = 'N' ;
		$new["cantxbul_art"] = 1 ;
		$new["newref_art"] = $article["model_art0"] ;
		$new["dtoc_art"] = 0;
		$new["bajat_art"] ='N' ;
		$new["pro_art"] =$article["pro_art0"] ;
		$new["pvpt_art"] =$article["pvp_art0"] ;
		$new["pendbaja_art"] ='N' ;
		$new["subfam_art"] ="" ;
		$new["idautor_art"] ="" ;
		$new["autor_art"] ="" ;
		$new["idtecnica_art"] ="" ;
		$new["tecnica_art"] ="" ;
		$new["subfam_art"] ="" ;
		$new["web_art"] ="" ;
		$new["calcprecio_art"] ="" ;
		FgArt::create($new);
		return $new;

	}

	public function destroy($num_pedc0)
	{

		$pedc0 = explode("-",$num_pedc0);

		$pedido = FgPedc0::where('anum_pedc0', $pedc0[0])->where('num_pedc0', $pedc0[1])->first();
		$lineaspedido = FgPedc1::where('anum_pedc1', $pedc0[0])->where('num_pedc1', $pedc0[1])->get();

		if (!$pedido) {
			return back()->withErrors(['errors' => ['sale not exist']])->withInput();
		}elseif($pedido->estado_pedc0=='S'){
			return back()->withErrors(['errors' => ['sale with delivery note or  invoice']])->withInput();
		}
		$articulos=[];
		foreach($lineaspedido as $lineaPedido){
			if ($lineaPedido->cants_pedc1 != 0) {
				return back()->withErrors(['errors' => ['sale with delivery note or  invoice']])->withInput();
			}
			$art0 = FgArt0::select("MODEL_ART0")->where("ID_ART0", $lineaPedido->art_pedc1)->first();

			if(!empty($art0)){
				$articulos[] = explode("-",$art0->model_art0);
			}

		}

		try {
			DB::beginTransaction();

			#añadir stock
			foreach($articulos as $articulo){
				FgHces1::where("SUB_HCES1", $articulo[0])->where("REF_HCES1", $articulo[1])->where("CONTROLSTOCK_HCES1", "S")->update(["stock_hces1"=>DB::raw('stock_hces1+1')]);

			}

			FgPedc0::where('anum_pedc0', $pedc0[0])->where('num_pedc0', $pedc0[1])->delete();
			FgPedc1::where('anum_pedc1', $pedc0[0])->where('num_pedc1', $pedc0[1])->delete();

			DB::commit();

		} catch (\Throwable $th) {
			DB::rollBack();

			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}

		return redirect(route('pedidos.index'))->with(['success' => array(trans('admin-app.title.deleted_ok'))]);

	}


	public function importeBasePedido(){
		$auctions =request("subastas");
		$codCli = request("client");
		$paymentController = new PaymentsController();
		$hoy = date("Y-m-d");
		$emp = Config::get('app.emp');
		$gemp  = Config::get('app.gemp');
		#por defecto ponemos 1 por si no han puesto el usuario
		$tipoIva = 1;
		$iva = $paymentController->getIva($emp, $hoy);
		if(!empty($codCli)){
			$tipo = $paymentController->user_has_Iva($gemp, $codCli);
			$tipoIva = $tipo->tipo;
		}

		$iva_cli = $paymentController->hasIvaReturnIva($tipoIva, $iva);


		$asigl0 = FgAsigl0::query();

		$refLots=" ( ";
		$or = "";
		#Los lotes vendrán en un array dentro de la subasta, por lo que deberemos convertir eso en condiciones desql válidas
		foreach ($auctions as $cod_sub => $lots){
			$refLots.= "$or (sub_asigl0 = '$cod_sub' and ref_asigl0 in (". implode(",", $lots) .") )";
			$or = " OR ";
		}
		$refLots.=" )";
		$lots = $asigl0->GetLotsByRefAsigl0( $refLots)->addselect("prop_hces1, pc_hces1")->get();

		$importe = 0;
		foreach($lots as $lot){
			$importe+= $lot->impsalhces_asigl0;
		}

		return ["importe" => $importe,"iva"=> $iva_cli];
	}
}
