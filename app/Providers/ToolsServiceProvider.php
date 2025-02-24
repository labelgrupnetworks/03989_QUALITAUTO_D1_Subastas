<?php

namespace App\Providers;

use App\libs\CacheLib;
use App\libs\ImageGenerate;
use App\Models\Enterprise;
use App\Models\Facturas;
use App\Models\Payments;
use App\Models\Subasta;
use App\Models\V5\FxCli;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Category_Blog_Lang;
use App\Providers\RoutingServiceProvider as Routing;
use DOMDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ToolsServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {}

	public function register() {}

	public static function linguisticSearch()
	{
		//permitir busqueda lingüistica,  que n otenga en cuenta acentos ni mayusculas
		$sql = "alter session set nls_comp=linguistic";
		DB::select($sql);
		$sql = "alter session set nls_sort=binary_ai";
		DB::select($sql);
	}
	public static function normalSearch()
	{

		//permitir busqueda lingüistica,  que n otenga en cuenta acentos ni mayusculas
		$sql = "alter session set nls_comp=binary";
		DB::select($sql);
		$sql = "alter session set nls_sort=binary_ai";
		DB::select($sql);
	}

	public static function conservationCurrency($num_hces, $lin_hces, $campos = array())
	{

		if (empty($campos)) {
			return null;
		}

		$select = '';

		foreach ($campos as $valor) {
			$select = $select . 'NVL(otv_lang."' . $valor . '_lang",  otv."' . $valor . '") "' . $valor . '",';
		}

		$select = trim($select, ',');


		$sql = " Select $select from \"object_types_values\" otv LEFT JOIN \"object_types_values_lang\" otv_lang on (otv_lang.\"transfer_sheet_number_lang\" = :num_hces and otv_lang.\"company_lang\" = :emp and otv_lang.\"transfer_sheet_line_lang\" = :lin_hces and otv_lang.\"lang_object_types_values_lang\" = :lang)
             where \"company\" = :emp and \"transfer_sheet_number\" = :num_hces and \"transfer_sheet_line\" = :lin_hces ";



		$params = array(
			'emp' => Config::get('app.emp'),
			'num_hces' => $num_hces,
			'lin_hces' => $lin_hces,
			'lang'     => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
		);


		$consulta = DB::select($sql, $params);
		if (empty($consulta)) {
			return null;
		}

		return head($consulta);
	}

	public static function friendlyDesc($str)
	{

		$str = preg_replace('/\\\b/', ' ', $str);
		$str = preg_replace('/\\\n/', '<br >', $str);
		$str = preg_replace('/\\\s/', ' ', $str);
		$str = str_replace("
", "<br>", $str);

		return $str;
	}

	public static function moneyFormat($qtty, $currency = FALSE, $decimal = 0, $position = 'R', $decimalSeparator = ",", $thousandSeparator = ".")
	{

		if (!is_numeric($qtty)) {
			return FALSE;
		}
		/*
                if (strpos($currency, ',') === false) {
                    $format = $qtty;
                }else{
                    $format = number_format($qtty, $decimal, ',', '.');
                }
                */
		if (Config::get("app.decimalSeparator")) {
			$decimalSeparator = Config::get("app.decimalSeparator");
		}
		if (Config::get("app.thousandSeparator")) {
			$thousandSeparator = Config::get("app.thousandSeparator");
		}

		$format = number_format($qtty, $decimal, $decimalSeparator, $thousandSeparator);


		if (!empty($currency)) {
			if ($position == 'R') {
				$format .= " " . $currency;
			} else {
				$format = $currency . " " . $format;
			}
		}

		return $format;
	}

	public static function getCurrency($str_currency)
	{

		if (empty($str_currency)) {
			return FALSE;
		}

		switch ($str_currency) {
			case 'EUR':
				$curr = '€';
				break;

			case 'COP':
				$curr = 'COP ';
				break;

			case 'US$':
				$curr = 'US$';
				break;

			case 'PAB':
				$curr = 'B/. ';
				break;

			default:
				$curr = '€';
				break;
		}

		return $curr;
	}

	public static function euroDate($fecha)
	{
		if (!$fecha) {
			return "";
		}
		$t = strtotime($fecha);
		return date("d/m/Y H:i:s", $t);
	}

	public static function getOffset($page, $itemsPerPage)
	{
		$result = FALSE;
		if (empty($page) or $page == 1) {
			$start  = 1;
			$offset = 1;
		} else {
			$start  = $itemsPerPage;

			if (is_numeric($page) && $page > 2) {
				$start = (($itemsPerPage * $page) + 1) - $itemsPerPage;
			} else {
				$start = ($itemsPerPage) + 1;
			}

			$offset = 1;
		}

		if ($page != 'all') {
			$sql = " WHERE rn BETWEEN " . $start . " AND " . (($start) + ($itemsPerPage - $offset));
			$result = $sql;
		}

		return $result;
	}

	public static function formatDate($fecha, $hora)
	{
		$date = explode(' ', $fecha);

		if (!empty($date[0]) && $hora != null) {
			$date = $date[0] . ' ' . $hora;
		} else {
			$date = $fecha;
		}

		return self::euroDate($date);
	}

	public static function encodeStr($str)
	{
		$str .= sha1('-' . substr($str, 0, 2) . '- Tsujimah');
		$str = urlencode(sha1(md5('label_*' . $str . '_*')));
		return $str;
	}

	public static function printFilterOrderBy()
	{
		$options = Config::get('app.filter_total_shown_options');

		$contents = view('front::includes.filters.order_by', ['options' => $options])->render();
		echo $contents;
	}

	# Obtiene y muestra los filtros para la lista de lotes de la subasta
	public static function printFilters($cod_sub, $id_auc_sessions)
	{
		$options = array();
		$sub = new Subasta();
		$sub->cod = $cod_sub;
		$sub->id_auc_sessions = $id_auc_sessions;
		$cat = Route::current()->parameter('cat');

		# Familias
		//FER Q LES FAMILIES ES VEGIN AFECTADES SEGONS ELS MATERIALS SELECCIONATS. (FILTRE POSTERIOR)
		if (!empty($cat)) {
			# Filtra las familias segun la categoria de la subasta seleccionada.
			$sub->where_filter .= " AND sec1.LIN_ORTSEC1 = " . $cat;
		}
		$options['families'] = $sub->getFamilies();

		# Materiales
		$selected_mats = FacadeRequest::input('mat');
		$selected_families = FacadeRequest::input('fam');
		$available_mats = array(1, 2, 3, 4, 5);

		# Obtiene los filtros por material disponibles.
		foreach ($available_mats as $val) {
			$s_key = false;
			$sub->where_filter = '';
			$cont = 0;

			# Filtra el resultado de cada material según el resto de materiales seleccionados
			if (!empty($selected_mats)) {
				foreach ($selected_mats as $k => $smat) {
					if (empty($smat)) {
						continue;
					}

					if ($k != $val) {
						$expl = explode('.', $smat);
						$sec = $expl[0];
						$num = $expl[1];

						if ($cont > 0) {
							$op = ' AND ';
						} else {
							$op = ' AND ';
						}

						$sub->where_filter .= $op . "fghces1sr.APAR" . $k . "_HCES1SR = " . $num;
						$cont++;
					}
				}
			}

			# Segun la categoria seleccionada
			if (!empty($cat)) {
				$sub->where_filter .= " AND fgortsec1.LIN_ORTSEC1 = " . $cat;
			}

			# Segun la familia seleccionada muestra unos filtros u otros.
			if (!empty($selected_families)) {
				$cont1 = 0;
				foreach ($selected_families as $fami => $value) {
					if ($cont1 > 0) {
						$op1 = " OR ";
					} else {
						$op1 = " AND (";
					}
					$sub->where_filter .= $op1 . " fghces1.SEC_HCES1 = '" . $fami . "'";
					$cont1++;
				}
				$sub->where_filter .= ')';
			}

			$options['materials'][$val] = $sub->getMaterials($val);
		}

		# Imprime el contenido de la vista.
		$contents = view('front::includes.filters.main', ['options' => $options])->render();
		echo $contents;
	}


	# Función a través de la que tienen que pasar todos los envíos de email.
	public static function sendMail($template, $emailOptions)
	{

		try {
			if (empty($emailOptions['UTM'])) {
				$emailOptions['UTM'] = ' ';
			}
			//No envia emails por que así lo indica la configuración
			if (!Config::get('app.enable_emails')) {
				return False;
			}
			//si esta configurada la opcción envio de copias y existe el mailbox, envia una copia a ese mailbox
			if (Config::get('app.copies_emails') && !empty(Config::get('app.copies_emails_mailbox')) && !env('APP_DEBUG') && $emailOptions['to'] != Config::get('app.debug_to_email')) {

				$emailOptions['copia_to'] = Config::get('app.copies_emails_mailbox');
				Mail::send('emails.' . $template, ['emailOptions' => $emailOptions], function ($m) use ($emailOptions) {
					$m->from(Config::get('app.from_email'), Config::get('app.name'));
					$m->to($emailOptions['copia_to'], $emailOptions['user'])->subject($emailOptions['subject']);
				});
			}

			$from = Config::get('app.from_email', Config::get('mail.from.address'));
			if (!empty($emailOptions['signaturit'])) {
				$from = Config::get('app.email_signaturit');
			}

			if (env('APP_DEBUG')) {
				$emailOptions['to'] = !empty(env('MAIL_TO')) ? env('MAIL_TO') : Config::get('app.debug_to_email');
				$from = env('MAIL_FROM_ADDRESS') ?? Config::get('app.from_email');
			} else {
				if (strpos($emailOptions['to'], ';') > 0) {
					$explode_email = explode(";", $emailOptions['to']);
					if (!empty($explode_email[0])) {
						$emailOptions['to'] = trim($explode_email[0]);
					}
				}
			}

			$emailOptions['user'] = ucwords(mb_strtolower($emailOptions['user']));
			Mail::send('emails.' . $template, ['emailOptions' => $emailOptions], function ($m) use ($emailOptions, $from) {
				$m->from($from, Config::get('app.name'));
				$m->to($emailOptions['to'], $emailOptions['user'])->subject($emailOptions['subject']);
			});
			return true;
		} catch (\Exception $e) {

			Log::emergency('Error Email: <br>' . $e);

			return false;
		}
	}

	# Función para mostrar el select menú de los idiomas
	public static function showLanguageSelector()
	{

		if (Config::get('app.enable_language_selector')) {
			# Array de idiomas disponibles
			$idiomas = Config::get('app.locales');

			# Imprime el contenido de la vista.
			$contents = view('front::includes.languages', ['idiomas' => $idiomas])->render();
			echo $contents;
		}
	}

	public static function getOtherLanguages()
	{
		return array_diff_key(config('app.locales'), [config('app.locale') => 1]);
	}

	public static function slider($key, $html)
	{
		$data['key'] = $key;
		$data['html'] = $html;
		$contents = view('front::content.slider', ['data' => $data])->render();
		return ($contents);
	}

	public static function down_timer($timer, $type = 'large')
	{

		$fecha = strtotime($timer) - strtotime("now");

		if ($type == 'large') {
			$date_time = " %Mm %Ss";
			if ($fecha > 86400) {
				$date_time = '%Dd %Hh' . $date_time;
			} elseif ($fecha > 3600) {
				$date_time = '%Hh' . $date_time;
			}
		} elseif ($type == 'complete') {
			$stringSeconds = "Segundos";
			$stringMinutes = "Minutos";
			$stringHours = "Hrs";
			$stringDays = "Días";

			$secondsElement = "<p class='timer-seconds'><span>%S</span><span>$stringSeconds</span></p>";
			$minutesElement = "<p class='timer-minutes'><span>%M</span><span>$stringMinutes</span></p>";
			$hoursElement = "<p class='timer-hours'><span>%H</span><span>$stringHours</span></p>";
			$daysElement = "<p class='timer-days'><span>%D</span><span>$stringDays</span></p>";

			$date_time = "$minutesElement $secondsElement";
			if ($fecha > 86400) {
				$date_time = "$daysElement $hoursElement $date_time";
			} elseif ($fecha > 3600) {
				$date_time = "$hoursElement $date_time";
			}
		} elseif ($type == 'small') {
			$date_time = " %Ss";
			if ($fecha > 86400) {
				$date_time = '%Dd %Hh';
			} elseif ($fecha > 3600) {
				$date_time = '%Hh  %Mm';
			} else {
				$date_time = '%Mm' . $date_time;
			}
		}


		return $date_time;
	}

	public static function querylog()
	{
		if (Config::get('app.debug') || (!empty($_GET) && !empty($_GET['querylog']) && $_GET['querylog'] == 'active_log')) {

			//si miramos desde debug es por que estamos abajo y va más lento, por eso el limite es mayor
			if (Config::get('app.debug') && Config::get('app.env') == 'local') {
				$limit = 1000;
			} else {
				$limit = 10;
			}
			$time = 0;
			$count = 0;

			echo "<div class='query-log'>";
			foreach (DB::getQueryLog() as $query) {
				$count++;
				$color = "";
				$time += $query['time'];
				if ($query['time'] > $limit) {
					$color = "color: red;";
				} elseif ($query['time'] > ($limit / 2)) {
					$color = "color: orange;";
				} elseif ($query['time'] > ($limit / 4)) {
					$color = "color: blue;";
				}
				echo "<div style='border:1px solid grey; margin:30px;padding: 10px;word-break: break-all; $color'> (" . $query['time'] . ")<br> " . nl2br($query['query']) . "</div> ";
			}
			echo "<h1> Total: $time , TotalQuerys: $count</h1>";
			echo "</div>";
		}
	}


	public static function getFilters($cod_sub)
	{
		$sql = "select col_subfw from FGSUBFW where emp_subfw = '" . Config::get('app.emp') . "' and sub_subfw= :cod_sub ";
		$columns_db = DB::select($sql, array("cod_sub", $cod_sub));
		$columns = "";
		$coma = "";
		//array que generará los selectores
		$selectors = array();
		foreach ($columns_db as $column) {
			$columns .= $coma . '  otv."' . $column->col_subfw  . '" ';
			$coma = ',';
			$selectors[$column->col_subfw] = array();
		}

		$sql = "select $columns from fghces1 hces1
            join \"object_types_values\" otv on ( otv.\"company\" = hces1.emp_hces1 and otv.\"transfer_sheet_number\" = hces1.num_hces1 and otv.\"transfer_sheet_line\" = hces1.lin_hces1)

             where hces1.emp_hces1= '" . Config::get('app.emp') . "' and  sub_hces1 = :cod_sub
            ";
		$object_types_values = DB::select($sql, array("cod_sub", $cod_sub));
		foreach ($object_types_values as $values) {
			foreach ($selectors as $key => $selector) {
				$val = trim($values->{$key});
				if (!empty($val)) {
					$selectors[$key][$val] = $val;
				}
			}
		}
		//ordenamos los filtros
		foreach ($selectors as $key => $selector) {
			asort($selectors[$key]);
		}

		return $selectors;
	}

	public static function get_month_lang($key_month, $month_list)
	{
		$months = array();
		$key_month = trim(strtolower($key_month));
		$months_lang = explode(",", $month_list);

		foreach ($months_lang as $value_months) {
			$months_temp = explode(":", $value_months);
			$months_small = trim(strtolower($months_temp[0]));
			if ($months_small == $key_month) {
				$monthName = trim(str_replace("'", "", $months_temp[1]));
				$monthName = trim(str_replace('"', "", $monthName));
				return $monthName;
			}
		}
		return '';
	}

	public static function countAdjPagar()
	{

		$count_lots_adj = 0;
		if (Session::has('user')) {
			$user = new \App\Models\User();
			$user->cod_cli = Session::get('user.cod');
			$user->itemsPerPage = 'count';
			$count_lots_adj_temp = $user->getAdjudicacionesPagar('N');
			if (!empty($count_lots_adj_temp)) {
				$count_lots_adj = $count_lots_adj_temp;
			}
		}
		return $count_lots_adj;
	}

	public static function countFactPagar()
	{

		$count_fact = 0;
		if (Session::has('user')) {
			$facturas = new Facturas();
			$facturas->cod_cli = Session::get('user.cod');
			//Sacamos facturas pendiente de pago
			$pendientes = $facturas->pending_bills();
			if (!empty($pendientes)) {
				$count_fact = count($pendientes);
			}
		}
		return $count_fact;
	}

	public static function PaisesEUR()
	{

		return array(
			'DE',
			'AT',
			'BE',
			'BG',
			'CY',
			'HR',
			'DK',
			'SK',
			'SI',
			'ES',
			'EE',
			'FI',
			'FR',
			'GR',
			'IE',
			'IT',
			'LV',
			'HU',
			'LT',
			'LU',
			'MT',
			'NL',
			'PL',
			'PT',
			'CZ',
			'RO',
			'SE'
		);
	}

	#importe con iva para comunitarios, los precios en base de datos ya incluyen el iva
	public static function PriceWithTaxForEuropean($imp, $codCli)
	{
		#si no está logeado devolvemos el precio como en base de datos, con iva
		if (empty($codCli)) {
			return $imp;
		}
		$payments = new Payments();
		#recogemos el iva actual
		$iva = $payments->getIVA(date('Y-m-d H:i:s'), '01');

		$tax = 0;
		if (count($iva) > 0) {
			$tax = $iva[0]->iva_iva / 100;
		}

		$user = FxCli::select("CODPAIS_CLI")->where("COD_CLI", $codCli)->first();

		# si no encontramos usuario , o si lo encontramos y es Europeo devolvemos el precio de base de datos que lleva el iva
		if (empty($user) ||  in_array($user->codpais_cli, ToolsServiceProvider::PaisesEUR())) {
			return  $imp;
		} else {
			#si es usuario extracomunitario se le descuenta el iva, es necesario redondearlo para no arrastrar decimales
			return  round($imp / (1 + $tax), 2);
		}
	}

	#si es europeo devolverá el iva y si no lo es devolverá 0
	public static function TaxForEuropean($codCli)
	{
		$payments = new Payments();
		#recogemos el iva actual
		$iva = $payments->getIVA(date('Y-m-d H:i:s'), '01');

		$tax = 0;
		if (count($iva) > 0) {
			$tax = $iva[0]->iva_iva / 100;
		}
		# si no hay usuario se aplica el iva
		if (empty($codCli)) {
			return $tax;
		} else {
			$user = FxCli::select("CODPAIS_CLI")->where("COD_CLI", $codCli)->first();
			if (empty($user) ||  in_array($user->codpais_cli, ToolsServiceProvider::PaisesEUR())) {
				return   $tax;
			} else {
				return 0;
			}
		}
	}
	/*
	public static function PriceWithTaxForEuropean($imp,$codCli, $taxForLoged = true){

		$iva = DB::select( "select iva_iva from fsiva where dfec_iva <= :time and hfec_iva >= :time and cod_iva = :cod",
						array(
							'time'       => date('Y-m-d H:i:s'),
							'cod'   => '01'
							)
					);
		$tax =0;
		if(count($iva)> 0){
			$tax = $iva[0]->iva_iva/100;
		}

		#si no esta logeado mostramos precio con iva o no segun variable
		if (empty($codCli)) {
			if($taxForLoged){
				return  (1 + $tax) * $imp;
			}else{
				return $imp;
			}


		}else{
			$user = FxCli::select("CODPAIS_CLI")->where("COD_CLI", $codCli)->first();

			# si no encontramos usuario , o si lo encontramos y es Europeo
			if(empty($user) ||  in_array($user->codpais_cli,ToolsServiceProvider::PaisesEUR() )){
					return  (1 + $tax) * $imp;

			}
		}
		#si no se suma el iva se devuelve el precio como estaba
		return $imp;


	}
*/
	public static function NamePais($countri)
	{
		$enterprice = new Enterprise();
		$keyname_cache = "get_countries" . Config::get('app.theme') . "_" . Config::get('app.emp');
		$paises = CacheLib::getCache($keyname_cache);
		if ($paises === false) {
			$paises = $enterprice->getCountries();
			CacheLib::putCache($keyname_cache, $paises);
		}

		$countries = array();
		foreach ($paises ?? [] as $pais) {
			$countries[$pais->cod_paises] = mb_convert_encoding(mb_convert_case($pais->des_paises, MB_CASE_TITLE), "UTF-8");
		}
		if (!empty($countries[$countri])) {
			return $countries[$countri];
		} else {
			return;
		}
	}

	public static function url_lot($cod_sub, $id_session, $des_sub, $ref, $num_hces, $friendly = "", $title = "")
	{
		$webfriend = !empty($friendly) ? $friendly :  Str::slug(strip_tags(trim($title)));

		if (Config::get("app.newUrlLot")) {
			//$url = Route("lote",["texto"=> $webfriend,"ref" => $ref, "cod" => $cod_sub]);
			$url = Config::get('app.url') . Routing::translateSeo('subasta-lote') . $webfriend . '/' . $cod_sub . '-' . $ref;
		} else {
			$url = Config::get('app.url') . Routing::translateSeo('lote') . $cod_sub . "-" . $id_session . '-' . $id_session . "/" . $ref . '-' . $num_hces . '-' . $webfriend;
		}

		return $url;
	}

	public static function url_lot_to_js($cod_sub, $id_session, $ref, $num_hces)
	{
		$appUrl = Config::get('app.url');

		return Config::get("app.newUrlLot")
			? $appUrl . RoutingServiceProvider::translateSeo('subasta-lote') . "a/$cod_sub-$ref"
			: $appUrl . RoutingServiceProvider::translateSeo('lote') . "$cod_sub-$id_session-$id_session/:ref-$num_hces";
	}

	public static function url_auction($cod_sub, $name, $id_session, $ref_session = '001')
	{
		if (!empty(Config::get("app.gridLots")) && Config::get("app.gridLots") == "new") {
			return route("urlAuction", ["texto" => Str::slug($name), "cod" => $cod_sub, "session" => $ref_session]);
		} else {
			return   Config::get('app.url') . Routing::translateSeo('subasta') . $cod_sub . "-" . Str::slug($name) . "-" . $id_session;
		}
	}

	public static function url_info_auction($cod_sub, $name)
	{
		return   Config::get('app.url') . Routing::translateSeo('info-subasta') . $cod_sub . "-" . Str::slug($name);
	}

	public static function url_indice_auction($cod_sub, $name, $id_session)
	{
		return   Config::get('app.url') . Routing::translateSeo('indice-subasta') . $cod_sub . "-" . Str::slug($name) . "-" . $id_session;
	}

	public static function url_real_time_auction($cod_sub, $name, $id_session)
	{
		return   Config::get('app.url') . Routing::translateSeo('api/subasta') . $cod_sub . "-" . Str::slug($name) . "-" . $id_session;
	}

	public static function url_categorys($category)
	{
		return   Config::get('app.url') . Routing::translateSeo('subastas') . $category;
	}

	public static function url_exposicion($des_sub, $cod_sub, $reference = '001')
	{
		return   Route("exposicion", ['texto' => Str::slug($des_sub), 'cod' => $cod_sub, 'reference' => $reference]);
	}

	public static function  images_size()
	{
		//si no lo hemos guardado aun
		if (empty(Config::get('app.images_size'))) {
			/* tamaño imagenes */
			$sql = "select * from WEB_IMAGES_SIZE WHERE ID_EMP = :emp";
			$params = array('emp' => Config::get('app.main_emp'));
			$sizes_DB =  DB::select($sql, $params);
			$sizes = array();

			//$sizes_DB = \DB::select($sql);
			foreach ($sizes_DB as $size_DB) {
				$sizes[$size_DB->name_web_images_size] = $size_DB->size_web_images_size;
			}
			Config::set('app.images_size', $sizes);
		}

		return Config::get('app.images_size');
	}
	public static function url_img_friendly($size, $numhces, $linhces, $img_num = 0, $textFriendly = null)
	{
		return Config::get('app.url') . "/img_load/" . $size . "/$numhces/$linhces/$img_num/$textFriendly.jpg";
	}

	public static function url_img($size, $numhces, $linhces, $img_num = null, $force_old = null)
	{
		$emp = Config::get('app.emp');
		$url = Config::get('app.url');

		$path_img_num = '';
		if (!empty($img_num)) {
			$path_img_num = "_" . sprintf("%02d", $img_num);
		}
		$file = "img/$emp/$numhces/$emp-$numhces-$linhces{$path_img_num}.jpg";

		#podemso forzar a generar imagenes con el force_old=1, en zonas como la ficha del lote
		$loadOld = !empty($force_old) ? $force_old : Config::get('app.loadImage', 0);

		#codigo antiguo
		if ($loadOld) {
			$img_file = "$url/img/load/$size/$emp-$numhces-$linhces{$path_img_num}.jpg";
			return $img_file . self::date_modification($file);
		}

		/* revisar esto, ya que se quito pero sin el no puedo cargar imagnes
		de ansorena galeria */
		if ($size === "real") {
			return self::lotRealImage($numhces, $linhces, $img_num);
		}

		$images_size = self::images_size();
		$sizeImage = !empty($images_size[$size]) ? $images_size[$size] : $size;

		$image_to_load = "img/thumbs/$sizeImage/$emp/$numhces/$emp-$numhces-$linhces{$path_img_num}";

		//finalmente creo que a nadie se le generan en webp, se puede quitar
		$extension = 'webp';
		if (!file_exists("$image_to_load.$extension")) {
			$extension = 'jpg';
		}

		$image_to_load = "$image_to_load.$extension";

		if (self::isImageValid($image_to_load)) {
			return "$url/$image_to_load" . self::date_modification($file);
		}

		//si no existe la imagen, generamos las miniaturas (solo si esta activado en la configuración)
		if (Config::get('app.generate_image_when_not_found', false)) {
			(new ImageGenerate)->imageLot($numhces, $linhces, $img_num, $sizeImage);
		}

		//si sigue sin existir la imagen, cargamos la imagen por defecto
		if (!self::isImageValid($image_to_load)) {
			$image_to_load = self::getPlaceholderImage($size);
		}

		return "$url/$image_to_load" . self::date_modification($file);
	}

	public static function serverLotUrlImg($url, $sizeImage, $numhces, $linhces)
	{
		$emp = Config::get('app.emp');
		return "https://$url/img/thumbs/$sizeImage/$emp/$numhces/$emp-$numhces-$linhces.jpg";
	}

	public static function url_img_auction($size, $cod_sub)
	{
		//Nuevo metodo para cargar imagenes de subastas sin realizar el load.
		return self::auctionImage($cod_sub, $size, null);

		/* $img_file=Config::get('app.url') . "/img/load/$size/AUCTION_" . Config::get('app.emp') . "_$cod_sub.jpg";
		$file="img/AUCTION_" . Config::get('app.emp') . "_$cod_sub.jpg";
		return $img_file.self::date_modification($file); */
	}

	public static function url_img_session($size, $cod_sub, $reference)
	{
		//Nuevo metodo para cargar imagenes de subastas sin realizar el load.
		return self::auctionImage($cod_sub, $size, $reference);

		/* $file = "img/SESSION_" . Config::get('app.emp') . "_" . $cod_sub . "_" . $reference . ".jpg";
		$img_file = Config::get('app.url') . "/img/load/$size/SESSION_" . Config::get('app.emp') . "_" . $cod_sub . "_" . $reference . ".jpg";
		return $img_file.self::date_modification($file); */
	}

	public static function auctionImage($cod_sub, $size = null, $reference = null)
	{
		$url = Config::get('app.url');

		$imagePath = self::buildAuctionImagePath($size, $cod_sub, $reference);
		$image_to_load = self::getValidAuctionImage($imagePath, $size, $cod_sub, $reference);

		return "$url/$image_to_load" . self::date_modification($image_to_load);
	}

	private static function auctionImageName($cod_sub, $reference)
	{
		$emp = Config::get('app.emp');
		if (empty($reference)) {
			return "AUCTION_{$emp}_{$cod_sub}";
		}
		return "SESSION_{$emp}_{$cod_sub}_{$reference}";
	}

	private static function buildAuctionImagePath($size, $cod_sub, $reference)
	{
		$imageName = self::auctionImageName($cod_sub, $reference);

		if (!$size || $size === 'real') {
			return "img/{$imageName}.*";
		}

		$images_size = self::images_size();
		if (!isset($images_size[$size])) {
			return "img/{$imageName}.*";
		}

		return "img/thumbs/{$images_size[$size]}/{$imageName}.*";
	}

	private static function getValidAuctionImage($imagePath, $size, $cod_sub, $reference)
	{
		$globImage = glob($imagePath);
		$image_to_load = $globImage ? $globImage[0] : null;

		if (!self::isImageValid($image_to_load)) {
			//Si no existe la miniatura la intentamos generar
			self::generateThumbnail($size, $cod_sub, $reference);

			$globImage = glob($imagePath);
			$image_to_load = $globImage ? $globImage[0] : null;

			//Si no se ha podido generar la miniatura o no existe la imagen original, cargamos la imagen por defecto
			if (!self::isImageValid($image_to_load)) {
				$image_to_load = self::getPlaceholderImage($size);
			}
		}

		return $image_to_load;
	}

	private static function isImageValid($image)
	{
		return file_exists($image) && filesize($image) >= 500;
	}

	private static function generateThumbnail($size, $cod_sub, $reference)
	{
		$originalImage = self::auctionImageName($cod_sub, $reference) . '.jpg';
		(new ImageGenerate)->resize_img($size, $originalImage, Config::get('app.theme'), true);
	}

	private static function getPlaceholderImage($size)
	{
		$theme = Config::get('app.theme');
		$pathNoPhoto = "themes/{$theme}/img/items/no_photo";
		return file_exists("{$pathNoPhoto}_{$size}.png") ? "{$pathNoPhoto}_{$size}.png" : "{$pathNoPhoto}.png";
	}

	public static function lotRealImage($numhces, $linhces, $img_num = null)
	{
		$emp = Config::get('app.emp');
		$nameFile = "$emp-$numhces-$linhces";
		$webPath = "/img/$emp/$numhces/";

		$path = "/img/$emp/$numhces/$nameFile";
		if ($img_num) {
			$path .= "_" . sprintf("%02d", $img_num);
		}

		$image = glob(public_path($path) . ".*");

		if ($image) {
			$imageFile = $image[0];
			return $webPath . basename($imageFile) . self::date_modification($imageFile);
		} else {
			return "/themes/" . Config::get('app.theme') . "/img/items/no_photo.png";
		}
	}

	public static function date_modification($img_file)
	{

		if (file_exists($img_file)) {
			return "?a=" . filemtime($img_file);
		}
	}

	/**
	 * @deprecated
	 */
	public static function url_img_validation($img_file)
	{

		if (file_exists($img_file)) {
			$fechaUltimaModificacion = filemtime($img_file);
			$img_date_mod = $img_file . "?a=" . $fechaUltimaModificacion;
			return $img_date_mod;
		}
		return $img_file;
	}

	public static function url_pdf($cod_sub, $reference, $archive)
	{
		$url = "files/" . Config::get('app.emp') . '_' . $cod_sub . '_' . $reference . '_' . $archive . '_' . App::getLocale() . '.pdf';

		//si no existe pdf en el idioma lo mostramos en ingles.
		if (!file_exists($url)) {
			$url = 'files/' . Config::get('app.emp') . '_' . $cod_sub . '_' . $reference . '_' . $archive . '_en.pdf';
			//si no existe es que no podemso mostrar el archivo y devolvemos vacio
			if (!file_exists($url)) {
				return "";
			}
		}
		return '/' . $url . '?a=' . rand();
	}

	public static function generateUrlGet($getValue = array())
	{

		$req = FacadeRequest::all();
		$to_concat = '';
		$cont = 0;

		if (isset($req['subperiodo']))
			unset($req['subperiodo']);

		$req = array_merge($req, $getValue);

		if (!empty($req)) {
			foreach ($req as $key => $value) {



				if (!is_array($value)) {
					if ($cont == 0) {
						$to_concat .= '?' . $key . '=' . $value;
					} else {
						$to_concat .= '&' . $key . '=' . $value;
					}
				} else {
					foreach ($value as $k => $v) {
						$to_concat .= '&' . $key . '[' . $k . ']=' . $v;
					}
				}

				$cont++;
			}
		}
		return $to_concat;
	}

	public static function replaceDangerqueryCharacter($text)
	{
		// ()|='"#&<>~/\!, [] {}
		$signos = array('(', ')', '|', '=', "'", '"', '#', '&', '<', '>', '~', '/', '\\', '!', ',', "[", "]", "}", "{");
		return str_replace($signos, "", $text);
	}

	public static function getLanguageComplete($index)
	{
		$index = strtolower($index);
		$languages = Config::get('app.language_complete');
		if (!empty($languages[$index])) {
			return $languages[$index];
		} else {
			return $index;
		}
	}


	/* function injectionSQL($consulta){
        $injection['in'] =  array('delete','insert','created','drop','alter','update','select');
        $val_injection= false;


        foreach ($injection['in'] as $valor){
            $consulta_temp=stripos($consulta, $valor);
            if($consulta_temp !== false){
                $val_injection = true;
                break;
            }
        }
        return  $val_injection;
     }*/


	static public function Construir_fecha($data)
	{
		if ($data) {
			$res = explode(" ", $data);
			return $res[0][8] . $res[0][9] . "/" . $res[0][5] . $res[0][6] . "/" . $res[0][0] . $res[0][1] . $res[0][2] . $res[0][3];
		}
	}

	static public function Construir_hora($data)
	{
		if ($data) {
			$res = explode(" ", $data);
			return $res[1][0] . $res[1][1] . $res[1][2] . $res[1][3] . $res[1][4];
		}
	}

	static public function acortar($cadena, $limite, $corte = " ", $pad = "...")
	{
		if (strlen($cadena) <= $limite)
			return $cadena;
		if (false !== ($breakpoint = strpos($cadena, $corte, $limite))) {
			if ($breakpoint < strlen($cadena) - 1) {
				$cadena = substr($cadena, 0, $breakpoint) . $pad;
			}
		}
		return $cadena;
	}

	static public function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false)
	{
		$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$str_end = "";
		if ($lower_str_end) {
			$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
		} else {
			$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
		}
		$str = $first_letter . $str_end;
		return $str;
	}

	static function getFragment($inicial, $final, $html)
	{

		$a = strstr($html, $inicial);
		$b = strstr($a, $final);
		$c = str_replace($b, "", $a);

		return $c;
	}

	static function getAll($inicial, $final, $html)
	{

		$t = 0;
		while ($html != "" && $t < 10000) {
			$a = strstr($html, $inicial);
			$b = strstr($a, $final);
			$c = str_replace($b, "", $a);
			$c = str_replace($inicial, "", $c);
			if ($c != "") {
				$ret[] = $c;
			} else {
				break;
			}
			$html = $b;
			$t++;
		}
		return $ret;
	}

	static public function Seo_url($strValue)
	{
		$a = strtolower(self::Limpia(str_replace(' ', '-', str_replace("'", ':', $strValue))));
		$a = str_replace("------", "-", $a);
		$a = str_replace("-----", "-", $a);
		$a = str_replace("----", "-", $a);
		$a = str_replace("---", "-", $a);
		$a = str_replace("--", "-", $a);
		return $a;
	}



	static public function Limpia($cadena)
	{

		$vocales = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "à", "è", "ì", "ò", "ù", "À", "È", "Ì", "Ò", "Ù", "ä", "ë", "ï", "ö", "ü", "Ä", "Ë", "Ï", "Ö", "Ü", "â", "ê", "î", "ô", "û", "Â", "Ê", "Î", "Ô", "Û");
		$acentos = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
		$cadena = str_replace($vocales, $acentos, $cadena);

		$caracteres = array("!", '"', "·", "$", "%", "&", "/", "(", ")", "=", "?", "¿", "'", "¡", "`", "+", "´", "ç", "¨", "^", "*", "º", "ª", "[", "]", "{", "}", " ", ",", ".", ";", "€", "ñ", "Ñ", ":", "®", "<", ">", "", "");
		$resultados = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "-", "", "", "", "", "n", "N", "", "", "", "", "", "");

		$frase = str_replace($caracteres, $resultados, $cadena);
		return $frase;
	}


	static function personalJsCss($admin = 0)
	{

		$action = app('request')->route()->getAction();

		if (!empty($action['controller'])) {
			$a = explode("/", str_replace("App/Http/Controllers/", "", str_replace("\\", "/", $action['controller'])));
		}

		if (isset($a[1]) && empty($admin)) {

			$b = explode("@", $a[1]);
			$theme = Config::get('app.theme');
			$modulo = Str::camel($a[0]);
			$componente = Str::camel(str_replace("Controller", "", $b[0]));
			if (is_file(public_path() . "/js/$modulo/$componente.js")) {
				echo '<script src="' . self::urlAssetsCache("/js/$modulo/$componente.js") . '"></script>';
			}
			if (is_file(public_path() . "/themes/$theme/js/$modulo/$componente.js")) {
				echo '<script src="' . self::urlAssetsCache("/themes/$theme/js/$modulo/$componente.js") . '"></script>';
			}

			if (is_file(public_path() . "/css/$modulo/$componente.css")) {
				echo '<link rel="stylesheet" type="text/css" href="' . self::urlAssetsCache("/css/$modulo/$componente.css") . '" >';
			}
			if (is_file(public_path() . "/themes/$theme/css/$modulo/$componente.css")) {
				echo '<link rel="stylesheet" type="text/css" href="' . self::urlAssetsCache("/themes/$theme/css/$modulo/$componente.css") . '" >';
			}
		} elseif (isset($a[2]) && $admin == 1) {

			$rand = rand();
			$b = explode("@", $a[2]);

			$modulo = Str::camel($a[1]);

			$componente = Str::camel(str_replace("Controller", "", $b[0]));

			if (is_file(public_path() . "/themes_admin/porto/assets/javascripts/$modulo/$componente.js")) {
				echo '<script src="' . self::urlAssetsCache("/themes_admin/porto/assets/javascripts/$modulo/$componente.js") . '"></script>';
			}

			if (is_file(public_path() . "/themes_admin/porto/assets/stylesheets/$modulo/$componente.css")) {
				echo '<link rel="stylesheet" type="text/css" href="' . self::urlAssetsCache("/themes_admin/porto/assets/stylesheets/$modulo/$componente.css") . '" >';
			}
		} elseif (isset($a[2]) && $admin == 2) {

			$rand = rand();
			$b = explode("@", $a[2]);
			$modulo = $a[1] == "V5" ? "V5" : Str::camel($a[1]);

			$pathJs = public_path() . "/themes_admin/porto/assets/javascripts/" . $modulo;
			$pathCss = public_path() . "/themes_admin/porto/assets/stylesheets/" . $modulo;

			if (is_dir($pathJs)) {
				if ($dir = opendir($pathJs)) {
					// Leo todos los ficheros de la carpeta
					while ($elemento = readdir($dir)) {
						// Tratamos los elementos . y .. que tienen todas las carpetas
						if ($elemento != "." && $elemento != "..") {
							// Si es fichero
							if (is_file(str_replace("\\", "/", $pathJs . '/' . $elemento))) {
								// Muestro la carpeta
								echo '<script src="' . self::urlAssetsCache("/themes_admin/porto/assets/javascripts/$modulo/$elemento") . '"></script>';
							}
						}
					}
				}
			}

			if (is_dir($pathCss)) {
				if ($dir = opendir($pathCss)) {
					while ($elemento = readdir($dir)) {
						// Tratamos los elementos . y .. que tienen todas las carpetas
						if ($elemento != "." && $elemento != "..") {
							// Si es fichero
							if (is_file(str_replace("\\", "/", $pathCss . '/' . $elemento))) {
								// Muestro la carpeta
								echo '<link rel="stylesheet" type="text/css" href="' . self::urlAssetsCache("/themes_admin/porto/assets/stylesheets/$modulo/$elemento") . '">';
							}
						}
					}
				}
			}
		}
	}

	public static function numberformat($qtty)
	{
		return ToolsServiceProvider::moneyFormat($qtty, FALSE, 0);
	}


	public static function exit404IfEmpty($var = null)
	{
		if (empty($var)) {
			return abort(404);
		}
	}
	#sirve para arrays tambien
	public static function exit404IfEmptyCollection($collection = null)
	{
		if (count($collection) == 0) {
			return abort(404);
		}
	}

	#devuelve el numero de lotes que hay para este elemento
	public static function showNumLots($numActiveFilters, $filters,  $level, $value)
	{
		if (Config::get("app.gridAllSessions")) {
			$filter_session = array("typeSub", "session");
		} else {
			$filter_session = array("typeSub");
		}
		#listado de los filtros que usamos
		$name_filter =  array_merge($filter_session, array("category", "section", "subsection"));
		$index = "";
		$concat = "";
		foreach ($name_filter as $filter) {

			if ($level == "category" && $filter == "section") {
				break;
			}
			if ($level == "section" && $filter == "subsection") {
				break;
			}

			if ($level == $filter) {
				$index .= $concat . $filter . "-" . $value;
				$concat = "_";
			} else {
				if (!empty($filters[$filter])) {
					$index .= $concat . $filter . "-" . $filters[$filter];
					$concat = "_";
				}
			}
		}



		if (!empty($numActiveFilters[$index])) {
			return $numActiveFilters[$index];
		} else {
			return 0;
		}
	}

	/**
	 * Metodo generico para enviar respuestas Json
	 * @param bool $succes Resultado
	 * @param string $mensaje Mensaje enviado
	 * @param array $datos Body de la respuesta
	 * @param int $codigo Codigo http
	 * @param string $member Clave publica
	 * @return JsonResponse json
	 */
	public static function enviar(bool $succes, string $mensaje, array $datos, int $codigo, string $member = ""): JsonResponse
	{

		return response()->json([
			'succes' => $succes,
			'message' => $mensaje,
			'data' => $datos,
			'member' => $member
		], $codigo);
	}

	/**
	 * Metodo de encriptación
	 * @param string $data datos a encriptar
	 * @param string $key clave de encriptación
	 * @return string datos encriptados
	 */
	public static function encrypt(string $data, string $key): string
	{
		return base64_encode(openssl_encrypt($data, "AES-256-ECB", $key, OPENSSL_RAW_DATA));
	}

	public static function descrypt(string $data, string $key)
	{
		return openssl_decrypt(base64_decode($data), 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
	}

	/*
	public static function breadCrumbSeo($dataSeo){

        $breadCrumb[]= array(
            "name" => $dataSeo->h1_seo,
            "url" => $dataSeo->url,
            "title" => $dataSeo->title_seo);

        if(!empty($dataSeo->parent)){
           $breadCrumb= array_merge( ToolsServiceProvider::breadCrumbSeo($dataSeo->parent),$breadCrumb);
        }

        return $breadCrumb;

    }
*/

	public static function urlAssetsCache($path)
	{
		static $hash = null;
		$publicPath = public_path($path);
		$isDebug = Config::get('app.debug');

		if (!file_exists($publicPath)) {
			return;
		}

		//de las imagenes no podemos obtener el hash ya que no se crean de nuevo en cada deploy
		if(strpos($path, 'img') !== false){
			return URL::asset($path) . "?a=" . filemtime($publicPath);
		}

		if($isDebug || !$hash){
			$hash = filemtime($publicPath);
		}

		return URL::asset($path) . "?a=$hash";
	}

	public static function preloadStylesheets($path, $isCritical)
	{
		if (!file_exists(public_path($path))) {
			return;
		}
		$url = self::urlAssetsCache($path);
		$preload = $isCritical ? "" : " media=\"print\" onload=\"this.media='all'\"";
		$stylesheet = "<link href=\"$url\" rel=\"stylesheet\"$preload>";
		$noScript = "<noscript><link href=\"$url\" rel=\"stylesheet\"/></noscript>";

		return $isCritical
			? $stylesheet
			: "$stylesheet
			$noScript";
	}

	public static function getDateFormat($dateValue, $formatOrigin, $formatReturn)
	{

		if (empty($dateValue)) {
			return '';
		}

		return Carbon::createFromFormat($formatOrigin, $dateValue)->format($formatReturn);
	}

	public static function getParseDateFormat($dateValue, $formatReturn)
	{
		if(empty($dateValue)){
			return '';
		}
		return Carbon::parse($dateValue)->format($formatReturn);
	}

	/**
	 * return date in format day month. Example: 12 de enero / January 12th
	 * @param string $dateValue
	 */
	public static function getDateFormatDayMonthLocale($dateValue)
	{
		$completeLocale = self::getLanguageComplete(Config::get('app.locale'));
   	 	$localeToTime = str_replace('-', '_', $completeLocale);
		$dateFormat = $localeToTime === 'es_ES' ? 'D [de] MMMM' : 'MMMM Do';

		$carbonDate = Carbon::parse($dateValue);
		return $carbonDate->locale($localeToTime)->isoFormat($dateFormat);
	}

	/**
	 * Obtener feed rss de wordpress.
	 * Si el certificado esta caducado, no se puede acceder al
	 * contenido.
	 */
	public static function getWorpressRss($url)
	{
		try {
			$context = stream_context_create(array('http' => array(
				'timeout' => 3, //en segundos
			)));

			$xml_string = file_get_contents($url, false, $context);
			$xml = simplexml_load_string($xml_string, 'SimpleXMLElement', LIBXML_NOCDATA);
			$json = json_encode($xml);
			$array = json_decode($json, true);
		} catch (\Throwable $th) {
			Log::info('Error al obtener el feed de wordpress', ['error' => $th->getMessage()]);
			return [];
		}


		return $array['channel']['item'];
	}


	/**
	 * withHeading = si true, el array debe ser asociativo (diccionario)
	 */
	public static function exportCollectionToExcel($collection, $fileName, $withHeading = true)
	{
		return $collection->downloadExcel("$fileName.xlsx", \Maatwebsite\Excel\Excel::XLSX, $withHeading);
	}

	public static function exportCollectionToCsv($collection, $fileName, $withHeading = true)
	{
		return $collection->downloadExcel("$fileName.csv", \Maatwebsite\Excel\Excel::CSV, $withHeading);
	}

	public static function storeCollectionToCSV($collection, $fileName, $withHeading = true)
	{
		return $collection->storeExcel("./$fileName.csv", 'public_html', \Maatwebsite\Excel\Excel::CSV, $withHeading);
	}

	public static function decodeHtmlStringToArrayByTag($stringHtml, $tag, $closure = null)
	{
		$dom = new DOMDocument();
		$dom->preserveWhiteSpace = false;

		try {

			//limpiamos posibles simbolos & ya que provocan error
			$stringHtml = preg_replace("/&(?!\S+;)/", "&amp;", $stringHtml);

			$dom->loadHTML(mb_convert_encoding($stringHtml, 'HTML-ENTITIES', 'UTF-8'));

			$elements = $dom->getElementsByTagName($tag);
			foreach ($elements as $element) {

				if ($closure) {
					$closure($element);
				}
				$html_arr[] = $dom->saveHtml($element);
			}
			return $html_arr ?? [];
		} catch (\Throwable $th) {
			return [$stringHtml];
		}
	}

	public static function changePositionNamesWithComa($string)
	{
		$names = explode(',', $string);
		$names = array_reverse($names);
		$string = implode(' ', $names);
		return trim($string);
	}

	public static function fileNameIsImage($url)
	{
		$extension = pathinfo($url, PATHINFO_EXTENSION);
		$extension = strtolower($extension);
		return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
	}

	public static function fileNameIsVideo($url)
	{
		$extension = pathinfo($url, PATHINFO_EXTENSION);
		$extension = strtolower($extension);
		return in_array($extension, ['mp4', 'webm', 'mov']);
	}


	/**
	 * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|null $files
	 */
	public static function validFiles($files)
	{
		if (!is_array($files)) {
			return $files->isValid() ? [$files] : [];
		}

		$files = array_filter($files, function ($file) {
			return $file->isValid();
		});

		return $files;
	}

	public static function isValidMime(Request $request, $rules)
	{
		if (Validator::make($request->file(), $rules)->fails()) {
			return false;
		}

		return true;
	}

	public static function serverFileSizeToKb()
	{
		$size = min(ini_get('upload_max_filesize'), ini_get('post_max_size'));
		$sizeSuffix = substr($size, -1);
		$sizeValue = substr($size, 0, -1);

		$sizes = [
			'M' => 1,
			'G' => 2,
			'T' => 3,
		];

		if (!isset($sizes[$sizeSuffix])) {
			return $size;
		}

		$sizeValue = (int) $sizeValue;
		$sizeValue = $sizeValue * pow(1024, $sizes[$sizeSuffix]);
		return $sizeValue;
	}

	/**
	 * Get values from single row in the database.
	 * @param Builder $dataTable new query instance of the model
	 * @param array $whereCases where cases
	 * @param array $whereIsNotNullCases where is not null cases
	 * @param string $orderBy order is asc
	 * @param array $joins [table, first, operator, second]
	 * @param array $scopes scopes
	 * @return mixed
	 */
	public static function getDatabaseSingleValues(
		$dataTable,
		$whereCases = [],
		$whereIsNotNullCases = [],
		$orderBy = '',
		$joins = [],
		$scopes = []
	) {
		if (count($whereCases) > 0) {
			$dataTable = $dataTable->where($whereCases);
		}
		if (count($whereIsNotNullCases) > 0) {
			$dataTable = $dataTable->whereNotNull($whereIsNotNullCases);
		}
		if ($orderBy != '') {
			$dataTable = $dataTable->orderBy($orderBy, 'asc');
		}
		if (count($joins) > 0) {
			foreach ($joins as $join) {
				$dataTable = $dataTable->join($join['table'], $join['first'], $join['operator'], $join['second']);
			}
		}
		if (count($scopes) > 0) {
			foreach ($scopes as $scope) {
				$dataTable = $dataTable->$scope();
			}
		}
		return $dataTable->first();
	}

	public static function isITPLot($cod_sub, $ref): bool
	{
		if (!Config::get('app.checkItp', false)) {
			return false;
		}

		$cod_cli = Session::get('user.cod', 0);
		if (!$cod_cli) {
			return false;
		}

		return DB::executeFunction('LOTE_ITP', [
			'EMPRESA' => Config::get('app.emp'),
			'SUBASTA' => $cod_sub,
			'LOTE' => $ref,
			'CLIENTE' => $cod_cli
		]);
	}

	public static function getBlogURLTranslated($lang, $web_blog_id): array
	{
		$blogs = Web_Blog::where('IDBLOG_WEB_BLOG_LANG', $web_blog_id)->joinWebBlogLang()->get();
		foreach ($blogs as $key => $blog) {
			if ($blog->lang_web_blog_lang == mb_strtoupper($lang)) {
				unset($blogs[$key]);
			}
		}
		$blog = $blogs->first();
		if (!$blog) {
			return [];
		}

		$categories = Web_Category_Blog_Lang::where('ID_CATEGORY_BLOG_LANG', $blog->primary_category_web_blog)->get();
		foreach ($categories as $key => $category) {
			if ($category->lang_category_blog_lang == mb_strtoupper($lang)) {
				unset($categories[$key]);
			}
		}
		$category = $categories->first();
		if (!$category) {
			return [];
		}

		$to_lang = mb_strtolower($blog->lang_web_blog_lang);
		$blog_literal_url = 'blog';
		$category_url = $category->url_category_blog_lang;
		$blog_url = $blog->url_web_blog_lang;

		$full_url = "/$to_lang/$blog_literal_url/$category_url/" . ($blog->enabled_web_blog_lang != 0 ? "$blog_url" : "");

		return [
			'url' => $full_url,
			'to_lang' => $to_lang,
		];
	}
}
