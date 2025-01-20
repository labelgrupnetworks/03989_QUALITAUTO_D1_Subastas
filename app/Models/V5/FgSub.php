<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Override\RelationCollection;
use App\Providers\ToolsServiceProvider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FgSub extends Model
{
	protected $table = 'FgSub';
	protected $primaryKey = 'cod_sub';

	public $timestamps = false;
	public $incrementing = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	public $lang;

	const SUBC_SUB_ACTIVO = 'S';
	const SUBC_SUB_ADMINISITRADOR = 'A';
	const SUBC_SUB_HISTORICO = 'H';
	const SUBC_SUB_INACTIVO = 'N';
	const SUBC_SUB_CERRADO = 'C';

	const TIPO_SUB_PRESENCIAL = 'W';
	const TIPO_SUB_ONLINE = 'O';
	const TIPO_SUB_VENTA_DIRECTA = 'V';
	const TIPO_SUB_PERMANENTE = 'P';
	const TIPO_SUB_ESPECIAL = 'E';
	const TIPO_SUB_MAKE_OFFER = 'M';

	const SUBABIERTA_SUB_PUJAS = 'P';
	const SUBABIERTA_SUB_ORDENES = 'O'; //evitar
	const SUBABIERTA_SUB_NO = 'N';

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_sub' => Config::get("app.emp")
		];



		parent::__construct($vars);
	}

	protected static function boot()

	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {

			$builder->where('emp_sub', Config::get("app.emp"));
		});
	}

	public function newCollection(array $models = [])
	{
		return new RelationCollection($models);
	}

	public function scopeWhereUpdateApi($query, $item)
	{
		return $query->where('cod_sub', $item["cod_sub"]);
	}


	# SELECTS

	public function scopeSelectBasicSub($query)
	{
		return $query->addSelect("cod_sub", "tipo_sub");
	}


	# WHERE

	public function scopeActiveSub($query)
	{
		if (Session::has('user') && Session::get('user.admin')) {
			$query->whereIn('SUBC_SUB', ['S', 'A']);
		} else {
			$query->where("fgsub.SUBC_SUB", 'S');
		}

		return $query;
	}

	public function scopeHistoricSub($query)
	{
		$query->where("fgsub.SUBC_SUB", 'H');
		return $query;
	}

	public function scopeOnlineAuctions($query)
	{
		return $query->where("fgsub.TIPO_SUB", FgSub::TIPO_SUB_ONLINE);
	}

	# JOINS

	public function getInfoSub($cod_sub, $refSession = null)
	{

		return $this->joinlangSub()

			->GetInfoSession($refSession)
			->where("FGSUB.COD_SUB", $cod_sub)
			->addSelect("FGSUB.SUBC_SUB")
			->addSelect("FGSUB.DFEC_SUB, FGSUB.HFEC_SUB")
			->addSelect("NVL(FGSUB_LANG.WEBMETAT_SUB_LANG,FGSUB.WEBMETAT_SUB) as WEBMETAT_SUB")
			->addSelect("NVL(FGSUB_LANG.WEBMETAD_SUB_LANG,FGSUB.WEBMETAD_SUB) as WEBMETAD_SUB")
			->addSelect("NVL(FGSUB_LANG.EXPOFECHAS_SUB_LANG,FGSUB.EXPOFECHAS_SUB) as EXPOFECHAS_SUB")
			->addSelect("NVL(FGSUB_LANG.EXPOHORARIO_SUB_LANG,FGSUB.EXPOHORARIO_SUB) as EXPOHORARIO_SUB")
			->addSelect("NVL(FGSUB_LANG.EXPOLOCAL_SUB_LANG,FGSUB.EXPOLOCAL_SUB) as EXPOLOCAL_SUB")
			->addSelect("NVL(FGSUB_LANG.EXPOMAPS_SUB_LANG,FGSUB.EXPOMAPS_SUB) as EXPOMAPS_SUB")
			->addSelect("NVL(FGSUB_LANG.SESFECHAS_SUB_LANG,FGSUB.SESFECHAS_SUB) as SESFECHAS_SUB")
			->addSelect("NVL(FGSUB_LANG.SESHORARIO_SUB_LANG,FGSUB.SESHORARIO_SUB) as SESHORARIO_SUB")
			->addSelect("NVL(FGSUB_LANG.SESLOCAL_SUB_LANG,FGSUB.SESLOCAL_SUB) as SESLOCAL_SUB")
			->addSelect("NVL(FGSUB_LANG.SESMAPS_SUB_LANG,FGSUB.SESMAPS_SUB) as SESMAPS_SUB")
			->first();
	}

	public function scopeJoinLangSub($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		$query->addSelect("nvl(FGSUB_LANG.DES_SUB_LANG,FGSUB.DES_SUB) DES_SUB");

		#reducimos mucho los tiempos de carga si no cargamos los clob y los convertimos a varchar de 4000
		if (Config::get("app.clobToVarchar")) {
			$query->addSelect("dbms_lob.substr(NVL(FGSUB_LANG.DESCDET_SUB_LANG,FGSUB.DESCDET_SUB), 2000, 1 ) DESCDET_SUB");
		} else {
			$query->addSelect("NVL(FGSUB_LANG.DESCDET_SUB_LANG,FGSUB.DESCDET_SUB) DESCDET_SUB");
		}

		$query->leftJoin('FGSUB_LANG', "FGSUB.COD_SUB = FGSUB_LANG.COD_SUB_LANG AND FGSUB_LANG.LANG_SUB_LANG = '" . $lang . "' AND FGSUB.EMP_SUB = FGSUB_LANG.EMP_SUB_LANG");
		return  $query;
	}
	public function scopeJoinSessionSub($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));


		$query->addSelect("FGSUB.COD_SUB")
			->addSelect("FGSUB.AGRSUB_SUB")
			->addSelect('"auc_sessions"."orders_start"')
			->addSelect('"auc_sessions"."orders_end"')
			->addSelect("FGSUB.TIPO_SUB")
			->addSelect('"auc_sessions"."reference"')
			->addSelect('NVL("auc_sessions_lang"."name_lang","auc_sessions"."name")as name')
			->addSelect('NVL("auc_sessions_lang"."description_lang","auc_sessions"."description") as description')
			->addSelect('"auc_sessions"."id_auc_sessions"')
			->addSelect('"auc_sessions"."start" as session_start')
			->addSelect('"auc_sessions"."end" as session_end')
			->addSelect("FGSUB.EMP_SUB")
		;

		$query->join('"auc_sessions"', '"auc_sessions"."company" = FGSUB.EMP_SUB AND "auc_sessions"."auction" = FGSUB.COD_SUB');
		$query->leftJoin('"auc_sessions_lang"', ' "auc_sessions_lang"."id_auc_session_lang" = "auc_sessions"."id_auc_sessions"   AND "auc_sessions"."company" = "auc_sessions_lang"."company_lang" AND "auc_sessions"."auction" = "auc_sessions_lang"."auction_lang" AND "auc_sessions_lang"."lang_auc_sessions_lang" = \'' . $lang . '\'');

		return $query;
	}

	public function scopeSimpleJoinSessionSub($query)
	{
		return $query->join('"auc_sessions"', '"company" = emp_sub AND "auction" = cod_sub');
	}

	public function scopeGetInfoSession($query, $refSession)
	{
		if (empty($refSession)) {
			return $query;
		}

		return $query->JoinSessionSub()
			->where('"auc_sessions"."reference"', $refSession);
	}
	public function scopeVisibilidadsubastas($query, $codCli)
	{
		#si no hay usuario no puede ver nada, por lo que ponemos una condicion imposible
		if (empty($codCli)) {
			$query = $query->where(1, 2);
		} else {
			#buscar subastas del usuario, o de cualquier usuario,  agrupamos por subastas por que da igual que tengan un lote o más
			/* NO PODEMOS COJER UNA OPCION COMO VISIBLE SI EL USUARIO LA TIENE PUESTA COMO NO VISIBLE EXPRESAMENTE POR ESO COMPARAMOS SUBASTA Y LOTE con la inversa, SI EL LOTE COINCIDE CON UNO QUE NO ES VISIBLE NO SE PUEDE VER, O SI EL USUARIO TIENE LA SUBASTA ENTERA COMO NO VISIBLE */

			$query = $query->leftjoin(
				DB::raw("(select  FGVISIBILIDAD.EMP_VISIBILIDAD, FGVISIBILIDAD.SUB_VISIBILIDAD, MAX(FGVISIBILIDAD.CLI_VISIBILIDAD) CLI_VISIBILIDAD  from FGVISIBILIDAD
			left Join FGVISIBILIDAD   INVERSA ON INVERSA.EMP_VISIBILIDAD = FGVISIBILIDAD.EMP_VISIBILIDAD AND INVERSA.SUB_VISIBILIDAD = FGVISIBILIDAD.SUB_VISIBILIDAD AND (INVERSA.REF_VISIBILIDAD = FGVISIBILIDAD.REF_VISIBILIDAD  OR INVERSA.REF_VISIBILIDAD IS NULL)    AND  INVERSA.INVERSO_VISIBILIDAD = 'S' AND  INVERSA.CLI_VISIBILIDAD =   '" . $codCli . "'
			where (FGVISIBILIDAD.CLI_VISIBILIDAD =  '" . $codCli . "' OR FGVISIBILIDAD.CLI_VISIBILIDAD is null )  AND FGVISIBILIDAD.INVERSO_VISIBILIDAD ='N'  AND INVERSA.CLI_VISIBILIDAD IS NULL
			group by FGVISIBILIDAD.EMP_VISIBILIDAD, FGVISIBILIDAD.SUB_VISIBILIDAD) VISIBILIDAD_SUBASTAS"),
				" VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = EMP_SUB and VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = COD_SUB"
			)->

				#mirar si el usuario tiene visibilidad en todas las subastas
				leftjoin("FGVISIBILIDAD VISIBILIDAD_TODAS_SUBASTAS", " VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD =  '" . $codCli . "' AND INVERSO_VISIBILIDAD ='N' AND VISIBILIDAD_TODAS_SUBASTAS.SUB_VISIBILIDAD IS NULL")->





				# MIRAMOS SI HAY ALGUNA NORMA INVERSA PARA ESTE USUARIO O PARA TODOS
				leftjoin(
					"FGVISIBILIDAD INVERSO_VISIBILIDAD_SUBASTAS",
					"INVERSO_VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = EMP_SUB and INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = COD_SUB AND INVERSO_VISIBILIDAD_SUBASTAS.INVERSO_VISIBILIDAD ='S' AND
    		(INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD =  '" . $codCli . "' OR INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD is null ) AND INVERSO_VISIBILIDAD_SUBASTAS.REF_VISIBILIDAD IS NULL"
				)->

				#comprobar que el left join visibilidad subasta devuelve resultado para esta subasta o que el usuario tiene visibilidad en todas las subastas
				whereRAW("(VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL)")->
				# si el usuario no tiene visibilidad universal o  si la tiene pero no tiene oculta esta subasta o esa subasta esta oculta y el tiene esa subasta activa o almenos un lote
				whereRAW("( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NULL OR ( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL AND (VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NULL)))");
		}
		return $query;
	}

	public function getSubcSubTypes()
	{
		return [
			self::SUBC_SUB_INACTIVO => trans("admin-app.fields.subc_sub_i"),
			self::SUBC_SUB_ACTIVO => trans("admin-app.fields.subc_sub_s"),
			self::SUBC_SUB_ADMINISITRADOR => trans("admin-app.fields.subc_sub_a"),
			self::SUBC_SUB_HISTORICO => trans("admin-app.fields.subc_sub_h"),
			self::SUBC_SUB_CERRADO => trans("admin-app.fields.subc_sub_c")

		];
	}

	public function getSubcSubTypeAttribute()
	{
		if (array_key_exists($this->subc_sub, $this->getSubcSubTypes())) {
			return $this->getSubcSubTypes()[$this->subc_sub];
		}

		return $this->subc_sub;
	}

	public function getTipoSubTypes()
	{
		//actualizar tabla sys_auction_types de todos los clientes y activar erpActiveAuctions
		//$configActiveAuctions = explode(",", Config::get('app.admin_active_auctions', 'W,P,O,V,E,M,I'));
		//$erpActiveAuctions = SysAuctionTypes::enabledAuctions()->get()->pluck('code');

		$types = collect([
			self::TIPO_SUB_PRESENCIAL => trans("admin-app.fields.tipo_sub_w"),
			self::TIPO_SUB_ONLINE => trans("admin-app.fields.tipo_sub_o"),
			self::TIPO_SUB_VENTA_DIRECTA => trans("admin-app.fields.tipo_sub_v"),
			self::TIPO_SUB_PERMANENTE => trans("admin-app.fields.tipo_sub_p"),
			self::TIPO_SUB_ESPECIAL => trans("admin-app.fields.tipo_sub_e"),
			self::TIPO_SUB_MAKE_OFFER =>  trans("admin-app.fields.tipo_sub_m"),
		]);

		return $types->filter(function ($value, $key) {
			return in_array($key, explode(",", Config::get('app.admin_active_auctions', 'W,P,O,V,E,M,I')));
		});
	}

	public function getTipoSubTypeAttribute()
	{
		return $this->getTipoSubTypes()[$this->tipo_sub] ?? $this->tipo_sub;
	}

	public function getSubAbiertaTypes()
	{
		return [
			self::SUBABIERTA_SUB_NO => trans("admin-app.fields.tipo_subabierta_sub_n"),
			self::SUBABIERTA_SUB_ORDENES => trans("admin-app.fields.tipo_subabierta_sub_o"),
			self::SUBABIERTA_SUB_PUJAS => trans("admin-app.fields.tipo_subabierta_sub_p")
		];
	}

	public function getSubAbiertaTypeAttribute()
	{
		if (array_key_exists($this->subabierta_sub, $this->getSubAbiertaTypes())) {
			return $this->getSubAbiertaTypes()[$this->subabierta_sub];
		}

		return $this->subc_sub;
	}

	public function getDesdeFechaHoraAttribute()
	{
		return ToolsServiceProvider::getDateFormat($this->dfec_sub, 'Y-m-d H:i:s', 'd-m-Y') . ' ' . $this->dhora_sub;
	}

	public function getHastaFechaHoraAttribute()
	{
		return ToolsServiceProvider::getDateFormat($this->hfec_sub, 'Y-m-d H:i:s', 'd-m-Y') . ' ' . $this->hhora_sub;
	}

	public function scopelog($query)
	{
		return $query->joinUsr()->select("FSUSR.NOM_USR, FGSUB.*");
	}

	public function scopeJoinUsr($query)
	{
		return $query->leftjoin("FSUSR", "FSUSR.COD_USR = FGSUB.USR_UPDATE_SUB");
	}

	public function scopeLeftJoinArtistExhibition($query)
	{
		return $query->leftjoin("WEB_ARTIST", "WEB_ARTIST.ID_ARTIST = FGSUB.VALORCOL_SUB AND WEB_ARTIST.EMP_ARTIST = FGSUB.EMP_SUB");
	}

	public function changeToExhibitionFormat($fgsubModel)
	{
		if (!$fgsubModel) {
			return null;
		}

		$artistName = $fgsubModel->name_artist ?? "";
		$artistNameArray = explode(',', $artistName);
		if (count($artistNameArray) > 1) {
			$artistName = trim($artistNameArray[1]) . ' ' . $artistNameArray[0];
		}

		$completeLocale = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		$localeToTime = str_replace('-', '_', $completeLocale);
		$dateFormat = $localeToTime === 'es_ES' ? 'D [de] MMMM' : 'MMMM Do';

		$initialDate = Carbon::parse($fgsubModel->dfec_sub);
		$endDate = Carbon::parse($fgsubModel->hfec_sub);
		$initialDateFormat = $initialDate->locale($localeToTime)->isoFormat($dateFormat);
		$endDateFormat = $endDate->locale($localeToTime)->isoFormat($dateFormat);

		//Se organiza por temporadas y no por años, las temporadas empiezan el septiembre y acaban en agosto
		//si el mes es menor que septiembre pertenece a la temporada anterior, por lo que restaremos un año
		$season = $initialDate->month < 9 ? $initialDate->year - 1 : $initialDate->year;

		//$emp = Config::get('app.emp');
		//$image = ToolsServiceProvider::urlAssetsCache("/img/AUCTION_{$emp}_{$fgsubModel->cod_sub}.JPEG");
		$image = ToolsServiceProvider::url_img_auction('subasta_large', $fgsubModel->cod_sub);
		//$image = ToolsServiceProvider::auctionImage($fgsubModel->cod_sub, 'subasta_large');
		//$image = $fgsubModel->image_lot ?? "";

		$exhibitionFields = [
			'url' => ToolsServiceProvider::url_exposicion($fgsubModel->des_sub, $fgsubModel->cod_sub),
			'image' => $image,
			'artist' => $artistName,
			'title' => $fgsubModel->des_sub,
			'initialDate' => $initialDateFormat,
			'finalDate' => $endDateFormat,
			'year' => $initialDate->year,
			'season' => $season,
		];

		return (object) $exhibitionFields;
	}

	public function getExhibitionFieldsAttribute()
	{
		return $this->changeToExhibitionFormat($this);
	}
}
