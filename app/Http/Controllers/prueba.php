<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\externalws\duran\ClientController;
use App\Http\Controllers\externalws\duran\OrderController;
use App\Http\Controllers\V5\ArticleController;
use App\Models\articles\FgArt;
use App\Models\articles\FgArt0;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCaracteristicas_Hces1_Lang;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Models\V5\FgCaracteristicas_Value_Lang;
use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgHces1;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgPedc0;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Models\V5\FxSec;
use App\Models\V5\Web_Faq;
use App\Models\V5\Web_FaqCat;
use App\Providers\ToolsServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use stdClass;

class prueba extends BaseController
{

	public function index() {}

	private function testConnection()
	{
		return DB::table('jobs')->get()->count();
	}

	private function testInvaluable()
	{
		$house = new App\Http\Controllers\externalAggregator\Invaluable\House();
		$a = $house->catalogs("2248", "001");
		echo $a;
	}

	private function sendFailedJobs($fromId, $toId)
	{
		foreach (range($fromId, $toId) as $id) {
			Artisan::call('queue:retry', ['id' => $id]);
		}
	}

	public function traspaso_fgcaracteristicas()
	{


		//$res = $this->traspaso_auc_custom_fields();
		//$res = $this->traspaso_auc_custom_fields_lang();
		//$res = $this->traspaso_object_types();
		//$res = $this->traspaso_object_types_lang();
		#ultimo paso pasar values
		$res = $this->traspaso_values_hces1();
		if ($res) {

			return redirect('prueba?a=' . rand());
		}
	}
	# una vez volcados todos los datos, hacemos update en fgcaracteristica_hces1 para añadir los codes
	public function traspaso_values_hces1($numelements = 200)
	{
		$caracteristicas = FgCaracteristicas::get();


		$idcaracteristicas = array();
		foreach ($caracteristicas as $caracteristica) {
			$idcaracteristicas[$caracteristica->name_caracteristicas] = $caracteristica->id_caracteristicas;
		}


		$sql = "select \"field\" from \"auc_custom_fields_values\" group by \"field\"";
		$fields = DB::select($sql, []);
		$select = "";
		$where = array();

		foreach ($fields as $field) {
			$select .= ', "' . $field->field . '_code" ';
			$where[] = '  "' . $field->field . '_code" is not null ';
		}

		$sql = "select * from (
		select \"transfer_sheet_number\" , \"transfer_sheet_line\"  $select
		, ROW_NUMBER() OVER(order by \"transfer_sheet_number\" , \"transfer_sheet_line\") as rn
		from \"object_types_values\"
		left join (select EMP_CARACTERISTICAS_HCES1,NUMHCES_CARACTERISTICAS_HCES1, LINHCES_CARACTERISTICAS_HCES1 from fgcaracteristicas_hces1 where idvalue_caracteristicas_hces1 is not null group by EMP_CARACTERISTICAS_HCES1,NUMHCES_CARACTERISTICAS_HCES1, LINHCES_CARACTERISTICAS_HCES1) T
			on EMP_CARACTERISTICAS_HCES1 = \"company\" and  NUMHCES_CARACTERISTICAS_HCES1 =  \"transfer_sheet_number\" and  LINHCES_CARACTERISTICAS_HCES1 = \"transfer_sheet_line\"
		where \"company\" = " . Config::get("app.emp") . " " .
			"and T.EMP_CARACTERISTICAS_HCES1 is null
		and (" . implode(' or ', $where) . ")
		)
			where rn <= $numelements";

		$codes = DB::select($sql, []);
		echo "traspasando códigos" . count($codes) . " a la FgCaracteristicas_Hces1";
		if (count($codes) == 0) {
			die();
		}

		foreach ($codes as $code) {

			foreach ($code as $atribute => $val) {

				if (!is_null($val) && $atribute != "transfer_sheet_number"  && $atribute != "transfer_sheet_line"  && $atribute != "rn") {

					$atribute = str_replace("_code", "", $atribute);
					FgCaracteristicas_Hces1::where("NUMHCES_CARACTERISTICAS_HCES1", $code->transfer_sheet_number)->where("LINHCES_CARACTERISTICAS_HCES1", $code->transfer_sheet_line)->where("IDCAR_CARACTERISTICAS_HCES1", $idcaracteristicas[$atribute])->update(["IDVALUE_CARACTERISTICAS_HCES1" => $val]);
				}
			}
		}

		return true;
	}


	# OJO tener en cuenta que la tabla de fgcaracteristicas_value debe estar vacia para esa empresa, si no es así habrá que sumar un numero
	public function traspaso_auc_custom_fields($numelements = 200)
	{
		$caracteristicas = FgCaracteristicas::get();

		$select = "";
		$idcaracteristicas = array();
		foreach ($caracteristicas as $caracteristica) {
			$select .= ' ,"' . $caracteristica->name_caracteristicas . '"';
			$idcaracteristicas[$caracteristica->name_caracteristicas] = $caracteristica->id_caracteristicas;
		}
		$sql = "select * from (
			select \"auc_custom_fields_values\".*,  ROW_NUMBER() OVER(order by \"id\") as rn from \"auc_custom_fields_values\"
			left join fgcaracteristicas_value on emp_caracteristicas_value = '" . Config::get("app.emp") . "' and id_caracteristicas_value = \"id\"
			where emp_caracteristicas_value is null
			)
			where rn <= $numelements";

		$customFieldsValue = DB::select($sql, []);

		$caracteristicasValue = array();

		echo "traspasando " . count($customFieldsValue) . " a la traspaso_auc_custom_fields";

		if (count($customFieldsValue) == 0) {
			#si acabamos ya no redireccionamos
			die();
		}
		foreach ($customFieldsValue as $customFieldValue) {

			$caracteristicasValue[] = [
				"EMP_CARACTERISTICAS_VALUE" => Config::get("app.emp"),
				"ID_CARACTERISTICAS_VALUE" =>  $customFieldValue->id,
				"IDCAR_CARACTERISTICAS_VALUE" => $idcaracteristicas[$customFieldValue->field],
				"OBJ_TYPE_CARACTERISTICAS_VALUE" =>  $customFieldValue->object_type,
				"SECTION_CARACTERISTICAS_VALUE" =>  $customFieldValue->section,
				"VALUE_CARACTERISTICAS_VALUE" => $customFieldValue->value
			];
		}

		FgCaracteristicas_Value::insert($caracteristicasValue);
		return true;
	}

	public function traspaso_auc_custom_fields_lang($numelements = 200)
	{
		$caracteristicas = FgCaracteristicas::get();

		$select = "";
		$idcaracteristicas = array();
		foreach ($caracteristicas as $caracteristica) {
			$select .= ' ,"' . $caracteristica->name_caracteristicas . '"';
			$idcaracteristicas[$caracteristica->name_caracteristicas] = $caracteristica->id_caracteristicas;
		}
		$sql = "select * from (
			select \"auc_custom_fields_values_lang\".*,  ROW_NUMBER() OVER(order by \"id_lang\") as rn from \"auc_custom_fields_values_lang\"
			left join fgcaracteristicas_value_lang on emp_car_val_lang = '" . Config::get("app.emp") . "' and idcarval_car_val_lang = \"id_lang\"
			where emp_car_val_lang is null
			)
			where rn <= $numelements";

		$customFieldsValue_lang = DB::select($sql, []);

		$caracteristicasValue_lang = array();
		echo "traspasando " . count($customFieldsValue_lang) . " a la traspaso_auc_custom_fields_lang";

		if (count($customFieldsValue_lang) == 0) {
			#si acabamos ya no redireccionamos
			die();
		}
		foreach ($customFieldsValue_lang as $customFieldValue_lang) {

			$caracteristicasValue_lang[] = [
				"EMP_CAR_VAL_LANG" => Config::get("app.emp"),
				"IDCARVAL_CAR_VAL_LANG" =>  $customFieldValue_lang->id_lang,
				"LANG_CAR_VAL_LANG" =>  $customFieldValue_lang->lang,
				"VALUE_CAR_VAL_LANG" =>  $customFieldValue_lang->value_lang
			];
		}

		FgCaracteristicas_Value_Lang::insert($caracteristicasValue_lang);
		return true;
	}

	public function traspaso_object_types($numelements = 200)
	{
		$caracteristicas = FgCaracteristicas::get();

		$select = "";
		$idcaracteristicas = array();
		$whereNull = array();
		foreach ($caracteristicas as $caracteristica) {
			$select .= ' ,"' . $caracteristica->name_caracteristicas . '"';
			$idcaracteristicas[$caracteristica->name_caracteristicas] = $caracteristica->id_caracteristicas;
			$whereNull[] = ' NVL(LENGTH("' . $caracteristica->name_caracteristicas . '"),0) > 0';
		}

		$lotes = FgHces1::select("emp_hces1, num_hces1, lin_hces1" . $select)->join('"object_types_values"', '"company" = emp_hces1 and "transfer_sheet_number" = num_hces1 and "transfer_sheet_line" = lin_hces1')->leftjoin("FGCARACTERISTICAS_HCES1", "EMP_CARACTERISTICAS_HCES1 = EMP_HCES1 AND NUMHCES_CARACTERISTICAS_HCES1 = NUM_HCES1 AND LINHCES_CARACTERISTICAS_HCES1=LIN_HCES1")->
			/* comprobamos que no exista en la tabla de caracteristicas */where("EMP_CARACTERISTICAS_HCES1")->whereRaw(" (" . implode(' or ', $whereNull) . " )")->take($numelements)->get();

		$caracteristicasHces1 = array();

		echo "traspasando " . count($lotes) . " a la traspaso_object_types";

		if (count($lotes) == 0) {
			#si acabamos ya no redireccionamos
			die();
		}

		foreach ($lotes as $lote) {

			foreach ($lote->toArray() as $atribute => $val) {

				if (!is_null($val) && $atribute != "emp_hces1"  && $atribute != "num_hces1" && $atribute != "lin_hces1" && $atribute != "rn") {

					#son los campos clob, "technical_description" se sacara a las hces1
					if (in_array($atribute, ["obverse", "reverse"])) {
						#cada p que haya ponemos un salto de linea
						$val = str_replace("</p>",  "</p>\n", $val);
						#quitamos código HTML ya que si no se superan los 2000 caracteres
						$val = strip_tags($val);
					}

					$caracteristicasHces1[] = [
						"EMP_CARACTERISTICAS_HCES1" => $lote->emp_hces1,
						"NUMHCES_CARACTERISTICAS_HCES1" => $lote->num_hces1,
						"LINHCES_CARACTERISTICAS_HCES1" => $lote->lin_hces1,
						"IDCAR_CARACTERISTICAS_HCES1" => $idcaracteristicas[$atribute],
						"VALUE_CARACTERISTICAS_HCES1" => $val
					];
				}
			}
		}

		FgCaracteristicas_Hces1::insert($caracteristicasHces1);

		return true;
	}

	public function traspaso_object_types_lang($numelements = 200)
	{
		$caracteristicas = FgCaracteristicas::get();

		$select = "";
		$idcaracteristicas = array();

		$whereNull = array();

		foreach ($caracteristicas as $caracteristica) {
			$select .= ' ,"' . $caracteristica->name_caracteristicas . '_lang"';
			$idcaracteristicas[$caracteristica->name_caracteristicas] = $caracteristica->id_caracteristicas;
			$whereNull[] = ' NVL(LENGTH("' . $caracteristica->name_caracteristicas . '_lang"),0) > 0';
		}



		$lotes = FgHces1::select("emp_hces1, num_hces1, lin_hces1, \"lang_object_types_values_lang\" " . $select)->join('"object_types_values_lang"', '"company_lang" = emp_hces1 and "transfer_sheet_number_lang" = num_hces1 and "transfer_sheet_line_lang" = lin_hces1')->leftjoin("FGCARACTERISTICAS_HCES1_LANG", "EMP_CAR_HCES1_LANG = EMP_HCES1 AND NUMHCES_CAR_HCES1_LANG = NUM_HCES1 AND LINHCES_CAR_HCES1_LANG=LIN_HCES1 AND LANG_CAR_HCES1_LANG =  \"lang_object_types_values_lang\"")->
			/* comprobamos que no exista en la tabla de caracteristicas  por lo que  miramso que EMP_CAR_HCES1_LANG sea nulo*/where("EMP_CAR_HCES1_LANG")->whereRaw(" (" . implode(' or ', $whereNull) . " )")->take($numelements)->get();

		$caracteristicasHces1 = array();

		echo "traspasando " . count($lotes) . " a la object_types_values_lang";

		if (count($lotes) == 0) {
			#si acabamos ya no redireccionamos
			die();
		}

		foreach ($lotes as $lote) {

			foreach ($lote->toArray() as $atribute => $val) {

				if (!is_null($val) && $atribute != "emp_hces1"  && $atribute != "num_hces1" && $atribute != "lin_hces1" && $atribute != "rn" && $atribute != "lang_object_types_values_lang") {
					if (in_array($atribute, ["obverse_lang", "reverse_lang"])) {
						#cada p que haya ponemos un salto de linea
						$val = str_replace("</p>",  "</p>\n", $val);
						#quitamos código HTML ya que si no se superan los 2000 caracteres
						$val = strip_tags($val);
					}

					$caracteristicasHces1[] = [
						"EMP_CAR_HCES1_LANG" => $lote->emp_hces1,
						"NUMHCES_CAR_HCES1_LANG" => $lote->num_hces1,
						"LINHCES_CAR_HCES1_LANG" => $lote->lin_hces1,
						"IDCAR_CAR_HCES1_LANG" => $idcaracteristicas[str_replace("_lang", "", $atribute)],
						"VALUE_CAR_HCES1_LANG" => $val,
						"LANG_CAR_HCES1_LANG" => $lote->lang_object_types_values_lang
					];
				}
			}
		}
		FgCaracteristicas_Hces1_Lang::insert($caracteristicasHces1);

		return true;
	}



	public function duplicadosAnsorena()
	{
		$sql = "
		select  cod_cli codigo,nom_cli nombre, cif_cli cif, concat(dir_cli,dir2_cli) direccion, tel1_cli telefono, tel2_cli telefono2,  email_cli email,TO_CHAR(f_alta_cli, 'DD/MM/YYYY') as fecha_alta,  compras, TO_CHAR(fecha_ultima_compra, 'DD/MM/YYYY') fecha_ultima_compra, ventas, TO_CHAR(fecha_ultima_venta, 'DD/MM/YYYY') fecha_ultima_venta, TO_CHAR(fecha_ultima_puja, 'DD/MM/YYYY') fecha_ultima_puja, TO_CHAR(fecha_ultima_cesion, 'DD/MM/YYYY')fecha_ultima_cesion

				from fxcli A
			   left join (select sum(himp_csub) compras, max(fecha_csub) fecha_ultima_compra,clifac_csub,emp_csub  from fgcsub group by clifac_csub,emp_csub) T on emp_csub = '001' and  clifac_csub= A.cod_cli
				left join (
				   select max(fec_asigl1) fecha_ultima_puja,cli_licit,emp_licit  from fgasigl1
				   join fglicit on emp_licit = emp_asigl1 and sub_licit = sub_asigl1 and cod_licit = licit_asigl1
				   group by emp_licit, cli_licit
				   ) T2 on emp_licit = '001'    and  cli_licit= A.cod_cli
			   left join (
			   select max(fec_his) fecha_ultima_cesion,cod_his,emp_his from fxhis2 where tdoc_his ='HC' and accion_his='ALT'
				group by emp_his, cod_his
			   )T3 on  emp_his = '001' and  cod_his= A.cod_cli

			   left join (select sum(total_dvc0) ventas, max(fecha_dvc0) fecha_ultima_venta, cod_dvc0,emp_dvc0  from FXDVC0 where tipo_dvc0 ='P' and emp_dvc0='001' group by emp_dvc0, cod_dvc0 ) V on  emp_dvc0='001' and V.cod_dvc0 = A.cod_cli
			  where gemp_cli = '01' and baja_tmp_cli ='N' and
			   (
				   cif_cli not in (
					   select cif_cli from fxcli where gemp_cli = '01' and length(cif_cli) > 5 and baja_tmp_cli ='N' group by cif_cli having(count(cif_cli) >1)
				   )
				   and
				   nom_cli in (
					   select nom_cli from fxcli where gemp_cli = '01' and length(nom_cli) > 1 and baja_tmp_cli ='N'
					  and  cif_cli not in (
					   select cif_cli from fxcli where gemp_cli = '01' and length(cif_cli) > 5 and baja_tmp_cli ='N' group by cif_cli having(count(cif_cli) >1)
				   )

					   group by nom_cli having(count(nom_cli) >1)
					)

			   )
		order by nom_cli,nvl(fecha_ultima_puja,fecha_ultima_cesion)desc, nvl(fecha_ultima_compra,fecha_ultima_venta)desc

	   ";
		#and  cif_cli in (' GB788152982','AAA916794')
		$users = DB::select($sql, []);
		$listado = [];
		foreach ($users as $key => $user) {
			if ($key > 0) {
				if ($users[$key - 1]->nombre != $user->nombre) {
					$listado[] = collect(["codigo" => "", "nombre" => "", "cif" => "", "direccion" => "", "telefono" => "", "telefono2" => "",  "email" => "", "fecha_alta" => "", "compras" => "",   "fecha_ultima_compra" => "",   "ventas" => "",   "fecha_ultima_venta" => "", "fecha_ultima_puja" => "", "fecha_ultima_cesion" => ""]);
				}
			}
			$listado[] = $user;
		}

		$collection = collect($listado);

		return $collection->downloadExcel("duplicados por nombre V3.xlsx", \Maatwebsite\Excel\Excel::XLSX, true);
	}

	public function crear_articulos()
	{
		$sub = 3102021;
		$ref = 1;
		$lote = FgAsigl0::select("SEC_HCES1,DESCWEB_HCES1, IMPSALHCES_ASIGL0,SUB_ASIGL0,REF_ASIGL0")->JoinFghces1Asigl0()->where("sub_asigl0", $sub)->where("ref_asigl0", $ref)->first();


		$sec = $lote->sec_hces1;
		$emp = Config::get("app.emp");
		$des = $lote->descweb_hces1;
		$model = $lote->descweb_hces1;
		$comb = 0;
		$tprod = 'G';
		$pvp = 2000; //$lote->impsalhces_asigl0;
		$fecha = date('Y-m-d H:i:s');
		$iva = 1;
		$udadxcaja = 1;
		#pongo lo de diferente para que no falle mientras hago las pruebas, ya que hay generado un ejemplo
		$codArt = 	FgArt::select("nvl(max(COD_ART),0) +1 as COD_ART")->where("cod_art", "!=", "3102021-1")->first();
		$refasin_art0 = $lote->sub_asigl0 . "-" . $lote->ref_asigl0;

		$art0fields = array(
			"sec_art0" => $sec,
			"emp_art0" => $emp,
			"des_art0" => $des,
			"comb_art0" => $comb,
			"tprod_art0" => $tprod,
			"model_art0" => $model,
			"pvp_art0" => $pvp,
			"f_alta_art0" => $fecha,
			"tiva_art0" => $iva,
			"udadxcaja_art0" => $udadxcaja,
			"refasin_art0" => $refasin_art0
		);

		FgArt0::create($art0fields);
		#debemos conseguir el id que se ha creado
		$art0 = FgArt0::select("ID_ART0")->where("REFASIN_ART0", $refasin_art0)->orderby("ID_ART0", "desc")->first();
		$artfields = array(
			"sec_art" => $sec,
			"emp_art" => $emp,
			"des_art" => $des,
			"cod_art" => $codArt->cod_art,
			"usra_art" => "ADMIN",
			"pvp_art" => $pvp,
			"tiva_art" => $iva,
			"stk_art" => 'N',
			"newref_art" => $model,
			"idart0_art" => $art0->id_art0
		);

		FgArt::create($artfields);


		#crear pedido
		$cod_cli = "005001";
		$idPedido = FgPedc0::select("nvl(max(NUMFIC_PEDC0),0) +1 as NUMFIC")->first();
		$user = FxCli::select("CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, CIF_CLI")->WHERE("COD_CLI", $cod_cli)->first();
		DB::select(
			"call Crea_pedido.CREA_CAPCELERA(:gemp, :emp, :idPed, :codCli, :cp, :pob, :prov, :telf, :obs, :nif, :codDir, :transport, :payment, to_char(sysdate,'YYYY-MM-DD'))",
			array(

				'gemp'        => Config::get('app.gemp'),
				'emp'        => Config::get('app.emp'),
				'idPed'    => $idPedido->numfic,
				'codCli'        => $cod_cli,
				'cp'     => $user->cp_cli,
				'pob'     => $user->pob_cli,
				'prov'     => $user->pro_cli,
				'telf'     => $user->tel1_cli,
				'nif'     => $user->cif_cli,
				'obs'     =>  "",
				'codDir'	=> '00',
				'transport' => '',
				'payment'	=> ""
			)

		);

		$a = DB::select(
			"call Crea_pedido.CREA_LINIES(:idPed, :emp, :seccio, :codi, :cant, to_char(sysdate,'YYYY-MM-DD'))",
			array(
				'idPed'    => $idPedido->numfic,

				'emp'        => Config::get('app.emp'),
				'seccio'        => $sec,
				'codi'     => $codArt->cod_art,
				'cant'     =>  1
			)
		);
	}

	public function guardar_pedido()
	{

		$cod_cli = Session::get('user.cod');

		#generamos la información a guardar.
		$inf = new \stdClass();
		$articleController = new ArticleController();
		$cartArticles = $articleController->loadArticleCart();
		$units = $articleController->articleCart;

		$importeLotes = 0;

		$paymethod = request("paymethod", "tarjeta");
		$comments = request("comments", "comentarios");
		$articles = [];
		$idPedido = FgPedc0::select("nvl(max(NUMFIC_PEDC0),0) +1 as NUMFIC")->first();

		$user = FxCli::select("CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, CIF_CLI")->WHERE("COD_CLI", $cod_cli)->first();

		ToolsServiceProvider::exit404IfEmpty($user);

		/*(GEMP ,EMP ,IDPED IN NUMBER,CCODLI ,CP ,POB ,PROV ,
                        TELF ,OBS ,NIF ,CODDIRCLI ,TRANSPORT ,PAYMENT
						formato de fecha para base de datos no php
						CALL Crea_pedido.CREA_CAPCELERA ('01','002',111,'005001','08840','pob','prov','telf','OBS','NIF','00','si','tarjeta', to_char(sysdate,'DD-MM-YYYY'));
		*/

		DB::select(
			"call Crea_pedido.CREA_CAPCELERA(:gemp, :emp, :idPed, :codCli, :cp, :pob, :prov, :telf, :obs, :nif, :codDir, :transport, :payment, to_char(sysdate,'YYYY-MM-DD'))",
			array(

				'gemp'        => Config::get('app.gemp'),
				'emp'        => Config::get('app.emp'),
				'idPed'    => $idPedido->numfic,
				'codCli'        => $cod_cli,
				'cp'     => $user->cp_cli,
				'pob'     => $user->pob_cli,
				'prov'     => $user->pro_cli,
				'telf'     => $user->tel1_cli,
				'nif'     => $user->cif_cli,
				'obs'     =>  $comments,
				'codDir'	=> '00',
				'transport' => '',
				'payment'	=> $paymethod
			)

		);

		foreach ($cartArticles as $article) {
			$lotInfo = new \stdClass();
			$lotInfo->idPed = $idPedido->numfic;
			$lotInfo->emp = Config::get("app.emp");
			$lotInfo->codi = $article->cod_art;
			$lotInfo->seccio = $article->sec_art;
			$lotInfo->cant = $units[$article->id_art];
			$articles[] = $lotInfo;

			$a = DB::select(
				"call Crea_pedido.CREA_LINIES(:idPed, :emp, :seccio, :codi, :cant, to_char(sysdate,'YYYY-MM-DD'))",
				array(
					'idPed'    => $idPedido->numfic,

					'emp'        => Config::get('app.emp'),
					'seccio'        => $article->sec_art,
					'codi'     => $article->cod_art,
					'cant'     =>  $units[$article->id_art]
				)
			);
		}
	}


	public function AnsorenaValidateUnion()
	{
		$min = request("min");
		$max = request("max");
		if ($max < 45000) {
			$sql = "update VOLCADO_CLIENTES VC SET ID_PADRE_NIF = (SELECT NVL(MIN(ID_PADRE_NIF), MIN(ID_NUM))ID_PADRE_NIF FROM VOLCADO_CLIENTES where nif=VC.nif and id_padre_duplicados is null GROUP BY nif  HAVING COUNT(NIF) >1 AND COUNT(NIF) <4) WHERE NIF IS NOT NULL  AND ID_NUM > $min AND ID_NUM < $max";
			DB::select($sql, []);
			$min = $max;
			$max = $max + 5000;
			header('Location: ' . Route("prueba") . "?min=$min&max=$max");
			exit;
		}
	}

	public function Ansorena_NIF()
	{
		$min = request("min");
		$max = request("max");
		if ($max < 45000) {
			$sql = "update VOLCADO_CLIENTES VC SET ID_PADRE_NIF = (SELECT NVL(MIN(ID_PADRE_NIF), MIN(ID_NUM))ID_PADRE_NIF FROM VOLCADO_CLIENTES where nif=VC.nif and id_padre_duplicados is null GROUP BY nif  HAVING COUNT(NIF) >1 AND COUNT(NIF) <4) WHERE NIF IS NOT NULL  AND ID_NUM > $min AND ID_NUM < $max";
			DB::select($sql, []);
			$min = $max;
			$max = $max + 5000;
			header('Location: ' . Route("prueba") . "?min=$min&max=$max");
			exit;
		}
	}

	public function Ansorena_duplicados()
	{
		$min = request("min");
		$max = request("max");
		if ($max < 45000) {
			$sql = "update VOLCADO_CLIENTES VC SET ID_PADRE_DUPLICADOS = (SELECT MIN(ID_NUM) ID_NUM FROM VOLCADO_CLIENTES where DUPLICIDAD_CEDENTE_COMPRADOR=VC.DUPLICIDAD_CEDENTE_COMPRADOR GROUP BY DUPLICIDAD_CEDENTE_COMPRADOR  HAVING COUNT(DUPLICIDAD_CEDENTE_COMPRADOR) >1 ) WHERE DUPLICIDAD_CEDENTE_COMPRADOR IS NOT NULL  AND ID_NUM > $min AND ID_NUM < $max";
			DB::select($sql, []);
			$min = $max;
			$max = $max + 5000;
			header('Location: ' . Route("prueba") . "?min=$min&max=$max");
			exit;
		}
	}


	public function Ansorena_telefono()
	{
		$min = request("min");
		$max = request("max");
		if ($max < 45000) {
			$sql = "update VOLCADO_CLIENTES VC SET ID_PADRE_TELEFONO = (SELECT MIN(ID_NUM) FROM VOLCADO_CLIENTES where TELEFONO=VC.TELEFONO AND ID_PADRE_NIF is null AND id_padre_duplicados is null GROUP BY TELEFONO  HAVING COUNT(TELEFONO) >1 AND COUNT(TELEFONO) <4 ) WHERE TELEFONO IS NOT NULL  AND ID_NUM >  $min AND ID_NUM < $max";

			DB::select($sql, []);
			$min = $max;
			$max = $max + 5000;
			header('Location: ' . Route("prueba") . "?min=$min&max=$max");
			exit;
		}
	}

	public function Ansorena_email()
	{
		$min = request("min");
		$max = request("max");
		if ($max < 45000) {
			$sql = "update VOLCADO_CLIENTES VC SET ID_PADRE_EMAIL = (SELECT MIN(ID_NUM) FROM VOLCADO_CLIENTES where EMAIL=VC.EMAIL AND   ID_PADRE_NIF is null AND id_padre_duplicados is null AND ID_PADRE_TELEFONO is null  GROUP BY EMAIL  HAVING COUNT(EMAIL) >1 AND COUNT(EMAIL) <4 ) WHERE EMAIL IS NOT NULL  AND ID_NUM >  $min AND ID_NUM < $max";

			DB::select($sql, []);
			$min = $max;
			$max = $max + 5000;
			header('Location: ' . Route("prueba") . "?min=$min&max=$max");
			exit;
		}
	}

	#test crear pedido
	public function crear_pedido()
	{
		$codCli = '00012';
		$idPedido = 1;
		$car = "a";
		$cp = "08840";
		$pob = "Viladecans";
		$prov = "Barcelona";
		$telf = "902902902";
		$obs = "texto observaciones";
		$nif = "12345678N";
		$codDirCli = "W1";
		$transport = "W1";
		$payment = "W1";
		$a = DB::select(
			"call CREA_PEDIDO.CREA_CAPCELERA(:gemp,:emp,:idPed,:cCodCli,:car,:cp,:pob,:prov,:telf,:obs,:nif, :codDirCli ,:transport ,:payment  )",
			array(
				'gemp'    => Config::get("app.gemp"),
				'emp'    => Config::get("app.emp"),
				'idPed'        => $idPedido,
				'cCodCli'    => $codCli,
				'car'    => $car, #no se si se usa
				'cp'    => $cp, #no se si se usa
				'pob'         => $pob,
				'prov'         => $prov,
				'telf'         => $telf,
				'obs'         => $obs,
				'nif'         => $nif,
				'codDirCli'         => $codDirCli,
				'transport'         => $transport,
				'payment'         => $payment,
			)
		);
	}

	public function soapDuranClient()
	{
		$codCli = '000003';

		$clientController = new ClientController();


		if (request("metodo") == "2") {
			$clientController->updateClient($codCli);
		} elseif (request("metodo") == "3") {
			$clientController->deleteClient($codCli);
		} else {
			$clientController->createClient($codCli);
		}
	}

	public function soapDuranBidW()
	{
		$orderController = new OrderController();
		$orderController->createOrder("000003", "582", "630", 600);
	}

	#NO BORRAR FUNCIONES de REDIRECCIONAMIENTO DURAN

	private function redirectLot($codSub)
	{
		$asigl0 = new FgAsigl0();
		$lots = $asigl0->select("COD_SUB, REF_ASIGL0, DES_SUB, WEBFRIEND_HCES1")
			->JoinFghces1Asigl0()
			->JoinSubastaAsigl0()
			->where('COD_SUB', $codSub)
			->where('REF_ASIGL0', 3)->get();
		foreach ($lots as $lot) {
			//	dd($lot);
			#se pasa a minusculas, se elimina la palabra subasta y se hac3 el SLUG
			$name = Str::slug(str_replace("subasta", "", strtolower($lot->des_sub)));
			$urlRedirect = "subastas-anteriores/$codSub-$name/" . $lot->webfriend_hces1 . ".html";
			echo $urlRedirect;
			die();
			#la url original trae subastas-en-sala antes del texto pero n ose debe usar, se omitira ese texto
			#url final	subastas-anteriores/581-febrero-2020/jose-maria-rossello-cinco-desnudos-115061.html
		}
	}
	/* N ose debería ejecutar mas
	private function urlamigableAuction(){
		$auctions = FgSub::select("COD_SUB, DES_SUB")->wherenotin("cod_sub",array("562","570","572","575","577"))->get();
		foreach($auctions as $auction){
			$desc = strtolower($auction->des_sub);
			$url = $auction->cod_sub."-". Str::slug( str_replace("subasta","", $desc));
			echo "<br> $url";

			DB::table('FGSUB')
			  ->where('COD_SUB', $auction->cod_sub)
              ->where('EMP_SUB', Config::get("app.emp"))
              ->update(['WEBFRIEND_SUB' => $url]);
		}

	}
*/
	private function redirectAuctions()
	{
		$auctions = FgSub::select("COD_SUB, DES_SUB, WEBFRIEND_SUB")->get();
		$redirect["emp_web_redirect_pages"] =  Config::get("app.emp");
		foreach ($auctions as $auction) {
			$urlOld =  "subastas-anteriores/" . $auction->webfriend_sub . ".html";
			$urlNew = "subasta/" . Str::slug($auction->des_sub) . "_" . $auction->cod_sub . "-001";
			$this->createPageRedirect($urlOld, $urlNew, Config::get("app.emp"));
		}
	}

	private function redirectLotsLoad($auction = "7500")
	{
		$lots = FgAsigl0::select("URL, SUB_ASIGL0, REF_ASIGL0, EMP_ASIGL0 ")->join("WEB_REDIRECT_LOTS_LOAD", "WEB_REDIRECT_LOTS_LOAD.ID = IDORIGEN_ASIGL0")->where("sub_asigl0", $auction)->get();

		$redirect["emp_web_redirect_lots"] = Config::get("app.emp");
		foreach ($lots as $lot) {

			$redirect['sub_web_redirect_lots'] = $lot->sub_asigl0;
			$redirect['ref_web_redirect_lots'] = $lot->ref_asigl0;
			$redirect['url_web_redirect_lots'] = str_replace("subastas-en-sala/", "", $lot->url);
			/*
			//borramos para que no haya repetidos, ya que estamos quitando subastas-en-sala y esto puede hacer que se generen dos url iguales.
			DB::table("WEB_REDIRECT_LOTS")->
			where("EMP_WEB_REDIRECT_LOTS", $redirect["emp_web_redirect_lots"])->
			where("SUB_WEB_REDIRECT_LOTS", $redirect['sub_web_redirect_lots'])->
			where("REF_WEB_REDIRECT_LOTS", $redirect['ref_web_redirect_lots'])->
			where("URL_WEB_REDIRECT_LOTS", $redirect['url_web_redirect_lots'])->
			delete();
*/
			DB::table("WEB_REDIRECT_LOTS")->insert($redirect);
		}
	}

	private function redirectLots($auction = null)
	{
		$x = 564;
		while ($x < 587) {
			$auction = $x;

			$fgsub = FgSub::select("COD_SUB, DES_SUB, WEBFRIEND_SUB, 'id_auc_sessions'")->JoinSessionSub();
			if (!empty($auction)) {
				$fgsub = $fgsub->where("COD_SUB", $auction);
			}
			$auctions = $fgsub->get();

			$redirect["emp_web_redirect_lots"] =  Config::get("app.emp");
			foreach ($auctions as $auction) {
				$urlSubasta = "subastas-anteriores/" . $auction->webfriend_sub . "/";
				$redirect['sub_web_redirect_lots'] = $auction->cod_sub;
				$lots = FgAsigl0::JoinFghces1Asigl0()->select("FGHCES1.WEBFRIEND_HCES1,REF_ASIGL0")->where("SUB_ASIGL0", $auction->cod_sub)->get();
				foreach ($lots as $lot) {
					$redirect['ref_web_redirect_lots'] = $lot->ref_asigl0;
					$redirect['url_web_redirect_lots'] = $urlSubasta . $lot->webfriend_hces1 . ".html";


					#primero borramos
					DB::table("WEB_REDIRECT_LOTS")->where("EMP_WEB_REDIRECT_LOTS", $redirect["emp_web_redirect_lots"])->where("SUB_WEB_REDIRECT_LOTS", $redirect['sub_web_redirect_lots'])->where("REF_WEB_REDIRECT_LOTS", $redirect['ref_web_redirect_lots'])->delete();
					#insertamos
					DB::table("WEB_REDIRECT_LOTS")->insert($redirect);
				}
			}

			echo "Auction $x<br>";

			$x++;
		}
	}

	public function redirectCategories()
	{
		$categorias =	FgOrtsec0::get();

		foreach ($categorias as $categoria) {


			$keyOwnCategory = $categoria->key_ortsec0;
			$keyOldCategory =  $categoria->key_ortsec0;

			if ($categoria->lin_ortsec0 == 554) {
				$keyOldCategory =  "pintura/arte-urbano";
			} elseif ($categoria->lin_ortsec0 == 17) {
				$keyOldCategory =  "varios/sellos";
			} elseif ($categoria->lin_ortsec0 == 16) {
				$keyOldCategory =  "varios/orfebreria";
			} elseif ($categoria->lin_ortsec0 == 19) {
				$keyOldCategory =  "varios/porcelana";
			} elseif ($categoria->lin_ortsec0 == 13) {

				$keyOldCategory =  "varios";
			} elseif ($categoria->lin_ortsec0 == 327) {

				$keyOldCategory =  "libros";
			} elseif ($categoria->lin_ortsec0 == 549) {

				$keyOldCategory =  "moda";
			}

			#url categorias
			$urlOnwCategory = "subastas-" . $keyOwnCategory;
			$urlOldCategory = "tienda-online/" . $keyOldCategory . ".html";

			$this->createPageRedirect($urlOldCategory, $urlOnwCategory,  '003');

			$fxsec = new FxSec();
			$secciones = $fxsec->GetSecFromLinFxsec($categoria->lin_ortsec0);
			echo "<br><br><br><a href='https://www.duran-subastas.com/" . $urlOldCategory . "' target='_blank'><strong>" . $categoria->des_ortsec0 . "</strong></a> => ";
			echo "<a href='https://duran.enpreproduccion.com/es/" . $urlOnwCategory . "' target='_blank'><strong>" . $categoria->des_ortsec0 . "</strong></a><br><br>";
			# son categorias nuevas que no tienen url antigua
			if ($categoria->lin_ortsec0 != 554 && $categoria->lin_ortsec0 != 17) {
				foreach ($secciones as $seccion) {
					$urlAnterior = "tienda-online/" . $keyOldCategory . "/" . $seccion["key_sec"] . ".html";
					$urlNueva = "subastas-" . $keyOwnCategory . "/" . $seccion["key_sec"];
					$this->createPageRedirect($urlAnterior, $urlNueva,  '003');
					echo "<a href='https://www.duran-subastas.com/" . $urlAnterior . "' target='_blank'><br> " . $seccion["des_sec"] . "</a> => ";
					echo "<a href='https://duran.enpreproduccion.com/es/" . $urlNueva . "' target='_blank'>" . $seccion["des_sec"] . "</a><br><br>";
				}
			}
		}
	}
	public function createPageRedirect($urlOld, $urlNew, $emp)
	{
		$redirect["url_web_redirect_pages"] = $urlOld; #"subastas-anteriores/" .$auction->webfriend_sub.".html";
		$redirect["page_web_redirect_pages"] =  $urlNew; # "subasta/" . Str::slug($auction->des_sub)."_".$auction->cod_sub."-001";
		$redirect["emp_web_redirect_pages"] =  $emp;
		#primero borramos
		DB::table("WEB_REDIRECT_PAGES")->where("EMP_WEB_REDIRECT_PAGES",  $emp)->where("PAGE_WEB_REDIRECT_PAGES", $redirect["page_web_redirect_pages"])->delete();
		#insertamos
		DB::table("WEB_REDIRECT_PAGES")->insert($redirect);
	}

	/* Función de volcado de datos de las FAQs (de .csv a SQL) */
	/* A las tablas WEB_FAQ y WEB_FAQCAT */
	public function faqs()
	{

		$lang = strtoupper(Config::get('app.locale'));
		$idFatherCat = 0;
		$idActual = 0;
		$idActualFaq = 1;
		$idFatherSubCat = 0;
		if (($gestor = fopen("files/faqs/faqs_" . $lang . ".csv", "r")) !== FALSE) {
			/* El While de carga hasta que dejen de haber datos en el .csv */
			/* La condición del while parsea una linea de celdas del documento */
			while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {

				$numero = count($datos);
				for ($x = 0; $x < $numero; $x++) {

					if ($x == 0 &&  !empty($datos[$x])) {
						$a = $datos[$x];
						$idActual++;
						$idFatherSubCat = $idActual;
						Web_FaqCat::create(["COD_FAQCAT" => $idActual, "PARENT_FAQCAT" => $idFatherCat, "NOMBRE_FAQCAT" => $a, "LANG_FAQCAT" => $lang, "POSITION" => $idActual]);
						break;
					} else if ($x == 1 && !empty($datos[$x])) {
						$b = $datos[$x];
						$idActual++;
						Web_FaqCat::create(["COD_FAQCAT" => $idActual, "PARENT_FAQCAT" => $idFatherSubCat, "NOMBRE_FAQCAT" => $b, "LANG_FAQCAT" => $lang, "POSITION" => $idActual]);
						break;
					} else if ($x == 2 && !empty($datos[$x])) {
						$c = $datos[$x];
						$d = $datos[$x + 1];
						Web_Faq::create(["COD_FAQ" => $idActualFaq, "COD_FAQCAT" => $idActual, "TITULO_FAQ" => $c, "DESC_FAQ" => $d, "LANG_FAQ" => $lang, "POSITION" => $idActualFaq]);
						$idActualFaq++;
					}
				}
			}
			fclose($gestor);
		}
	}

	function exportToExcel()
	{
		$gemp = Config::get('app.gemp');
		$fileName = "TXP";
		$dataForExport = FgAsigl0::select(
			" sub_asigl0  || '-' || ref_asigl0 as id",
			'ANCHO_HCES1 as length',
			'GRUESO_HCES1 as depth',
			'ALTO_HCES1 as height',
			"'cm' as metrics_unit",
			'IMPSALHCES_ASIGL0 as value',
			"'Eur' as currency",
			"'Aquí va photo_url' as photo_url",
			"'Aquí va lot_url' as lot_url",
			"NOM_CLI as owner_name",
			'DIR_ALM as picking_address',
			'CODPAIS_ALM as picking_country',
			'POB_ALM as picking_city',
			'CP_ALM as picking_zipcode',
			'nvl(DESCWEB_HCES1, TITULO_HCES1) as description',
			'REF_ASIGL0 as lot_number',
			'DES_SUB as catalog_name',
			'SUB_ASIGL0 as calalog_reference',
			'"start" as catalog_date',
			'COD_SUB as cod_sub',
			'"id_auc_sessions" as id_auc_sessions',
			'"name" as name',
			'NUM_HCES1 as num_hces1',
			'WEBFRIEND_HCES1 as webfriend_hces1',
			'LIN_HCES1 as lin_hces1'
		)
			->joinFghces1Asigl0()
			->leftJoinAlm()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->leftjoin('FXCLI', "FXCLI.GEMP_CLI = $gemp AND FXCLI.COD_CLI = FGHCES1.PROP_HCES1")
			->where('COD_SUB', '23142')
			->first();

		/* foreach ($dataForExport as $key => $value) {
			$url_friendly = ToolsServiceProvider::url_lot($value->cod_sub, $value->id_auc_sessions, $value->name, $value->lot_number, $value->num_hces1, $value->webfriend_hces1, $value->description);
			$dataForExport[$key]["lot_url"] = $url_friendly;
			$dataForExport[$key]["photo_url"] = ToolsServiceProvider::url_img('lote_medium', $value->num_hces1, $value->lin_hces1);

			//Borrar variables innecesarias debajo del catalog_date
			unset($dataForExport[$key]["cod_sub"]);
			unset($dataForExport[$key]["id_auc_sessions"]);
			unset($dataForExport[$key]["name"]);
			unset($dataForExport[$key]["num_hces1"]);
			unset($dataForExport[$key]["webfriend_hces1"]);
			unset($dataForExport[$key]["lin_hces1"]);
		} */

		dd($dataForExport);
		/* return $this->exportCollectionToExcel($dataForExport, $fileName); */
	}


	function whatsappAction()
	{
		$headers_whatsapp = [
			'Authorization' => 'Bearer EAAGCB52O9oMBANpgu8ZBQqP5oijPZAQOyw8d9tuxZBF8wZBn3a0bwfqSRaNJ9FSULbX8JgxR5S1It176hJuJ5TX92YB7fqGnoG6KaKDo5sIflmjJXCQmYetFHryvp048TV2FzVydQxQ8ouB6SSxz2KRmSKHBw2mPFZBtQFbzcmz4MuRuKUs8S',
			'Content-Type' => 'application/json',
		];

		try {

			$response = $this->sendTemplateWhatsapp($headers_whatsapp);

			dd($response->getBody()->getContents());
		} catch (\Throwable $th) {
			Log::info($th->getMessage());

			dd($th->getMessage());
		}
	}

	function sendTemplateWhatsapp($headers)
	{

		$var1 = Request::get('var1');
		$var2 = Request::get('var2');
		$var3 = Request::get('var3');
		$var4 = Request::get('var4');

		$client = new Client();

		$response = $client->request('POST', 'https://graph.facebook.com/v16.0/113096834998329/messages', [
			'headers' => [
				'Authorization' => 'Bearer EAAGCB52O9oMBANpgu8ZBQqP5oijPZAQOyw8d9tuxZBF8wZBn3a0bwfqSRaNJ9FSULbX8JgxR5S1It176hJuJ5TX92YB7fqGnoG6KaKDo5sIflmjJXCQmYetFHryvp048TV2FzVydQxQ8ouB6SSxz2KRmSKHBw2mPFZBtQFbzcmz4MuRuKUs8S',
				'Content-Type' => 'application/json',
			],
			'json' => [
				'messaging_product' => 'whatsapp',
				'to' => '34640637357',
				'type' => "template",
				'template' => [
					'name' => 'sample_recient_auction',
					'language' => [
						'code' => 'es_ES'
					],
					'components' => [
						[
							'type' => 'header',
							'parameters' => [
								[
									'type' => 'text',
									'text' => $var1
								]
							],
						],
						[
							'type' => 'body',
							'parameters' => [
								/* 								[
									'type' => 'text',
									'image' => $var1
								], */
								[
									'type' => 'text',
									'text' => $var2
								],
								[
									'type' => 'text',
									'text' => $var3
								]
							],
						]
					],
				]
			]
		]);

		return $response;
	}

	function createTemplate4Whatsapp($headers)
	{

		$var1 = Request::get('var1');
		$var2 = Request::get('var2');
		$var3 = Request::get('var3');
		$var4 = Request::get('var4');

		$client = new Client();

		$text_template = '¿Estás preparado para la emoción de nuestra última subasta?
Descubre la increíble colección de lotes en la subasta "{{1}}". Te espera una amplia selección de productos y ofertas imperdibles.
¡No te pierdas la oportunidad de participar en vivo el próximo {{2}} y disfruta de la emocionante experiencia de pujar por tus favoritos.

Recuerda visitar nuestra plataforma y sumergirte en la adrenalina de la puja en tiempo real.

¡Te esperamos con entusiasmo!';

		$response = $client->request('POST', 'https://graph.facebook.com/v17.0/110347208640772/message_templates', [
			'headers' => $headers,
			'json' => [
				"name" => "sample_recient_auction",
				"language" => "es_ES",
				"category" => "MARKETING",
				"components" => [
					[
						"type" => "HEADER",
						"format" => "TEXT",
						"text" => "¡Hola {{1}}!",
						"example" => [
							"header_text" => [
								"Jorge Alameda"
							]
						]
					],
					[
						"type" => "BODY",
						"text" => $text_template,
						"example" => [
							"body_text" => [
								[
									"Alfombras y tapices del siglo primero",
									"13 de octubre"
								]
							]
						]
					],
					[
						"type" => "FOOTER",
						"text" => '¿No te interesa? Toca "Detener promociones"'
					],
					[
						"type" => "BUTTONS",
						"buttons" => [
							[
								"type" => "QUICK_REPLY",
								"text" => "Detener promociones",
							]
						]
					]
				]
			]
		]);

		dump($response->getBody()->getContents());

		/* 'recipient_type' => 'individual', */

		/* 'link' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3', */

		return $response;
	}
}
