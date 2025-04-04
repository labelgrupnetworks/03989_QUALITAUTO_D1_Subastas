<?php

# Ubicacion del modelo
namespace App\Models;

use App\libs\CacheLib;
use App\libs\EmailLib;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\V5\FgAsigl1;

use App\libs\StrLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1_Aux;
use App\Models\V5\FgAsigl1Mt;
use App\Models\V5\FxCli;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgCsub;
use App\Models\V5\FgSub;
use App\Models\V5\Web_Cancel_Log;
use App\Providers\ToolsServiceProvider;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\libs\SeoLib;
use App\Models\V5\AucSessions;
use App\Models\V5\FgLicit;
use App\Models\V5\FgLicitRepresentados;
use App\Models\V5\FgPujaMax;
use App\Models\V5\FgRepresentados;
use App\Models\V5\FgSubInvites;
use Illuminate\Support\Facades\Session;

class Subasta extends Model
{
    protected $table = 'FGSUB';
	protected $guarded = [];

    # Codigo subasta (COD_SUB)
    public $cod;

    # Lin
    public $lote;

    # Tipo de Subasta (W=Web tiempo real ordenes hasta fin despues pujas en sala hasta que nadie puje / O=Online tipo ebay/S/I/D=Venta Directa botiga online / P=Permanente nunca se cierra)
    /*MODIFICADO, el tipo D no se tiene que ver en la web son como ventas en tienda fisica, se tienen que ver las de tipo V que son venta directa en web*/
    public $tipo;

    # Linea de Categoria
    public $cat;

    # Texto variable
    public $texto;

    # Hoja de cesión
    public $hces;

    # Codigo unico de cliente de ERP
    public $cli_licit;

    # Referencia hces
    public $ref;

    # Numero orden lote hces1 (filtro)
    public $orden;

    # Codigo licitador
    public $licit;

    # Importe
    public $imp;

    # Tipo de puja (W) para web
    public $type_bid;


    # Controles para paginador de resultados
    public $page;
    public $itemsPerPage;

    # Permite filtrar los lotes por su estado de CERRADO_ASIGL0 (P, N, S) Pausado, No cerrado y Cerrado
    # P -> únicamente en tiempo real
    public $estado_lotes;

    #Permite modificar el order by de ciertas consultas.
    public $order_by_values;
    public $where_filter = "";
    public $join_filter = "";
    public $select_filter = "";
    public $params_filter = array();

    public $is_gestor;

    # Para las sessiones.
    public $id_auc_sessions;
    #referencia de session
    public $session_reference;

    # para saber is la subasta no tiene pujas
    public $sin_pujas=false;

    public $estimado;
    public $precio;

	public $scales;

	public $represented = null;

	public static $allAuctions;

    public static function getOffset($page, $itemsPerPage)
    {
        $result = FALSE;
        if(empty($page) or $page == 1) {
            $start  = 1;
            $offset = 1;
        } else {
            $start  = $itemsPerPage;

            if(is_int($page) && $page > 2) {
                $start = (($itemsPerPage * $page) + 1) - $itemsPerPage;
            } else {
                $start = ($itemsPerPage) + 1;
            }

            $offset = 1;
        }

        if($page != 'all') {
            $sql = " WHERE rn BETWEEN ".$start." AND ".( ($start) + ($itemsPerPage - $offset));
            $result = $sql;
        }

        return $result;

    }

    public static function getCat($lin = false)
    {
        $result = false;

        if(!empty($lin) and is_numeric($lin)) {
            $result = " AND cat0.LIN_ORTSEC0 = :cat";
        }

        return $result;
    }

    /*
     * Obtenemos las sesiones de la subasta
     */
    public function getSessiones()
    {
		return AucSessions::query()
				->select('"auc_sessions".*')
				->selectRaw('NVL("auc_sessions_lang"."name_lang",  "auc_sessions"."name") name')
				->joinLang()
				->where([
					['"auction"', '=', $this->cod],
					['"init_lot"', '>', 0],
					['"end_lot"', '>', 0]
				])
				->whereNotNull('"start"')
				->whereNotNull('"end"')
				->orderBy('"reference"')
				->get();
    }

    public function getLots_old($type = "normal", $cache_sql = false)
    {

        if (empty($this->order_by_values)){
            $order_by = "ORDER BY ASIGL0.REF_ASIGL0";
        }else{
            $order_by = "ORDER BY $this->order_by_values";
        }
        if (empty($this->group_by)){
            $group_by = "";
        }else{
            $group_by =" GROUP BY ". $this->group_by;
        }

        if(!empty(\Config::get('app.hide_not_sell_lot_historical')) ){
            /*  si la subasta es histórica que solo se vea si está vendido */
               $this->where_filter.= " AND (  SUB.SUBC_SUB != 'H' or (HCES1.SUB_HCES1 = ASIGL0.SUB_ASIGL0 and     HCES1.LIC_HCES1 = 'S' )   ) ";

        }
        //ocultar lotes devueltos
        if(!empty(Config::get('app.hide_return_lot'))){
            $this->where_filter.= " AND HCES1.FAC_HCES1 != 'D' AND HCES1.FAC_HCES1 != 'R' ";
        }


         $params = array(
                'emp'       =>  Config::get('app.emp'),
                'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))

                );

        $sql_pre = "FROM FGASIGL0 ASIGL0
                INNER JOIN FGHCES1 HCES1 ON (HCES1.EMP_HCES1 = :emp AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0  AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0)
                INNER JOIN FGSUB SUB ON SUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND SUB.COD_SUB = ASIGL0.SUB_ASIGL0
                INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = SUB.COD_SUB AND AUC.\"company\" = ASIGL0.EMP_ASIGL0
                LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG =  HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1 AND HCES1_LANG.LANG_HCES1_LANG = :lang)
                LEFT JOIN FGCSUB CSUB ON (CSUB.EMP_CSUB = ASIGL0.EMP_ASIGL0 AND CSUB.sub_CSUB = ASIGL0.SUB_ASIGL0 AND REF_CSUB = ASIGL0.REF_ASIGL0)
                $this->join_filter
                WHERE
                        ASIGL0.EMP_ASIGL0 = :emp


                AND
                        ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\"
                AND
                        ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\"
                AND
                        SUB.SUBC_SUB IN ('S','H','A')
                AND
                    OCULTO_ASIGL0 = 'N'
               /* si la subasta es de tipo O los lotes deben estar activos */
              AND (SUB.TIPO_SUB != 'P' or (TRUNC(ASIGL0.FINI_ASIGL0) < TRUNC(SYSDATE) or (ASIGL0.FINI_ASIGL0 = TRUNC(SYSDATE) AND  ASIGL0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) )
                     and (( SUB.TIPO_SUB !='P') or ASIGL0.cerrado_asigl0 ='N')
                $this->where_filter
                ";

                /* 23/03/2018 solo lo tiene que hacer para las permane
              AND (SUB.TIPO_SUB NOT IN ('O','P') or (TRUNC(ASIGL0.FINI_ASIGL0) < TRUNC(SYSDATE) or (ASIGL0.FINI_ASIGL0 = TRUNC(SYSDATE) AND  ASIGL0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) )
                     and (( SUB.TIPO_SUB !='O' and SUB.TIPO_SUB !='P') or ASIGL0.cerrado_asigl0 ='N')   */
         if ($type == "count"){
            $sql =" SELECT count(ASIGL0.REF_ASIGL0)  as cuantos
                    $sql_pre
                    ";

            if($cache_sql){
                //quitamos espacios en blanco
                $name_cache = "count_". str_replace(" ","", $this->where_filter. $this->join_filter);

                $res = \CacheLib::useCache($name_cache,$sql, $params);
            }else{
                $res = DB::select($sql, $params);
            }
            return head($res)->cuantos;
        }elseif($type == "small"){
            $sql ="
                    SELECT
                    $this->select_filter

                    $sql_pre

                    $group_by
                        $order_by
                    ";
            if($cache_sql){
                //quitamos espacios en blanco
                $name_cache ="small_". str_replace(" ","", $this->where_filter. $this->join_filter.$this->select_filter);

                $lotes = \CacheLib::useCache($name_cache,$sql, $params);
            }else{
                $lotes = DB::select($sql, $params);
            }
            return $lotes;

        }elseif($type == "normal"){

            $sql =" SELECT * FROM (
                    SELECT rownum RN, T.* FROM (
                    SELECT
                     $this->select_filter
                     HCES1.REF_HCES1, HCES1.ORDEN_HCES1, HCES1.FAC_HCES1, HCES1.LIC_HCES1, HCES1.LIN_HCES1, HCES1.NUM_HCES1,  HCES1.SUB_HCES1, HCES1.CONTEXTRA_HCES1,
                     CSUB.HIMP_CSUB,
                     HCES1.IMPLIC_HCES1, HCES1.descdet_HCES1,
                     NVL(HCES1_LANG.DESCWEB_HCES1_LANG,  HCES1.DESCWEB_HCES1) DESCWEB_HCES1,
                     NVL(HCES1_LANG.DESC_HCES1_LANG,  HCES1.DESC_HCES1) DESC_HCES1,
                    NVL(HCES1_LANG.WEBFRIEND_HCES1_LANG,  HCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1, NVL(HCES1_LANG.TITULO_HCES1_LANG,  HCES1.TITULO_HCES1) TITULO_HCES1,


                    ASIGL0.REF_ASIGL0,ASIGL0.NUMHCES_ASIGL0, ASIGL0.IMPSALHCES_ASIGL0, ASIGL0.CERRADO_ASIGL0, ASIGL0.REMATE_ASIGL0,  ASIGL0.COMPRA_ASIGL0,ASIGL0.IMPTASH_ASIGL0, ASIGL0.IMPRES_ASIGL0,ASIGL0.IMPTAS_ASIGL0,RETIRADO_ASIGL0,ASIGL0.DESTACADO_ASIGL0,
                    SUB.COD_SUB, SUB.DES_SUB, SUB.TIPO_SUB, SUB.SUBC_SUB,SUB.SUBABIERTA_SUB, SUB.OPCIONCAR_SUB,
                    AUC.\"id_auc_sessions\", AUC.\"name\", AUC.\"start\" as START_SESSION, AUC.\"end\" as END_SESSION,
                    AUC.\"orders_start\", AUC.\"orders_end\",
                    (CASE WHEN ASIGL0.FFIN_ASIGL0 IS NOT NULL AND ASIGL0.HFIN_ASIGL0 IS NOT NULL
                        THEN REPLACE(TO_DATE(TO_CHAR(ASIGL0.FFIN_ASIGL0, 'DD/MM/YY') || ' ' || ASIGL0.HFIN_ASIGL0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                        ELSE null END) close_at,
                    GREATEST (implic_hces1, IMPSALHCES_ASIGL0) as max_puja, ASIGL0.OFERTA_ASIGL0


                    $sql_pre
                    $order_by
            )T )
                    ".self::getOffset($this->page, $this->itemsPerPage);
           /* print_r($sql);
            die();*/
            if($cache_sql){
                //quitamos espacios en blanco
                $name_cache ="normal_". str_replace(" ","", $this->where_filter. $this->join_filter.$this->select_filter.$order_by.$this->page.$this->itemsPerPage);

                $lotes = \CacheLib::useCache($name_cache,$sql, $params);
            }else{
                $lotes = DB::select($sql, $params);
            }

            return $lotes;
        }
        /*
        $lotes = DB::select($sql, $params);

        return $lotes;
         */
    }






    public function getLots($type = "normal", $cache_sql = false)
    {

        if (empty($this->order_by_values)){
            $order_by = "ORDER BY ASIGL0.REF_ASIGL0";
        }else{
            $order_by = "ORDER BY $this->order_by_values";
        }
        if (empty($this->group_by)){
            $group_by = "";
        }else{
            $group_by =" GROUP BY ". $this->group_by;
        }

        if(!empty(\Config::get('app.hide_not_sell_lot_historical')) ){
            /*  si la subasta es histórica que solo se vea si está vendido */
               $this->where_filter.= " AND (  SUB.SUBC_SUB != 'H' or (HCES1.SUB_HCES1 = ASIGL0.SUB_ASIGL0 and     HCES1.LIC_HCES1 = 'S' )   ) ";

        }
        //ocultar lotes devueltos
        if(!empty(Config::get('app.hide_return_lot'))){
            $this->where_filter.= " AND HCES1.FAC_HCES1 != 'D' AND HCES1.FAC_HCES1 != 'R' ";
        }
		#si tienen este config los lotes de las subastas que sean V-tienda o O-Online no se ven cuando estan cerrados
		if(\Config::get("app.hideCloseLots")){
			$this->where_filter.= "AND (ASIGL0.CERRADO_ASIGL0 ='N' OR SUB.TIPO_SUB ='W') ";
		}

        $admin = '';
        if(\Session::has('user') && \Session::get('user.admin')){
            $admin = ",'A'";
        }


         $params = array(
                'emp'       =>  Config::get('app.emp'),
                'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))

				);

		if(!empty($this->params_filter)){
			$params = array_merge($this->params_filter,$params);
		}



		/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
		if(\Config::get("app.restrictVisibility")){
			//si no hay usuario logeado devolvemos array vacio
			if(empty(\Session::get('user.cod'))){
				return [];
			}

			$this->join_filter = $this->join_filter ." ". $this->restrictVisibilityLot("join");
			$this->where_filter = $this->where_filter ."  ". $this->restrictVisibilityLot("where");
			$params['codCli'] = \Session::get('user.cod');
		}




        $sql_join = "INNER JOIN FGHCES1 HCES1 ON (HCES1.EMP_HCES1 = :emp AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0  AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0)
                INNER JOIN FGSUB SUB ON SUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND SUB.COD_SUB = ASIGL0.SUB_ASIGL0
                INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = SUB.COD_SUB AND AUC.\"company\" = ASIGL0.EMP_ASIGL0
                LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG =  HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1 AND HCES1_LANG.LANG_HCES1_LANG = :lang)
				LEFT JOIN FGCSUB CSUB ON (CSUB.EMP_CSUB = ASIGL0.EMP_ASIGL0 AND CSUB.sub_CSUB = ASIGL0.SUB_ASIGL0 AND REF_CSUB = ASIGL0.REF_ASIGL0)

                $this->join_filter" ;
        $sql_pre = "FROM FGASIGL0 ASIGL0
                $sql_join
                WHERE
                        ASIGL0.EMP_ASIGL0 = :emp


                AND
                        ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\"
                AND
                        ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\"
                AND
                        SUB.SUBC_SUB IN ('S','H' $admin)
                AND
                    OCULTO_ASIGL0 = 'N'
               /* si la subasta es de tipo O los lotes deben estar activos */
              AND (SUB.TIPO_SUB != 'P' or (TRUNC(ASIGL0.FINI_ASIGL0) < TRUNC(SYSDATE) or (ASIGL0.FINI_ASIGL0 = TRUNC(SYSDATE) AND  ASIGL0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) )
                     and (( SUB.TIPO_SUB !='P') or ASIGL0.cerrado_asigl0 ='N')
                $this->where_filter
                ";

        $sql_pre_new = " AND ASIGL0.EMP_ASIGL0 = :emp
                AND
                        ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\"
                AND
                        ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\"
                AND
                        SUB.SUBC_SUB IN ('S','H' $admin)
                AND
                    OCULTO_ASIGL0 = 'N'
               /* si la subasta es de tipo O los lotes deben estar activos */
              AND (SUB.TIPO_SUB != 'P' or (TRUNC(ASIGL0.FINI_ASIGL0) < TRUNC(SYSDATE) or (ASIGL0.FINI_ASIGL0 = TRUNC(SYSDATE) AND  ASIGL0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) )
                     and (( SUB.TIPO_SUB !='P') or ASIGL0.cerrado_asigl0 ='N')
                $this->where_filter"
                ;

                /* 23/03/2018 solo lo tiene que hacer para las permane
              AND (SUB.TIPO_SUB NOT IN ('O','P') or (TRUNC(ASIGL0.FINI_ASIGL0) < TRUNC(SYSDATE) or (ASIGL0.FINI_ASIGL0 = TRUNC(SYSDATE) AND  ASIGL0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) )
                     and (( SUB.TIPO_SUB !='O' and SUB.TIPO_SUB !='P') or ASIGL0.cerrado_asigl0 ='N')   */
         if ($type == "count"){
            $sql =" SELECT count(ASIGL0.REF_ASIGL0)  as cuantos
                    $sql_pre
                    ";


            if($cache_sql){
                //quitamos espacios en blanco
                $name_cache = "count_". str_replace(" ","", $this->where_filter. $this->join_filter);

                $res = \CacheLib::useCache($name_cache,$sql, $params);
            }else{
                $res = DB::select($sql, $params);
            }
            return head($res)->cuantos;
        }elseif($type == "small"){
            $sql ="
                    SELECT
                    $this->select_filter

                    $sql_pre

                    $group_by
                        $order_by
                    ";

            if($cache_sql){
                //quitamos espacios en blanco
                $name_cache ="small_". str_replace(" ","", $this->where_filter. $this->join_filter.$this->select_filter);

                $lotes = \CacheLib::useCache($name_cache,$sql, $params);
            }else{
                $lotes = DB::select($sql, $params);
            }
            return $lotes;

        }elseif($type == "normal"){
            $sql = "SELECT * FROM (
                    SELECT T.RN,
                    ".$this->select_filter."
                    HCES1.REF_HCES1, HCES1.ORDEN_HCES1, HCES1.FAC_HCES1, HCES1.LIC_HCES1, HCES1.LIN_HCES1, HCES1.NUM_HCES1, HCES1.SUB_HCES1, HCES1.CONTEXTRA_HCES1,
                    CSUB.HIMP_CSUB,
                    HCES1.IMPLIC_HCES1, HCES1.descdet_HCES1,
                    NVL(HCES1_LANG.DESCWEB_HCES1_LANG, HCES1.DESCWEB_HCES1) DESCWEB_HCES1,
                    NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1) DESC_HCES1,
                    NVL(HCES1_LANG.WEBFRIEND_HCES1_LANG, HCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1, NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.TITULO_HCES1) TITULO_HCES1,

                    ASIGL0.REF_ASIGL0, ASIGL0.NUMHCES_ASIGL0, ASIGL0.IMPSALHCES_ASIGL0, ASIGL0.CERRADO_ASIGL0, ASIGL0.REMATE_ASIGL0, ASIGL0.COMPRA_ASIGL0, ASIGL0.COMLHCES_ASIGL0,
                    ASIGL0.IMPTASH_ASIGL0, ASIGL0.IMPRES_ASIGL0, ASIGL0.IMPTAS_ASIGL0, ASIGL0.RETIRADO_ASIGL0, ASIGL0.DESTACADO_ASIGL0,ASIGL0.IMPADJ_ASIGL0,
                    ASIGL0.DESADJU_ASIGL0, ASIGL0.IMPSALWEB_ASIGL0, ASIGL0.OCULTARPS_ASIGL0,
                    SUB.COD_SUB, SUB.DES_SUB, SUB.TIPO_SUB, SUB.SUBC_SUB,SUB.SUBABIERTA_SUB, SUB.OPCIONCAR_SUB,
                    AUC.\"id_auc_sessions\", AUC.\"name\", AUC.\"start\" as START_SESSION, AUC.\"end\" as END_SESSION,
                    AUC.\"orders_start\", AUC.\"orders_end\",
                    (CASE WHEN FFIN_ASIGL0 IS NOT NULL AND HFIN_ASIGL0 IS NOT NULL
                    THEN REPLACE(TO_DATE(TO_CHAR(FFIN_ASIGL0, 'DD/MM/YY') || ' ' || HFIN_ASIGL0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                    ELSE null END) close_at,
                    GREATEST (implic_hces1, IMPSALHCES_ASIGL0) as max_puja, OFERTA_ASIGL0

                    FROM (
                            SELECT
                            ROWNUM RN, t.*
                            from (select
                                EMP_ASIGL0,SUB_ASIGL0,REF_ASIGL0

                                $sql_pre
                                $order_by
                            )T )T

                    INNER JOIN FGASIGL0 ASIGL0 ON (ASIGL0.EMP_ASIGL0 = T.EMP_ASIGL0 AND ASIGL0.SUB_ASIGL0 = T.SUB_ASIGL0 AND ASIGL0.REF_ASIGL0 = T.REF_ASIGL0)
                    $sql_join
                    ".self::getOffset($this->page, $this->itemsPerPage). $sql_pre_new .") ORDER BY RN ASC" ;

            if($cache_sql){
                //quitamos espacios en blanco
                $name_cache ="normal_". str_replace(" ","", $this->where_filter. $this->join_filter.$this->select_filter.$order_by.$this->page.$this->itemsPerPage);

                $lotes = \CacheLib::useCache($name_cache,$sql, $params);
            }else{
                $lotes = DB::select($sql, $params);

               //$lotes = DB::select($sql, $params); $lotes = DB::select($sql, $params);
            }
             return $lotes;

        }
        /*
        $lotes = DB::select($sql, $params);

        return $lotes;
         */
    }


	#03-02-2021 comento código por que parece que no se usa, si al fina lse usa pasar la variable $this->estado_lotes por bindings
	/*
     public function getSubasta_filters($included_id_auc_session = true)
        {

        $params = array(
                'emp'       =>  Config::get('app.emp'),
                'cod_sub'   =>  $this->cod
                );


        $estado_lotes="";
        //si tiene la config de cerrar lote
        if (Config::get('app.hide_sold_lot')){
            $estado_lotes.= "p.CERRADO_ASIGL0 = 'N' AND ";

        }

        # Filtrado por estado de lote CERRADO_ASIGL0
        if(!empty($this->estado_lotes)) {
            $estado_lotes .= "p.CERRADO_ASIGL0 = '".$this->estado_lotes."' AND ";
        }


        //no hacemos caso a los where por que entocnes n ocontaria todos los elementos
        $where_filter = "";
        if($included_id_auc_session)
        {
            $where_filter=" AND auc.\"id_auc_sessions\" = ".$this->id_auc_sessions;
        }

         $select = "p.ref_asigl0,lotes.REF_HCES1, lotes.num_hces1, lotes.titulo_hces1";
         $select .= " ,t_values.\"period\", t_values.\"subperiod_1\"  ";
        $sql="SELECT  " .$select."

        FROM FGASIGL0 p
        INNER JOIN FGHCES1 lotes ON   (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)
        INNER JOIN FGSUB subastas ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
        JOIN \"auc_sessions\" auc ON (auc.\"auction\" = :cod_sub)
        LEFT JOIN FGORTSEC1 cat
                        ON (cat.SEC_ORTSEC1 = lotes.SEC_HCES1 AND cat.SUB_ORTSEC1 = :cod_sub AND cat.EMP_ORTSEC1 = :emp)

        left JOIN \"object_types_values\" t_values on t_values.\"transfer_sheet_number\"  = lotes.num_hces1  and t_values.\"transfer_sheet_line\" = lotes.lin_hces1
        left join \"auc_custom_fields_values\" f_values on f_values.\"field\" = 'period' and f_values.\"value\" =  t_values.\"period\"
        left join \"auc_custom_fields_values\" f_values_sub on f_values_sub.\"field\" = 'subperiod' and f_values_sub.\"value\" =  t_values.\"subperiod_1\"


        WHERE ".$estado_lotes." p.EMP_ASIGL0 = :emp AND subastas.COD_SUB = :cod_sub " . $where_filter."


        AND lotes.REF_HCES1 >= auc.\"init_lot\" AND lotes.REF_HCES1 <= auc.\"end_lot\"
        AND subastas.TIPO_SUB IN (".$this->tipo.") AND subastas.SUBC_SUB IN ('S', 'H','A')

        AND (subastas.tipo_sub not in ('O','P') or   TRUNC(p.fini_asigl0 ) < TRUNC(SYSDATE) or (TRUNC(p.fini_asigl0 ) = TRUNC(SYSDATE) AND  p.hini_asigl0 <= to_char(sysdate, 'HH24:MI:SS')) )
        order by f_values.\"order\",f_values_sub.\"order\", t_values.\"period\", t_values.\"subperiod_1\"
         " ;

        $subasta = DB::select($sql, $params);

        return $subasta;

    }
*/

	public function getInfSubasta()
	{
		$params = array();
		$cacheName = "info_subasta_" . $this->cod;

		$sql_where = '';
		if (!empty($this->id_auc_sessions)) {
			$cacheName .= "_".$this->id_auc_sessions;
			$sql_where = " AND auc.\"id_auc_sessions\" = " . $this->id_auc_sessions;
		}

		$join = "";
		/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
		if (Config::get("app.restrictVisibility")) {
			//si no hay usuario logeado devolvemos vacio
			if (empty(Session::get('user.cod'))) {
				return null;
			}

			$join = $this->restrictVisibilityAuction("join");
			$sql_where =  $sql_where . "  " . $this->restrictVisibilityAuction("where");
			$params['codCli'] = Session::get('user.cod');
		}

		if(Config::get('app.agrsub')) {
			$agrsub = Config::get('app.agrsub');
			$sql_where .= " AND sub.AGRSUB_SUB = :agrsub";
			$params['agrsub'] = $agrsub;
		}


		$sql = "SELECT sub.COD_SUB cod_sub, sub.EMP_SUB, sub.SUBC_SUB, sub.tipo_sub, sub.SUBC_SUB, sub.tipo_sub,sub.subabierta_sub,sub.opcioncar_sub,
				sub.subastatr_sub,sub.COMPRAWEB_SUB,
				NVL(fgsublang.DES_SUB_LANG,  sub.DES_SUB) des_sub,
				NVL(fgsublang.EXPOFECHAS_SUB_LANG,  sub.expofechas_sub) expofechas_sub,
				NVL(fgsublang.EXPOHORARIO_SUB_LANG,  sub.expohorario_sub) expohorario_sub,
				NVL(fgsublang.EXPOLOCAL_SUB_LANG,  sub.expolocal_sub) expolocal_sub,
				NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub) sesfechas_sub,
				NVL(fgsublang.SESHORARIO_SUB_LANG,  sub.seshorario_sub) seshorario_sub,
				NVL(fgsublang.SESLOCAL_SUB_LANG,  sub.seslocal_sub) seslocal_sub,
				NVL(fgsublang.descdet_SUB_LANG,  sub.descdet_sub) descdet_sub,
				NVL(fgsublang.obs_sub_lang,  sub.obs_sub) obs_sub,
				NVL(auc_lang.\"info_lang\", auc.\"info\") session_info,
				sub.sesmaps_sub,sub.expomaps_sub,
				auc.*,
				NVL(auc_lang.\"name_lang\", auc.\"name\") name,
				auc_lang.\"upCatalogo_lang\" upcatalogo_lang,auc_lang.\"upPrecioRealizado_lang\" uppreciorealizado_lang, auc_lang.\"upManualUso_lang\" upmanualuso_lang
				FROM FGSUB sub
				LEFT JOIN FGSUB_LANG fgsublang ON (sub.EMP_SUB = fgsublang.EMP_SUB_LANG AND sub.COD_SUB = fgsublang.COD_SUB_LANG AND  fgsublang.LANG_SUB_LANG = :lang)
				JOIN \"auc_sessions\" auc ON (auc.\"auction\" = :cod_sub AND auc.\"company\" = :emp)
				LEFT JOIN \"auc_sessions_lang\" auc_lang
					ON (auc_lang.\"id_auc_session_lang\" = auc.\"id_auc_sessions\" and auc_lang.\"company_lang\" = :emp and auc_lang.\"lang_auc_sessions_lang\" = :lang)

				$join

				where sub.EMP_SUB = :emp
				$sql_where
				and sub.cod_sub = :cod_sub";

		$params['emp'] = Config::get('app.emp');
		$params['cod_sub'] = $this->cod;
		$params['lang'] = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

		//pongo solo un minuto por que no tarda mucho y es posible que necesiten cambiar la subasta de tipo carrito a otro
		$auctions = CacheLib::useCache($cacheName, $sql, $params,1);

		if (!empty($auctions)) {
			return head($auctions);
		} else {
			return NULL;
		}
	}



     # Lotes de una subasta según su código de subasta (COD_SUB)
    public function getSubastaMenu($is_counting = FALSE)
    {
      //return null;
        # Parametros a parsear en el SQL con PDO
        $params = array(
                'emp'       =>  Config::get('app.emp'),
                'cod_sub'   =>  $this->cod
                );

        # Si recibimos la categoria le añadimos un parametro al PDO
        if(self::getCat($this->cat)) {
            $params['cat'] = $this->cat;
        }

        # Filtrado por estado de lote CERRADO_ASIGL0
        if(!empty($this->estado_lotes)) {
            $estado_lotes = "p.CERRADO_ASIGL0 = '".$this->estado_lotes."' AND ";
        } else {
            $estado_lotes = false;
        }

        if (empty($this->order_by_values)) {
            $this->order_by_values = " lotes.REF_HCES1";
        }

        $sql = "SELECT * FROM (
            SELECT rownum rn, pu.* FROM (
              SELECT cat.SEC_ORTSEC1, cat0.DES_ORTSEC0 categoria, p.*, subastas.*, lotes.*, csub.FAC_CSUB, ws.ESTADO, ws.REANUDACION, auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name,

               (CASE WHEN p.ffin_asigl0 IS NOT NULL AND p.hfin_asigl0 IS NOT NULL
                        THEN REPLACE(TO_DATE(TO_CHAR(p.ffin_asigl0, 'DD/MM/YY') || ' ' || p.hfin_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                        ELSE null END) close_at,

              /*Puja máxima por lote*/
                (SELECT
                    (CASE WHEN MAX(asigl1.imp_asigl1) IS NOT NULL
                        THEN MAX(asigl1.imp_asigl1)
                        ELSE asigl0.impsalhces_asigl0 END)
                    FROM FGASIGL0 asigl0
                        LEFT JOIN FGASIGL1 asigl1
                        ON (asigl1.ref_asigl1 = asigl0.REF_ASIGL0 AND asigl1.sub_asigl1 = asigl0.SUB_ASIGL0)
                    WHERE asigl0.sub_asigl0 = :cod_sub AND lotes.EMP_HCES1= asigl0.EMP_ASIGL0 AND lotes.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = asigl0.LINHCES_ASIGL0
                    GROUP BY asigl0.impsalhces_asigl0) as max_puja

                FROM FGASIGL0 p

                    INNER JOIN FGHCES1 lotes ON   (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)
                    INNER JOIN FGSUB subastas
                        ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)

                    JOIN \"auc_sessions\" auc ON (auc.\"auction\" = :cod_sub AND auc.\"company\" = :emp)

                    LEFT JOIN FGHCES1SR lotessr
                      ON (lotessr.LIN_HCES1SR = lotes.LIN_HCES1 AND lotessr.NUM_HCES1SR = lotes.NUM_HCES1 AND lotessr.EMP_HCES1SR = lotes.EMP_HCES1)

                    /* Categorias */
                    LEFT JOIN FGORTSEC1 cat
                        ON (cat.SEC_ORTSEC1 = lotes.SEC_HCES1 AND cat.SUB_ORTSEC1 = :cod_sub AND cat.EMP_ORTSEC1 = :emp)
                    LEFT JOIN FGORTSEC0 cat0
                      ON (cat.LIN_ORTSEC1 = cat0.LIN_ORTSEC0 AND cat0.SUB_ORTSEC0 = :cod_sub AND cat0.EMP_ORTSEC0 = :emp)

                    LEFT JOIN FGCSUB csub ON (csub.SUB_CSUB = subastas.COD_SUB AND csub.EMP_CSUB = :emp AND csub.REF_CSUB = lotes.REF_HCES1)

                    /* Tipo de estado en subastas a tiempo real por si esta pausada y cuando reanuda */
                    LEFT JOIN WEB_SUBASTAS ws ON (ws.ID_SUB = :cod_sub AND ws.ID_EMP = :emp AND ws.session_reference = auc.\"reference\")

                    /*p.CERRADO_ASIGL0 = 'N' AND*/
                    /*Descarta los lotes facturados*/
                    /*NOT EXISTS( SELECT FAC_CSUB FROM FGCSUB csub WHERE csub.SUB_CSUB = subastas.COD_SUB AND csub.EMP_CSUB = :emp AND csub.REF_CSUB = lotes.REF_HCES1) AND*/

                    WHERE ".$estado_lotes." p.EMP_ASIGL0 = :emp AND
                    subastas.COD_SUB = :cod_sub ".$this->where_filter."
                    /* Limita el resultado de lotes segun los parametros DREF a HREF */
                    /*AND lotes.REF_HCES1 >= subastas.DREF_SUB */
                    AND lotes.REF_HCES1 >= auc.\"init_lot\"
                    AND lotes.REF_HCES1 <= auc.\"end_lot\"

                    /*AND lotes.REF_HCES1 <=
                    (CASE WHEN subastas.HREF_SUB > 0
                        THEN subastas.HREF_SUB
                        ELSE 99999999999 END)*/
                    AND

                    subastas.TIPO_SUB IN (".$this->tipo.") AND subastas.SUBC_SUB IN ('S', 'H', 'A')
                       ".self::getCat($this->cat)."
                       ORDER BY ".$this->order_by_values."
                      ) pu )".self::getOffset($this->page, $this->itemsPerPage);

            $subasta = DB::select($sql, $params);

        return $subasta;
    }

    public function allSubasta()
    {

        return DB::select("SELECT * FROM (
                        SELECT rownum rn, pu.* FROM (
                                SELECT p.*, subastas.*, lotes.* , auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name
                                FROM FGASIGL0 p
                                      INNER JOIN FGHCES1 lotes ON   (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)
                                      INNER JOIN FGSUB subastas ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
                                      JOIN \"auc_sessions\" auc ON (auc.\"auction\" = subastas.COD_SUB AND auc.\"company\" = :emp)
                                        WHERE p.EMP_ASIGL0 = :emp
                                        AND subastas.TIPO_SUB IN (".$this->tipo.") ) pu
                                )".self::getOffset($this->page, $this->itemsPerPage),
                                array(
                                    'emp'   =>  App('config')['app']['emp']
                                    )
                                );

    }

	//copia de getPujas pero que envia menos información
	public function getPujas($licit = false, $cod_sub = false, $num = 100)
	{
		if (!$cod_sub) {
			$cod_sub = $this->cod;
		}

		$params = array(
			'emp'       => Config::get('app.emp'),
			'cod_sub'   => $cod_sub,
			'ref'       => $this->ref
		);

		if ($licit) {
			$params['licit']    = $licit;
			$where_licit        = " AND pujas1.LICIT_ASIGL1 = :licit";
		} else {
			$where_licit        = false;
		}

		$addUserInfo = "";
		$joinUserInfo = "";
		if(Config::get('app.show_user_info_in_bids', false)) {
			$gemp = Config::get('app.gemp');
			$addUserInfo = ", fxcli.codpais_cli";
			$joinUserInfo = "LEFT JOIN fxcli ON (licitadores.CLI_LICIT = fxcli.COD_CLI AND fxcli.GEMP_CLI = :gemp)";
			$params['gemp'] = $gemp;
		}

		//ES IMPORTANTE QUE SI HAY DOS PUJAS IGUALES COJA PRIMERO LA ULTIMA, PARA ESO HACE FALTA MIRAR LA FECHA Y HORA Y LIN_ASIGL1 POR SI LA FECHA Y HORA SON IGUALES.
		$pujas = DB::select(
			"SELECT * FROM (
				SELECT * FROM (
					  SELECT rownum rn, pu.* FROM (
						  SELECT
						  	licitadores.SUB_LICIT cod_sub,
							licitadores.cod_licit,
							pujas1.ref_asigl1,
							pujas1.lin_asigl1,
							pujas1.imp_asigl1,
							pujas1.pujrep_asigl1,
							concat(SUBSTR(pujas1.fec_asigl1, 1, 11),
							pujas1.hora_asigl1) as bid_date,
							type_asigl1,
							licitadores.cli_licit
							{$addUserInfo}
						  FROM FGASIGL1 pujas1
						  JOIN FGLICIT licitadores ON (licitadores.COD_LICIT = pujas1.LICIT_ASIGL1 AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod_sub)
						  {$joinUserInfo}
						  WHERE pujas1.SUB_ASIGL1 = :cod_sub AND pujas1.EMP_ASIGL1 = :emp AND pujas1.REF_ASIGL1 = :ref $where_licit
						  ORDER BY IMP_ASIGL1 DESC, TO_DATE(TO_CHAR(pujas1.FEC_ASIGL1, 'DD/MM/YY') || ' ' || pujas1.HORA_ASIGL1, 'DD/MM/YY HH24:MI:SS') DESC, LIN_ASIGL1 DESC
					  ) pu
					) where ROWNUM <= $num)t" . self::getOffset($this->page, $this->itemsPerPage),
			$params
		);

		if (!$licit) {
			foreach ($pujas as $key => $value) {
				$pujas[$key]->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($value->imp_asigl1);
			}
		} elseif (!empty($pujas[0])) {
			$pujas[0]->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($pujas[0]->imp_asigl1);
		}

		return $pujas;
	}

   	#Listado de pujas inversas
     public function getPujasInversas($licit = false,$cod_sub = false,   $num = 100)
    {


        $params = array(
            'emp'       => Config::get('app.emp'),
            'cod_sub'   => $this->cod,
            'ref'       => $this->ref
        );


        if($licit) {
            $params['licit']    = $licit;
            $where_licit        = " AND pujas1.LICIT_ASIGL1 = :licit";
        } else {
            $where_licit        = false;
        }

        #OJO este listado es incompatible a dejar pujar por un importe que ya exista, si en algun momento se quiere hacer pujas inversas a ciegas, o se bloquean las pujas iguales o se crea un nuevo listado ya que interfieren con como se ha de ordenar las ordenes
        $pujas = DB::select("SELECT * FROM (
            SELECT * FROM (
                  SELECT rownum rn, pu.* FROM (
                      SELECT licitadores.SUB_LICIT cod_sub ,licitadores.cod_licit, pujas1.ref_asigl1, pujas1.lin_asigl1, pujas1.imp_asigl1,pujas1.pujrep_asigl1, pujas1.fec_asigl1 as bid_date, type_asigl1, licitadores.cli_licit FROM FGASIGL1 pujas1
                      JOIN FGLICIT licitadores ON (licitadores.COD_LICIT = pujas1.LICIT_ASIGL1 AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod_sub)

                      WHERE pujas1.SUB_ASIGL1 = :cod_sub AND pujas1.EMP_ASIGL1 = :emp AND pujas1.REF_ASIGL1 = :ref $where_licit
                      ORDER BY IMP_ASIGL1 ASC, fec_asigl1 DESC, LIN_ASIGL1 DESC
                  ) pu
                ) where ROWNUM <= $num)t".self::getOffset($this->page, $this->itemsPerPage)
                ,$params
            );

        if(!$licit) {
            foreach ($pujas as $key => $value) {
                $pujas[$key]->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($value->imp_asigl1);
            }
        } elseif (!empty($pujas[0])) {
            $pujas[0]->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($pujas[0]->imp_asigl1);
        }

        return $pujas;
	}

	function getPujasWithAuction($byLicit = null){

		$fgasigl1 = FgAsigl1::select([
			'ref_asigl1',
			'lin_asigl1',
			'licit_asigl1',
			'imp_asigl1',
			'fec_asigl1',
			'cod_licit',
			'cli_licit',
			'rsoc_licit',
			'nom_cli',
			'rsoc_cli',
			'fisjur_cli',
			'nom_representados',
		])
			->joinCli()
			->leftJoinRepresentedLicit()
			->where('sub_asigl1', $this->cod);

		if (!empty($byLicit)) {
			$fgasigl1->where('licit_asigl1', $byLicit);
		}

		return $fgasigl1->orderBy('ref_asigl1')->orderBy('fec_asigl1')->get();
	}


    public function getFamilies()
    {
        $sql = "select sec1.LIN_ORTSEC1,  sec2.DES_ORTSEC0 as des_sec, count(hces1.ref_hces1) num  from fxsec fx
                JOIN FGORTSEC1 sec1 ON (sec1.SEC_ORTSEC1 = fx.COD_SEC)
                JOIN FGHCES1 hces1 ON (hces1.SEC_HCES1 = sec1.SEC_ORTSEC1 AND hces1.SUB_HCES1 = sec1.SUB_ORTSEC1)
                JOIN FGORTSEC0 sec2 ON (sec2.sub_ORTSEC0 =sec1.sub_ORTSEC1 AND sec2.EMP_ORTSEC0 = :emp  and sec2.orden_ortsec0 =sec1.orden_ortsec1 )
                JOIN \"auc_sessions\" auc ON (auc.\"auction\" = sec1.SUB_ORTSEC1 AND auc.\"company\" = :emp)
                where sec1.EMP_ORTSEC1 = :emp AND
                sec1.SUB_ORTSEC1 = :cod_sub AND
                fx.BAJAT_SEC = 'N' AND
                fx.GEMP_SEC = :gemp
                AND  auc.\"id_auc_sessions\" = :id_auc_sessions
                AND REF_HCES1 between auc.\"init_lot\" AND auc.\"end_lot\"
            ".$this->where_filter."
             GROUP BY  sec1.LIN_ORTSEC1,sec2.DES_ORTSEC0 ORDER BY sec2.DES_ORTSEC0";

        return DB::select($sql,array(
                            'emp'             => Config::get('app.emp'),
                            'gemp'            =>  Config::get('app.gemp'),
                            'cod_sub'         => $this->cod,
                            'id_auc_sessions' => $this->id_auc_sessions
                            ));
    }

    public function getMaterials($from, $lin = false)
    {
        return array();
        $sql = "select distinct
            fghces1sr.APAR".$from."_HCES1SR, fgsrapar.DES_SRAPAR, fgsrapar.sec_srapar, fgsrapar.num_srapar, fghces1.LIN_HCES1
            from fghces1,fghces1sr,fgsrapar, fxsec, fgortsec1
            where EMP_HCES1 = :emp
                and SUB_HCES1 = :cod_sub
                and fghces1sr.emp_hces1sr = fghces1.EMP_HCES1
                and fghces1sr.NUM_HCES1SR = fghces1.NUM_HCES1
                and fghces1sr.LIN_HCES1SR = fghces1.LIN_HCES1
                and fgsrapar.EMP_SRAPAR = fghces1.EMP_HCES1
                and fgsrapar.SEC_SRAPAR = fghces1.SEC_HCES1
                and fgsrapar.NUM_SRAPAR = fghces1sr.APAR".$from."_HCES1SR
                and fgsrapar.UCELDA".$from."_SRAPAR = 'S'
                and fxsec.GEMP_SEC = :gemp
                and fxsec.COD_SEC = fghces1.SEC_HCES1
                and fxsec.BAJAT_SEC = 'N'
                and fgortsec1.SEC_ORTSEC1 = fghces1.SEC_HCES1
                and fgortsec1.SUB_ORTSEC1 = fghces1.SUB_HCES1
                ".$this->where_filter."
            order by 2";

        $mats = DB::select($sql,
                        array(
                            'emp'         => Config::get('app.emp'),
                            'gemp'        => Config::get('app.gemp'),
                            'cod_sub'     => $this->cod
                            )
                        );

        return $mats;

    }

    public function getCodLicitFromCli()
    {
        $licit = DB::select("SELECT COD_LICIT FROM FGLICIT WHERE CLI_LICIT = :cli_licit AND EMP_LICIT = :emp",
                        array(
                            'emp'           => Config::get('app.emp'),
                            'cli_licit'     => $this->licit,
                            )
                        );
        $data = array();

        foreach ($licit as $key => $value) {
            $data[] = $value->cod_licit;
        }

        return $data;
    }
//OJO, si se añade algun campo de texto se debe pasar por Cleanstr
    public function getAllSubastaLicitPujas($cod_sub = false, $ref = false)
    {
        $tail = '';
        $params = array(
            'emp'       => Config::get('app.emp'),
            'cli_licit' => $this->licit
            //'cod_sub'   => $this->cod,
        );

        if(!empty($cod_sub)) {
            $params['cod_sub'] = $cod_sub;
            $tail .= ' AND a.SUB_ASIGL1 = :cod_sub';
        }

        if(!empty($ref)) {
            $params['ref'] = $ref;
            $tail .= ' AND a.REF_ASIGL1 = :ref';
        }



        $sql = "
        SELECT * FROM (
            SELECT rownum rn, pu.* FROM (
                select a.*, b.*, lotes.titulo_hces1, lotes.num_hces1, lotes.ref_hces1, lotes.lin_hces1 from fgasigl1 a
                JOIN fgasigl0 asigl0 ON (asigl0.emp_asigl0 = a.emp_asigl1 AND asigl0.SUB_ASIGL0 = a.SUB_ASIGL1 AND asigl0.REF_ASIGL0 = a.REF_ASIGL1)
                JOIN fglicit b ON (b.EMP_LICIT = :emp AND b.SUB_LICIT = a.SUB_ASIGL1 AND b.COD_LICIT = a.LICIT_ASIGL1)
                JOIN FGHCES1 lotes ON (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = asigl0.LINHCES_ASIGL0)
                    where a.EMP_ASIGL1 = :emp $tail
                    and b.CLI_LICIT = :cli_licit
                    order by a.SUB_ASIGL1, a.REF_ASIGL1, a.IMP_ASIGL1
            )
        pu ) ". ToolsServiceProvider::getOffset($this->page, $this->itemsPerPage)."";

        $pujas = DB::select($sql, $params);
        $strLib = new StrLib();
        foreach ($pujas as $key => $value) {
            $pujas[$key]->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($value->imp_asigl1);
            $pujas[$key]->imagen = $this->getLoteImg($value);
            $pujas[$key]->date = ToolsServiceProvider::formatDate($value->fec_asigl1, $value->hora_asigl1);
            $pujas[$key]->titulo_hces1 = $strLib->CleanStr($value->titulo_hces1);
            $pujas[$key]->rsoc_licit = $strLib->CleanStr($value->rsoc_licit);
        }

        return $pujas;
    }
    public function getMaxOrden($cod_sub = false, $ref = false){
        $params = array(
            'emp'       => Config::get('app.emp'),
            'cli_licit'     => $this->licit,
            'cod_sub'   => $cod_sub,
            'ref' => $ref,
        );

        $sql="select a.himp_orlic
                from FGORLIC a
                JOIN fgasigl0 asigl0 ON (asigl0.emp_asigl0 = a.emp_orlic AND asigl0.SUB_ASIGL0 = a.SUB_ORLIC AND asigl0.REF_ASIGL0 = a.REF_ORLIC)
                JOIN fglicit b ON (b.EMP_LICIT = :emp AND b.SUB_LICIT = a.SUB_ORLIC AND b.COD_LICIT = a.LICIT_ORLIC)
                JOIN FGSUB SUB ON  SUB.EMP_SUB = a.emp_orlic AND SUB.COD_SUB = a.SUB_ORLIC
                JOIN FGHCES1 lotes ON   (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = asigl0.LINHCES_ASIGL0)

                    where a.EMP_ORLIC = :emp
                    AND SUB.SUBC_SUB IN ('S','A')
                    AND a.REF_ORLIC = :ref
                    AND a.SUB_ORLIC = :cod_sub
                   and b.CLI_LICIT = :cli_licit
                    order by a.SUB_ORLIC, a.REF_ORLIC";

        $orden = DB::select($sql, $params);

        if(count($orden) > 0){
            return $orden[0]->himp_orlic;
        }else{
            return 0;
        }
    }

    public function getAllBidsAndOrders($favorites = false, $lotsColsed = false, $whereFilters = [])
	{
        $sql_favorites = "";
        $sql_join_favorites = "";
		$orderby = " fec desc, hora desc";

		$whereClose = "AND asigl0.cerrado_asigl0 != 'S'";
		if($lotsColsed){
			$whereClose = "";
		}

		$showClosedLotOrdersInPanel = " AND asigl0.cerrado_asigl0 != 'S'";
		if(config('app.showClosedLotOrdersInPanel', 0)){
			$showClosedLotOrdersInPanel = "";
		}

        $showLotsAllSubcSubAuctions = " AND SUB.SUBC_SUB IN ('S','A')";
        if(config('app.showLotsAllSubcSubAuctions', 0)){
            $showLotsAllSubcSubAuctions = "";
        }

        if(!empty(Config::get('app.orderby_allbidsandorders'))){
              $orderby = Config::get('app.orderby_allbidsandorders');
		}

		$whereAuctions = "";
		if(!empty($whereFilters['cods_sub'])){
			$whereAuctions = " AND SUB.COD_SUB IN ('" . implode("', '", $whereFilters['cods_sub']) . "')";
		}

		if(Config::get('app.agrsub')) {
			$agrsub = Config::get('app.agrsub');
			$whereAuctions .= " AND SUB.AGRSUB_SUB = '$agrsub'";
		}

        if($favorites){
            $sql_join_favorites = "join web_favorites fav on (fav.id_emp=asigl0.emp_asigl0 and fav.id_sub = asigl0.SUB_ASIGL0 and fav.id_ref = asigl0.REF_ASIGL0 and fav.cod_cli= :cli_licit)";
            $sql_favorites = " UNION
                                    select  b.COD_LICIT, \"id_auc_sessions\",auc.\"name\" as session_name, auc.\"start\" as session_start, auc.\"end\" as session_end, impsalhces_asigl0,retirado_asigl0, cerrado_asigl0,  ref_asigl0,tipo_sub,cod_sub,0 imp, null tipop_orlic, a.fecha fec,  TO_CHAR(a.fecha,'HH24:MM:SS')  hora,NUMHCES_ASIGL0, LINHCES_ASIGL0
                                    from web_favorites a
                                    JOIN fgasigl0 asigl0 ON (asigl0.emp_asigl0 = a.id_emp AND asigl0.SUB_ASIGL0 = a.id_sub AND asigl0.REF_ASIGL0 = a.id_ref)
                                     JOIN fglicit b ON (b.EMP_LICIT = a.id_emp AND b.SUB_LICIT = a.id_sub AND b.CLI_LICIT = a.cod_cli)
                                    JOIN FGSUB SUB ON  SUB.EMP_SUB = a.id_emp AND SUB.COD_SUB = a.id_sub
                                    JOIN  \"auc_sessions\" auc ON  auc.\"company\" = asigl0.emp_asigl0 and auc.\"auction\" = SUB.COD_SUB and auc.\"init_lot\" <= asigl0.REF_ASIGL0 and auc.\"end_lot\" >= asigl0.REF_ASIGL0
                                    left join fgorlic orlic on (orlic.emp_orlic = a.id_emp and orlic.sub_orlic = a.id_sub and orlic.ref_orlic  = a.id_ref and orlic.licit_orlic = b.cod_licit)
                                    left join fgasigl1 asigl1 on (asigl1.emp_asigl1 = a.id_emp and asigl1.sub_asigl1 = a.id_sub and asigl1.ref_asigl1  = a.id_ref and asigl1.licit_asigl1 = b.cod_licit)
                                    where a.id_emp = :emp
										$showLotsAllSubcSubAuctions
										$whereAuctions
                                        AND a.cod_cli = :cli_licit
                                        AND b.CLI_LICIT < :subalia_min_licit
                                        $whereClose
                                        and orlic.emp_orlic is null
                                        and asigl1.emp_asigl1 is null";

        }

        $params = array(
            'emp'       => Config::get('app.emp'),
            'cli_licit'     => $this->licit,
            'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')),
            'subalia_min_licit' =>  !empty(Config::get('app.subalia_min_licit'))? Config::get('app.subalia_min_licit') : 10000
            //'cod_sub'   => $this->cod,
        );

        // DEBEMOS COGER EL LICITADOR GANADOR, PERO SOLO NOS SIRVE PARA SUBASTAS TIPO ONLINE, O LAS W QUE YA HAN EMPEZADO.
        $licit_bid_winner_sql = "(select licit_asigl1
            from  fgasigl1 asigl1 where  asigl1.emp_asigl1 = :emp  AND asigl1.SUB_ASIGL1 = cod_sub  AND REF_ASIGL1 = REF_ASIGL0
             and lin_asigl1 = (select max(lin_asigl1) from  fgasigl1 asigl1 where  asigl1.emp_asigl1 = :emp  AND asigl1.SUB_ASIGL1 = cod_sub  AND REF_ASIGL1 = REF_ASIGL0 AND  imp_asigl1= ( select max(imp_asigl1) from  fgasigl1 asigl1 where  asigl1.emp_asigl1 = :emp  AND asigl1.SUB_ASIGL1 = cod_sub  AND REF_ASIGL1 = REF_ASIGL0) )
            ) as licit_winner_bid, ";
        //miramso si hay una orden superior o igual pero antes en el tiempo, si no la hay cogemos el licitador del usuario, el max licit es solo para agrupar, nos serviría cualquier licitador que le gane
        $licit_order_winner_sql = "nvl((select max(FGORLIC.licit_orlic)
                                        from FGORLIC WHERE SUB_ORLIC =cod_sub AND REF_ORLIC=ref_asigl0 AND EMP_ORLIC=:emp and (FGORLIC.himp_orlic > T4.imp or (FGORLIC.himp_orlic = T4.imp AND FGORLIC.fec_orlic < T4.fec ))
                                        ), T4.COD_LICIT) as licit_winner_order,";

        //SOLO COJEMOS ORDENES DE SUBASTAS TIPO W SI LA SUBASTA NO HA EMPEZADO (NO TIENE PUJAS)
        $orders_sql =   "select

                        b.COD_LICIT, \"id_auc_sessions\",auc.\"name\" as session_name, auc.\"start\" as session_start, auc.\"end\" as session_end, impsalhces_asigl0,retirado_asigl0, cerrado_asigl0, ref_asigl0,tipo_sub,cod_sub,himp_orlic as imp, tipop_orlic, fec_orlic fec, hora_orlic hora,NUMHCES_ASIGL0, LINHCES_ASIGL0
                        from FGORLIC a
                        JOIN fgasigl0 asigl0 ON (asigl0.emp_asigl0 = a.emp_orlic AND asigl0.SUB_ASIGL0 = a.SUB_ORLIC AND asigl0.REF_ASIGL0 = a.REF_ORLIC)
                        JOIN fglicit b ON (b.EMP_LICIT = :emp AND b.SUB_LICIT = a.SUB_ORLIC AND b.COD_LICIT = a.LICIT_ORLIC)
                        JOIN FGSUB SUB ON  SUB.EMP_SUB = a.emp_orlic AND SUB.COD_SUB = a.SUB_ORLIC
                        JOIN  \"auc_sessions\" auc ON  auc.\"company\" = asigl0.emp_asigl0 and auc.\"auction\" = SUB.COD_SUB and auc.\"init_lot\" <= asigl0.REF_ASIGL0 and auc.\"end_lot\" >= asigl0.REF_ASIGL0
                        $sql_join_favorites
                        where a.EMP_ORLIC = :emp
                        AND asigl0.REF_ASIGL0 >= auc.\"init_lot\"
                        AND asigl0.REF_ASIGL0 <= auc.\"end_lot\"
                            $showLotsAllSubcSubAuctions
							$whereAuctions
                            AND b.CLI_LICIT = :cli_licit
                            AND b.COD_LICIT < :subalia_min_licit
                            $showClosedLotOrdersInPanel"; //  AND (TIPO_SUB != 'W' or auc.\"start\" > SYSDATE)"

        $bids_sql = "select

                    COD_LICIT, \"id_auc_sessions\",auc.\"name\" as session_name, auc.\"start\" as session_start, auc.\"end\" as session_end, impsalhces_asigl0,retirado_asigl0, cerrado_asigl0, ref_asigl0,tipo_sub,cod_sub,imp,null tipop_orlic,  fec,hora,NUMHCES_ASIGL0, LINHCES_ASIGL0 from
                    (
                        select b.COD_LICIT,retirado_asigl0, cerrado_asigl0, emp_asigl0,SUB_ASIGL0,ref_asigl0,max(fec_asigl1) fec, max(hora_asigl1) hora, max(imp_asigl1) as imp, max(NUMHCES_ASIGL0) as NUMHCES_ASIGL0, max(LINHCES_ASIGL0) as LINHCES_ASIGL0, max(impsalhces_asigl0) as impsalhces_asigl0       from  fgasigl1 a
                        JOIN fgasigl0 asigl0 ON (asigl0.emp_asigl0 = a.emp_asigl1 AND asigl0.SUB_ASIGL0 = a.SUB_ASIGL1 AND asigl0.REF_ASIGL0 = a.REF_ASIGL1)
                        JOIN fglicit b ON (b.EMP_LICIT = a.EMP_ASIGL1 AND b.SUB_LICIT = a.SUB_ASIGL1 AND b.COD_LICIT = a.LICIT_ASIGL1)
                        $sql_join_favorites
                        where a.EMP_ASIGL1 = :emp
                        and b.CLI_LICIT = :cli_licit
                        AND b.COD_LICIT < :subalia_min_licit
                        $whereClose
                        group by b.COD_LICIT, asigl0.emp_asigl0,asigl0.SUB_ASIGL0,asigl0.REF_ASIGL0,retirado_asigl0, cerrado_asigl0
                    ) pujas

                    JOIN FGSUB SUB ON  SUB.EMP_SUB =  pujas.emp_asigl0 AND SUB.COD_SUB = pujas.SUB_ASIGL0
                    JOIN  \"auc_sessions\" auc ON  auc.\"company\" = SUB.EMP_SUB and auc.\"auction\" =  SUB.COD_SUB and auc.\"init_lot\" <= ref_asigl0 and auc.\"end_lot\" >= ref_asigl0
                    where
						ref_asigl0 >= auc.\"init_lot\"
						AND ref_asigl0 <= auc.\"end_lot\"
						$showLotsAllSubcSubAuctions
						$whereAuctions";


        //hay tantos selects anidados por que al hacer un group by se perdian valores de RN y eso provocaria que se cargaran menos elementos por página
        $sql =  "SELECT
                $licit_bid_winner_sql
                $licit_order_winner_sql
                COD_LICIT,
                rn,lotes.implic_hces1,\"id_auc_sessions\", session_name, session_start, session_end, impsalhces_asigl0,retirado_asigl0, cerrado_asigl0, ref_asigl0,tipo_sub,cod_sub,num_hces1,lin_hces1,  imp,tipop_orlic, fec, hora ,
                              NVL(lotes_lang.titulo_hces1_lang, lotes.titulo_hces1) titulo_hces1,
                NVL(lotes_lang.desc_hces1_lang, lotes.desc_hces1) desc_hces1,  NVL(lotes_lang.descweb_hces1_lang, lotes.descweb_hces1) descweb_hces1
                              FROM (
                         SELECT rownum rn,T3.* FROM (
                          SELECT COD_LICIT, \"id_auc_sessions\",session_name, session_start, session_end, impsalhces_asigl0,retirado_asigl0, cerrado_asigl0, ref_asigl0,tipo_sub,cod_sub,NUMHCES_ASIGL0, LINHCES_ASIGL0, max(imp) imp, max(tipop_orlic)  tipop_orlic , max(fec) as fec, max(hora) as hora FROM (
                 SELECT  T.*  from
                 (
                        $orders_sql
                    UNION
                        $bids_sql
                    $sql_favorites


                  )T       )T2  group by COD_LICIT, \"id_auc_sessions\",session_name, session_start, session_end, impsalhces_asigl0,retirado_asigl0, cerrado_asigl0, tipo_sub,cod_sub,ref_asigl0,NUMHCES_ASIGL0, LINHCES_ASIGL0 order by    tipo_sub, cod_sub,     ref_asigl0)T3 )T4
                  JOIN FGHCES1 lotes ON   (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = T4.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = T4.LINHCES_ASIGL0)
                LEFT JOIN FGHCES1_LANG lotes_lang
                ON (lotes_lang.EMP_HCES1_LANG = :emp AND lotes_lang.NUM_HCES1_LANG = lotes.num_hces1  AND lotes_lang.LIN_HCES1_LANG = lotes.lin_hces1 AND lotes_lang.LANG_HCES1_LANG = :lang)
                ".ToolsServiceProvider::getOffset($this->page, $this->itemsPerPage)." order by $orderby";

           $ordenes = DB::select($sql, $params);

        foreach ($ordenes as $key => $value) {
            $ordenes[$key]->formatted_imp = ToolsServiceProvider::moneyFormat($value->imp);
            $ordenes[$key]->formatted_impsalhces_asigl0 = ToolsServiceProvider::moneyFormat($value->impsalhces_asigl0);

            $ordenes[$key]->imagen = $this->getLoteImg($value);
            $date = explode(' ', $value->fec);
            //al agrupar por pujas no es posible coger la hora real por que estamos cogiendo la maxima, por lo que voy a usar la fecha completa de todas las pujas que contengan la hora dentro de la fecha.
            if(!empty($date[1]) &&  $date[1] != "00:00:00"){
                $ordenes[$key]->date = ToolsServiceProvider::formatDate($value->fec, null);
            }else{
                $ordenes[$key]->date = ToolsServiceProvider::formatDate($value->fec, $value->hora);
            }

        }

        return $ordenes;
    }

    public function getAllSubastaLicitOrdenes($cod_sub = false, $ref = false)
    {
        $tail = '';

        $params = array(
            'emp'       => Config::get('app.emp'),
            'cli_licit'     => $this->licit
            //'cod_sub'   => $this->cod,
        );

        if(!empty($cod_sub)) {
            $params['cod_sub'] = $cod_sub;
            $tail .= ' AND a.SUB_ORLIC = :cod_sub';
        }

        if(!empty($ref)) {
            $params['ref'] = $ref;
            $tail .= ' AND a.REF_ORLIC = :ref';
        }




        $sql = "
        SELECT * FROM (
            SELECT rownum rn, pu.* FROM (
                select a.*, b.*, lotes.titulo_hces1, lotes.num_hces1, lotes.ref_hces1, lotes.lin_hces1 , lotes.desc_hces1, asigl0.ref_asigl0, asigl0.impsalhces_asigl0,SUB.*,
                asigl0.numhces_asigl0,asigl0.linhces_asigl0
                from FGORLIC a
                JOIN fgasigl0 asigl0 ON (asigl0.emp_asigl0 = a.emp_orlic AND asigl0.SUB_ASIGL0 = a.SUB_ORLIC AND asigl0.REF_ASIGL0 = a.REF_ORLIC)
                JOIN fglicit b ON (b.EMP_LICIT = :emp AND b.SUB_LICIT = a.SUB_ORLIC AND b.COD_LICIT = a.LICIT_ORLIC)
                JOIN FGSUB SUB ON  SUB.EMP_SUB = a.emp_orlic AND SUB.COD_SUB = a.SUB_ORLIC
                JOIN FGHCES1 lotes ON   (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = asigl0.LINHCES_ASIGL0)

                    where a.EMP_ORLIC = :emp $tail
                    AND SUB.SUBC_SUB in ( 'S','A' )
                    and b.CLI_LICIT = :cli_licit
                    order by a.SUB_ORLIC, a.REF_ORLIC
            )
        pu ) ". ToolsServiceProvider::getOffset($this->page, $this->itemsPerPage)."";

        $ordenes = DB::select($sql, $params);

        $strLib = new StrLib();
        foreach ($ordenes as $key => $value) {
            $ordenes[$key]->formatted_himp_orlic = ToolsServiceProvider::moneyFormat($value->himp_orlic);
            $ordenes[$key]->imagen = $this->getLoteImg($value);
            $ordenes[$key]->date = ToolsServiceProvider::formatDate($value->fec_orlic, $value->hora_orlic);
            /*$subasta = new Subasta($ordenes[$key]->SUB_LICIT);
            */
            $ref_prueba = isset($value->ref_hces1)?$value->ref_hces1:-1;
            //$ordenes[$key]->desc = $this->getDescLote();
            $ordenes[$key]->desc_hces1 = $strLib->CleanStr($value->desc_hces1);
            $sub_licit = isset($ordenes[$key]->sub_orlic)?$ordenes[$key]->sub_orlic:-1;
            $ordenes[$key]->id_auc_session = $this->getIdAucSessionslote($sub_licit,$ref_prueba );
            $ordenes[$key]->session_name = $this->getNameSessionslote($sub_licit,$ref_prueba );
            $ordenes[$key]->formatted_impsalhces_asigl0 = ToolsServiceProvider::moneyFormat($value->impsalhces_asigl0);


        }

        return $ordenes;
    }


    # Ordenes de Licitación de un Lote en concreto
    public function getOrdenesInversa()
    {

        $cod_sub = $this->cod;


        //No se puede modificar el ORDER BY
        $sql= "SELECT licitadores.cod_licit,ordenesLicitacion.tipop_orlic,ordenesLicitacion.himp_orlic,ordenesLicitacion.fec_orlic, licit_orlic, lots_conditional_orlic, num_conditional_orlic FROM FGORLIC ordenesLicitacion
                                JOIN FGLICIT licitadores
								JOIN FXCLI ON FXCLI.GEMP_CLI = :gemp AND  FXCLI.COD_CLI = licitadores.CLI_LICIT
                                ON (licitadores.COD_LICIT = ordenesLicitacion.LICIT_ORLIC AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod)
                            WHERE
							(FXCLI.BAJA_TMP_CLI ='N' OR FXCLI.BAJA_TMP_CLI ='W')  AND
                                ordenesLicitacion.EMP_ORLIC = :emp

                                AND ordenesLicitacion.SUB_ORLIC = :cod
                                AND ordenesLicitacion.REF_ORLIC = :lote
                                AND ordenesLicitacion.FEC_ORLIC is not null
                                AND ordenesLicitacion.HORA_ORLIC is not null
                                ORDER BY ordenesLicitacion.HIMP_ORLIC ASC,
                                TO_DATE(TO_CHAR(ordenesLicitacion.FEC_ORLIC, 'DD/MM/YY') || ' ' || ordenesLicitacion.HORA_ORLIC, 'DD/MM/YY HH24:MI:SS') ASC, LIN_ORLIC ASC";
        $params = array('cod' => $cod_sub, 'lote' => $this->ref, 'emp' => App('config')['app']['emp'], 'gemp' => \Config::get("app.gemp"));


        $ordenesTmp = DB::select($sql,$params);
		$ordenes = array();
        foreach ($ordenesTmp as $key => $value) {

			$ordenesTmp[$key]->himp_orlic_formatted = ToolsServiceProvider::moneyFormat($value->himp_orlic);
			$ordenes[]=$ordenesTmp[$key];
        }

        return $ordenes;
	}

	# Ordenes de Licitación de un Lote en concreto
    public function getOrdenes($where = '', $cod_sub=false)
    {
      if(!$cod_sub)
      {
        $cod_sub = $this->cod;
      }
        // 2017_11_13   cambio el LEFT JOIN por un join en LEFT JOIN FGLICIT licitadores, ya que es necesario que exista el licitador
        //No se puede modificar el ORDER BY
        $sql= "SELECT licitadores.cod_licit,ordenesLicitacion.tipop_orlic,ordenesLicitacion.himp_orlic,ordenesLicitacion.fec_orlic, licit_orlic, lots_conditional_orlic, num_conditional_orlic FROM FGORLIC ordenesLicitacion
                                JOIN FGLICIT licitadores
								JOIN FXCLI ON FXCLI.GEMP_CLI = :gemp AND  FXCLI.COD_CLI = licitadores.CLI_LICIT
                                ON (licitadores.COD_LICIT = ordenesLicitacion.LICIT_ORLIC AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod)
                            WHERE
							(FXCLI.BAJA_TMP_CLI ='N' OR FXCLI.BAJA_TMP_CLI ='W')  AND
                                ordenesLicitacion.EMP_ORLIC = :emp
                                ".$where."
                                AND ordenesLicitacion.SUB_ORLIC = :cod
                                AND ordenesLicitacion.REF_ORLIC = :lote
                                AND ordenesLicitacion.FEC_ORLIC is not null
                                AND ordenesLicitacion.HORA_ORLIC is not null
                                ORDER BY ordenesLicitacion.HIMP_ORLIC DESC,
                                TO_DATE(TO_CHAR(ordenesLicitacion.FEC_ORLIC, 'DD/MM/YY') || ' ' || ordenesLicitacion.HORA_ORLIC, 'DD/MM/YY HH24:MI:SS') ASC, LIN_ORLIC ASC";
        $params = array('cod' => $cod_sub, 'lote' => $this->ref, 'emp' => App('config')['app']['emp'], 'gemp' => \Config::get("app.gemp"));

        /*\Log::error("CONSULTA ORDENES-->".$sql.' :: cod'. strtoupper($cod_sub). ' lote' .intval($this->ref) );*/
        $ordenesTmp = DB::select($sql,$params);
	  	#debemos crear un array nuevo para que no haya huecos en el array, antes faltaba el elemento 0 y daba fallos
		$ordenes = array();
        foreach ($ordenesTmp as $key => $value) {

			$ordenesTmp[$key]->himp_orlic_formatted = ToolsServiceProvider::moneyFormat($value->himp_orlic);

			#si es una orden condicionada (numero lotes y referencia lotes)
			if(!empty($value->num_conditional_orlic) && !empty($value->lots_conditional_orlic)){

				#Calculamos cuantos de esos lotes se ha adjudicado
				$adjudicaciones = FgCsub::select("count(*) cuantos")
				->where("SUB_CSUB", $this->cod)->where("LICIT_CSUB", $value->licit_orlic)
				->wherein("REF_CSUB", explode("|",$value->lots_conditional_orlic) )->first()->cuantos;

				#comparamos con el numero máximo y si ya ha llegado a ese número, no tenemos la orden en cuenta
				if($value->num_conditional_orlic <= $adjudicaciones ){
					#guardamos log por si hay quejas
					\Log::info("Orden condicionada no ejecutada subasta: $cod_sub ref: ". $this->ref."
					  numCondicional: ".$value->num_conditional_orlic." numadjudicaicones: " .$adjudicaciones. " lotes condicionados: ".$value->lots_conditional_orlic );
				}else{
					#si cargamos la orden
					$ordenes[]=$ordenesTmp[$key];
				}
			}else{
				$ordenes[]=$ordenesTmp[$key];
			}

        }


        return $ordenes;
	}
	# DEVOLVEMOS UNA ORDEN SI HAY CREDITO SUFICIENTE, SI NO BUSCAMOS OTRA
    public function getOrdenCredit($sessionReference, $actualBid, $userBid, $licit) #actualBid, es el importe de salida o a puja actual del lote
    {
		//echo "Puja actual: ".$actualBid." <br> userbid:". $userBid ." <br>";

		$ordenes = FgOrlic::select("LICIT_ORLIC, HIMP_ORLIC, TIPOP_ORLIC, FEC_ORLIC, HORA_ORLIC")->
							where("EMP_ORLIC", Config::get('app.emp'))->
							where("SUB_ORLIC", $this->cod)->
							where("REF_ORLIC", $this->ref)->
							where("LICIT_ORLIC","!=", $licit)-> #no cogemos las ordenes propias para evitar que uan orden no que n ose hubiera ejecutado por falta de credito ahora se ejecute haciendo que se autopuje el usuario
							where("HIMP_ORLIC", ">=", $actualBid)-> #la orden debe superar o igualar la puja actual, ya que si no no tiene sentido que se use, asi libramos de hacercalculos innecesarios
							orderby("HIMP_ORLIC", "DESC")->
							orderby("FEC_ORLIC")->
							orderby("HORA_ORLIC")->
							orderby("LIN_ORLIC")->get();

		#aqui guardaremos la orden a devolver,
		$returnOrder = null;

		foreach ($ordenes as $key => $orden){

			$credit = FxCli::getCurrentCredit($this->cod, $orden->licit_orlic);

			$user = new User();
			$totalAdjudicado = $user->getSumAdjudicacionesSubasta($this->cod, $orden->licit_orlic);
			$disponible = $credit - $totalAdjudicado ;
			//echo $orden->licit_orlic."<br> Credito disponible: ".$disponible."<br> Orden: ".$orden->himp_orlic."<br><br><br>";

			#Caso 1 el credito disponible es mayor que la orden, se guarda la orden para devolverla
			#en caso de que ya exista una orden guardada en returnOrder si la nueva es más grande que la anterior se sustituye, si son iguales, se mira si la que estamos observando se hizo antes en el tiempo
			#si tenemos suficiente credito como para hacer la orden y
			if($disponible >=  $orden->himp_orlic && (
			#la orden a devolver está vacia,o si la orden que consultamos es mayor que la que queriamos devolver, o son iguales pero la que consultamos se hizo antes
				empty($returnOrder) || $orden->himp_orlic > $returnOrder->himp_orlic ||  ($orden->himp_orlic == $returnOrder->himp_orlic && strtotime($orden->fec_orlic) < strtotime($returnOrder->fec_orlic) )  ) ){

				$returnOrder = $orden;

			}elseif($disponible <  $orden->himp_orlic){
				#si lo que dispone el usuario es mayor que el precio actual del lote y si no existe orden en returnOrden, o si existe se comprueba si esta es mayor, si son iguales, se mira si la que estamso observando se hizo antes en el tiempo
				if($disponible >= $actualBid && ( empty($returnOrder) || $disponible > $returnOrder->himp_orlic || ($disponible == $returnOrder->himp_orlic && strtotime($orden->fec_orlic) < strtotime($returnOrder->fec_orlic) ) )){

					$orden->himp_orlic =$disponible;
					$returnOrder = $orden;

				}
			}


			#si la orden que vamos a devolver supera a la puja del usuario ya no hace falta buscar más, (si es igual si que hay que buscar más), y hacemos return, esto se hace para cubrir un caso raro, de que una orden con más credito pero por debajo de la actual se cuele.
			if(!empty($returnOrder) && $returnOrder->himp_orlic > $userBid  ){
				return $returnOrder;
			}

		}

		//echo "importe orden a devolver: ".$returnOrder->himp_orlic;
		return $returnOrder;


	}

    # Cogemos una orden de licitacion sin información adicional, únicamente para tiempo real.
    public function getOrden($licit = false)
    {
        $params =  array(
                        'emp'       => Config::get('app.emp'),
                        'cod_sub'   => $this->cod,
                        'ref'       => $this->ref
                    );

        if($licit) {
            $params['licit']    = $licit;
            $where_licit        = " AND LICIT_ORLIC = :licit";
        } else {
            $where_licit        = false;
        }

        $ordenesTmp = DB::select("SELECT licit_orlic, himp_orlic, tipop_orlic, fec_orlic, hora_orlic, lots_conditional_orlic, num_conditional_orlic  FROM FGORLIC ordenesLicitacion  WHERE
                                ordenesLicitacion.EMP_ORLIC     = :emp
                                AND ordenesLicitacion.SUB_ORLIC = :cod_sub
                                AND ordenesLicitacion.REF_ORLIC = :ref
                                $where_licit
                                ORDER BY himp_orlic desc, fec_orlic, hora_orlic, LIN_ORLIC ASC",
                                $params
                        );
			$ordenes=[];
		foreach ($ordenesTmp as $key => $value) {

			#si es una orden condicionada (numero lotes y referencia lotes)
			if(!empty($value->num_conditional_orlic) && !empty($value->lots_conditional_orlic)){

				#Calculamos cuantos de esos lotes se ha adjudicado
				$adjudicaciones = FgCsub::select("count(*) cuantos")
				->where("SUB_CSUB", $this->cod)->where("LICIT_CSUB", $value->licit_orlic)
				->wherein("REF_CSUB", explode("|",$value->lots_conditional_orlic) )->first()->cuantos;

				#comparamos con el numero máximo y si ya ha llegado a ese número, no tenemos la orden en cuenta
				if($value->num_conditional_orlic <= $adjudicaciones ){
					#no cargamos la orden
					#guardamos log por si hay quejas
					\Log::info("Orden condicionada no ejecutada subasta: $this->cod ref: ". $this->ref."
						numCondicional: ".$value->num_conditional_orlic." numadjudicaicones: " .$adjudicaciones. " lotes condicionados: ".$value->lots_conditional_orlic );
				}else{
					#si cargamos la orden
					$ordenes[]=$ordenesTmp[$key];
				}
			}else{
				$ordenes[]=$ordenesTmp[$key];
			}

		}


		return $ordenes;
    }
    //funcion que devuelve el lote anterior o el siguiente, teniendo en cuenta si se hace por orden o por referencia y si el lote esta activo o da igual
    public function getNextPreviousLot($pos = "NEXT", $val, $field, $cerrado = NULL, $retirado = 'N')
    {
        if(empty($val)){
            $val = 0;
        }
        $sql2="";
        //si lo miramos por orden
        if($field == 'order'){
            if ($pos == "NEXT"){
                $sql2 = " AND hces1.ORDEN_HCES1 > :val ";
                $order = " ORDER BY HCES1.ORDEN_HCES1 ASC ";
            }elseif ($pos == "PREVIOUS"){
                $sql2 = " AND hces1.ORDEN_HCES1 < :val ";
                $order = " ORDER BY hces1.ORDEN_HCES1 DESC ";
            }
        }

        if(empty($sql2)){
            \Log::error("Error en parametros getNextAfterActualLot: field = $field, pos = $pos, val = $val ");
            return NULL;
        }

        // si queremos que no esté cerrado
        if(!empty($cerrado) && $cerrado == 'N'){

            $sql2 .= " AND asigl0.CERRADO_ASIGL0 = 'N'";

        }
         // si queremos que no esté retirado
        if(!empty($retirado) && $retirado == 'N'){

            $sql2 .= " AND asigl0.RETIRADO_ASIGL0 = 'N'";

        }

        $params = array(
            'emp'       => Config::get('app.emp'),
            'cod_sub'   => $this->cod,
			'session_reference' => $this->session_reference


		);
		if($field == 'order'){
			$params['val'] =$val;
		}

        $sql="SELECT T.* FROM (
                select asigl0.REF_ASIGL0 from  FGASIGL0 asigl0
                join \"auc_sessions\" auc on ( auc.\"company\" =  asigl0.EMP_ASIGL0 and auc.\"auction\" = asigl0.SUB_ASIGL0 )
                join FGHCES1 hces1 on ( asigl0.EMP_ASIGL0 = hces1.EMP_HCES1 and asigl0.NUMHCES_ASIGL0 = hces1.NUM_HCES1  and asigl0.LINHCES_ASIGL0 = hces1.LIN_HCES1 AND ASIGL0.SUB_ASIGL0 = HCES1.SUB_HCES1 )

                where
                asigl0.EMP_ASIGL0 = :emp AND
                asigl0.SUB_ASIGL0 = :cod_sub AND

                auc.\"reference\" = :session_reference AND
                asigl0.REF_ASIGL0 >= auc.\"init_lot\" AND
                asigl0.REF_ASIGL0 <= auc.\"end_lot\"  AND
                asigl0.oculto_asigl0 = 'N'
                $sql2
                $order
                 )t
                WHERE ROWNUM = 1";


        $res = DB::select($sql, $params);

        if(count($res) > 0){
            return head($res)->ref_asigl0;
        }else{
            return NULL;
        }
    }


    //funcion que devuelve los datos_minimos de un lote, puede devolver los datos del lote que pasan, del anterior o del siguiente
    public function getLoteLight()
    {
         $params = array(
            'emp'       => Config::get('app.emp'),
            'cod_sub'   => $this->cod,
            'lote'      => $this->lote,
        );

        $sql="SELECT hces1.orden_hces1,asigl0.sub_asigl0, asigl0.ref_asigl0,hces1.id_hces1, hces1.titulo_hces1, hces1.desc_hces1,  hces1.num_hces1, hces1.lin_hces1,hces1.sec_hces1, asigl0.cerrado_asigl0, asigl0.compra_asigl0,asigl0.impres_asigl0, asigl0.impsalhces_asigl0, asigl0.numhces_asigl0,asigl0.linhces_asigl0,hces1.prop_hces1,hces1.impres_hces1,  hces1.implic_hces1    as max_puja,hces1.COML_HCES1,
                asigl0.fini_asigl0, asigl0.hini_asigl0
                FROM FGASIGL0  asigl0
                INNER JOIN FGHCES1  hces1 ON (hces1.EMP_HCES1 = :emp AND hces1.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND hces1.LIN_HCES1 = asigl0.LINHCES_ASIGL0)


                WHERE asigl0.EMP_ASIGL0      = :emp

                AND asigl0.SUB_ASIGL0 = :cod_sub
                AND  asigl0.REF_ASIGL0 = :lote";

        $res = DB::select($sql, $params);

        if(count($res) > 0){
            return head($res);
        }else{
            return NULL;
        }

    }

    # Información de un lote en concreto (Añadir la información de si está facturado, mirar el comment del not exists para hacer el join)
    public function getLote($where_field = FALSE, $oculto = true, $checkVisibility = false)
    {
		$params = array();

        //Lote no es numerico 404
        if(!is_numeric($this->lote)){
			return abort(404);
        }

        //Si no llega id_auc_sessions lo vamos a buscar
        if(empty($this->id_auc_sessions))
        {
            $auction = $this->getIdAucSessionslote($this->cod, $this->lote);
            //si está vacio es que el lote no debería verse o no existe
            if(empty($auction)){
              return array();
            }

        }  else{
            $auction = $this->id_auc_sessions;
        }

        $where_order = "AND p.REF_ASIGL0 = :lote";

        //Si hay seteado el atributo orden
        if (!empty($where_field) && !empty($this->orden))
        {
            $where_order = "AND lotes.".$where_field." = :lote";
        }

        if(!empty(Config::get('app.hide_return_lot'))){
            $where_order .= " AND lotes.FAC_HCES1 != 'D' AND lotes.FAC_HCES1 != 'R' ";
        }

        if($oculto){
            $where_order .= " AND p.OCULTO_ASIGL0  = 'N'";
        }

		if(Config::get('app.agrsub')) {
			$where_order .= " AND subastas.AGRSUB_SUB = :agrsub";
			$params['agrsub'] = Config::get('app.agrsub');
		}

		$join ="";

		/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */

		if(Config::get("app.restrictVisibility") && $checkVisibility){
			//si no hay usuario logeado devolvemos vacio
			if(empty(Session::get('user.cod'))){
				return null;
			}

			$join = $this->restrictVisibilityLot("join");
			$where_order =  $where_order ."  ". $this->restrictVisibilityLot("where");
			$params['codCli'] = Session::get('user.cod');
		}

        $sql = "SELECT rownum rn, p.*, subastas.*, lotes.implic_hces1,lotes.id_hces1, CSUB.himp_csub,CSUB.base_csub,subastas.opcioncar_sub,lotes.nobj_hces1,
            lotes.lin_hces1, lotes.num_hces1, lotes.ref_hces1,  lotes.orden_hces1, lotes.fac_hces1, lotes.lic_hces1, lotes.sec_hces1, NVL(lotes_lang.descdet_hces1_lang, lotes.descdet_hces1) descdet_hces1 ,  NVL(lotes_lang.descweb_hces1_lang, lotes.descweb_hces1) descweb_hces1, lotes.sub_hces1,
            lotes.loteaparte_hces1 ,lotes.embalaje_hces1, lotes.transport_hces1, lotes.alto_hces1, ancho_hces1, grueso_hces1,peso_hces1,lotes.altoumed_hces1, anchoumed_hces1, gruesoumed_hces1,pesoumed_hces1,lotes.alm_hces1,lotes.contextra_hces1, ministerio_hces1, permisoexp_hces1, imgfriendly_hces1, lotes.pc_hces1,
			controlstock_hces1, stock_hces1,
            NVL(lotes_lang.titulo_hces1_lang, lotes.titulo_hces1) titulo_hces1,  NVL(lotes_lang.desc_hces1_lang, lotes.desc_hces1) desc_hces1,
            NVL(lotes_lang.webmetat_hces1_lang, lotes.webmetat_hces1) webmetat_hces1,  NVL(lotes_lang.webmetad_hces1_lang, lotes.webmetad_hces1) webmetad_hces1,  NVL(lotes_lang.webfriend_hces1_lang, lotes.webfriend_hces1) webfriend_hces1,lotes.contextra_hces1,
            auc.*,NVL(auc_lang.\"name_lang\", auc.\"name\") auc_name, auc.\"end\" end_session, auc.\"start\" start_session,prop_hces1,implic_hces1    as max_puja, p.IMPADJ_ASIGL0,
             (CASE WHEN p.ffin_asigl0 IS NOT NULL AND p.hfin_asigl0 IS NOT NULL
                        THEN REPLACE(TO_DATE(TO_CHAR(p.ffin_asigl0, 'DD/MM/YY') || ' ' || p.hfin_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                        ELSE null END) close_at,
			(CASE WHEN p.fini_asigl0 IS NOT NULL AND p.hini_asigl0 IS NOT NULL
                THEN REPLACE(TO_DATE(TO_CHAR(p.fini_asigl0, 'DD/MM/YY') || ' ' || p.hini_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                ELSE null END) open_at,

			alm.obs_alm,lotes.cosembcarg_hces1
         FROM FGASIGL0 p
            INNER JOIN FGHCES1 lotes
              ON (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)
            LEFT JOIN FGHCES1_LANG lotes_lang
              ON (lotes_lang.EMP_HCES1_LANG = :emp AND lotes_lang.NUM_HCES1_LANG = p.NUMHCES_ASIGL0  AND lotes_lang.LIN_HCES1_LANG = p.LINHCES_ASIGL0 AND lotes_lang.LANG_HCES1_LANG = :lang)

            INNER JOIN FGSUB subastas
                ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
            JOIN \"auc_sessions\" auc ON (auc.\"auction\" = :cod_sub AND auc.\"company\" = :emp)
            LEFT JOIN \"auc_sessions_lang\" auc_lang
                ON (auc_lang.\"company_lang\" =:emp AND auc_lang.\"reference_lang\" = auc.\"reference\" AND auc_lang.\"auction_lang\" = auc.\"auction\" AND auc_lang.\"lang_auc_sessions_lang\" = :lang)
            LEFT JOIN FGCSUB CSUB ON (CSUB.EMP_CSUB = p.EMP_ASIGL0 AND CSUB.sub_CSUB = p.SUB_ASIGL0 AND REF_CSUB = p.REF_ASIGL0)
            LEFT JOIN FXALM ALM ON (ALM.COD_ALM = lotes.ALM_HCES1 AND p.EMP_ASIGL0=ALM.EMP_ALM)
			$join
              WHERE p.EMP_ASIGL0      = :emp
              AND auc.\"id_auc_sessions\" = :auction
              AND subastas.COD_SUB    = :cod_sub
              ".$where_order;


		$params['emp'] = Config::get('app.emp');
		$params['cod_sub'] = $this->cod;
		$params['lote'] = $this->lote;
		$params['lang'] = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		$params['auction'] = $auction;

        return DB::select($sql,	$params );
    }


    public function getIdAucSessionslote($cod_subasta, $lote)
    {

        $sql = 'SELECT "id_auc_sessions" '
              . 'FROM "auc_sessions" '
              . 'WHERE "auction" = :cod_sub '
              . 'AND "company" = :emp '
              . 'AND "init_lot" <= :lote '
              . 'AND "end_lot" >= :lote ';
       $id_auc_sessions = DB::select($sql,
                                        array(
                                            'emp'       => Config::get('app.emp'),
                                            'cod_sub'   => $cod_subasta,
                                            'lote'      => $lote,
                                            )
                                        );
       if (count($id_auc_sessions) > 0){
        return $id_auc_sessions[0]->id_auc_sessions;
       }
       else{
           return NULL;
       }

    }
    public function get_reference_auc_session($id_auc_sessions)
    {
        $sql = " Select \"reference\" from \"auc_sessions\" where \"id_auc_sessions\" = :id_auc_session and \"auction\" = :cod_sub and \"company\" = :emp ";
         $bindings = array(
                    'emp'            => Config::get('app.emp'),
                    'cod_sub'        => $this->cod,
                    'id_auc_session' => $id_auc_sessions

                    );
        $res =  DB::select($sql, $bindings);
        if (count($res) > 0 )
        {
            return head($res)->reference;
        }
        else
        {
            return NULL;
        }

    }

     public function get_session($id_auc_sessions)
    {
        $sql = " Select * from \"auc_sessions\" where \"id_auc_sessions\" = :id_auc_session and \"auction\" = :cod_sub and \"company\" = :emp ";
         $bindings = array(
                    'emp'            => Config::get('app.emp'),
                    'cod_sub'        => $this->cod,
                    'id_auc_session' => $id_auc_sessions

                    );
        $res =  DB::select($sql, $bindings);
        if (count($res) > 0 )
        {
            return head($res);
        }
        else
        {
            return NULL;
        }

    }

    public function getReferenceAucSessionslote()
    {

        $sql = 'SELECT "reference" '
              . 'FROM "auc_sessions" '
              . 'WHERE "auction" = :cod_sub '
              . 'AND "company" = :emp '
              . 'AND "init_lot" <= :lote '
              . 'AND "end_lot" >= :lote ';
       $auc_sessions = DB::select($sql,
                                        array(
                                            'emp'       => Config::get('app.emp'),
                                            'cod_sub'   => $this->cod,
                                            'lote'      => $this->lote,
                                            )
                                        );
        if (count($auc_sessions) > 0 )
        {
            return head($auc_sessions)->reference;
        }
        else
        {
            return NULL;
        }

    }

    public function getNameSessionslote($cod_subasta, $lote)
    {

        $sql = 'SELECT "name" '
              . 'FROM "auc_sessions" '
              . 'WHERE "auction" = :cod_sub '
              . 'AND "company" = :emp '
              . 'AND "init_lot" <= :lote '
              . 'AND "end_lot" >= :lote ';
       $id_auc_sessions = DB::select($sql,
                                        array(
                                            'emp'       => Config::get('app.emp'),
                                            'cod_sub'   => $cod_subasta,
                                            'lote'      => $lote,
                                            )
                                        );
       if(isset($id_auc_sessions[0])){
           return $id_auc_sessions[0]->name;
       }
       else{
           return "";
       }


    }



    # Información del lote siguinte a un lote en concreto.
    /*  hemos quitado esta linea AND lotes.ORDEN_HCES1 > :orden */
    public function getNextAvailableLote()
    {
        $sql = "SELECT * FROM (
                                SELECT rownum rn, pu.* FROM (
                                        SELECT p.*, subastas.*, lotes.*, auc.*, auc.\"end\" end_session, auc.\"start\" start_session FROM FGASIGL0 p
                                              INNER JOIN FGHCES1 lotes
                                                ON (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0 AND p.SUB_ASIGL0 = lotes.SUB_HCES1)
                                              INNER JOIN FGSUB subastas ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
                                              JOIN \"auc_sessions\" auc ON (auc.\"auction\" = :cod_sub AND auc.\"company\" = :emp AND auc.\"reference\" = :session_reference)
                                                WHERE p.EMP_ASIGL0 = :emp
                                                AND p.OCULTO_ASIGL0 = 'N'
                                                AND subastas.COD_SUB = :cod_sub
                                                AND p.retirado_asigl0 = 'N'
                                                AND p.CERRADO_ASIGL0 = 'N'
                                                AND auc.\"end_lot\" >=  p.REF_ASIGL0
                                                AND auc.\"init_lot\" <=  p.REF_ASIGL0
                                                AND lotes.FAC_HCES1 != 'D' AND lotes.FAC_HCES1 != 'R'
                                                ORDER BY lotes.ORDEN_HCES1 ASC ) pu
                                        )".self::getOffset($this->page, $this->itemsPerPage);

        $bindings = array(
            'emp'       => Config::get('app.emp'),
            'cod_sub'   => $this->cod,

            'session_reference'      => $this->session_reference
        );

        /*'orden'      => $this->orden,*/
        return DB::select($sql, $bindings);
    }


    public function getCurrency(){
        $curr = DB::select("SELECT DIV_PARAMS FROM FSPARAMS
                    WHERE EMP_PARAMS = :emp AND
                    CLA_PARAMS = :cla
                    ",
                    array(
                        'emp'   => Config::get('app.emp'),
                        'cla'   => 1
                        )
                );

        $currency = new \stdClass();
        $currency->name = head($curr)->div_params;
        $currency->symbol = ToolsServiceProvider::getCurrency($currency->name);

        return $currency;
    }

	# Auto increment de los códigos de licitador en la tabla FGLICIT
    public function licitIncrement()
    {
        $num = FgLicit::newCodLicit($this->cod); //número entre 0 y 99999 quitando el 9999
		$dummyBidder = Config::get('app.dummy_bidder');

		// Si el número de licitador es mayor o igual que el bidder, se asigna un número de licitador no utilizado
		if($num >= $dummyBidder){

			$start_bidders = DB::table('fgprmsub')
				->select('numlicweb_prmsub')
				->where('EMP_PRMSUB', Config::get('app.emp'))
				->value('numlicweb_prmsub') ?? 1000;

			//if start_bidders is bigger to dummy_bidder, we will retun num
			if($start_bidders > $dummyBidder){
				return $num;
			}


			$num = FgLicit::unusedCodLicit($this->cod);
        }

        return $num;
    }

    /*
    |--------------------------------------------------------------------------
    | LICITADORES - código de licitador
    |--------------------------------------------------------------------------
    |
    | Al entrar con un usuario registrado en una subasta, comprobamos si existe como licitador,
    | de lo contrario lo daremos de alta en la tabla FGLICIT
    | para asignarle un código de licitador por cada subasta
    |
    */
    #
    public function checkLicitador($only_check = false)
    {

        if(!$only_check) {
            #no se deben tener en cuenta los licitadores de subalia
            $licitSubalia   = !empty(Config::get('app.subalia_min_licit'))? Config::get('app.subalia_min_licit') : 100000;
            $licit = DB::select("SELECT COD_LICIT FROM FGLICIT WHERE  EMP_LICIT = :emp AND SUB_LICIT = :cod_sub  AND CLI_LICIT = :cli_licit AND COD_LICIT < :licitSubalia",
                                array(
                                    'emp'           => Config::get('app.emp'),
                                    'cli_licit'     => $this->cli_licit,
                                    'cod_sub'       => $this->cod,
                                    'licitSubalia'  => $licitSubalia
                                    )
                                );


            if(Config::get('app.auto_licit') == 1) {
                # Si no existe insertaremos
                if(count($licit) == 0) {
                    $cod_licit = DB::select("INSERT INTO FGLICIT (EMP_LICIT, SUB_LICIT, COD_LICIT, CLI_LICIT, RSOC_LICIT) VALUES (:emp, :cod_sub, :cod_licit, :cli_licit, :rsoc)",
                                        array(
                                        'emp'           => Config::get('app.emp'),
                                        'cod_sub'       => $this->cod,
                                        'cod_licit'     => $this->licitIncrement(),
                                        'cli_licit'     => $this->cli_licit,
                                        'rsoc'          => $this->rsoc,

                                        )
                                    );

                    $licit = DB::select("SELECT COD_LICIT FROM FGLICIT WHERE CLI_LICIT = :cli_licit AND SUB_LICIT = :cod_sub AND EMP_LICIT = :emp",
                                array(
                                    'emp'           => Config::get('app.emp'),
                                    'cli_licit'     => $this->cli_licit,
                                    'cod_sub'       => $this->cod,
                                    )
                                );
                }
            }
        } else {
			$licit = $this->validLicit($this->licit, $this->cod);
        }

        return $licit;
    }

	public function validLicit($cod_licit, $cod_sub)
	{
		$licit = $this->getLicit($cod_licit, $cod_sub);

		// Si no se encontró el licitador, buscar en el log
		if (!$licit) {
			$licit = $this->getLicitFromLog($cod_licit, $cod_sub);

			// Si se encontró el licitador en el log, buscar de nuevo si existe en la tabla FGLICIT
			if ($licit) {
				return $this->validLicit($licit[0]->cod_licit, $cod_sub);
			}
		}

		return $licit;
	}

	private function getLicit($cod_licit, $cod_sub)
	{
		return DB::select(
			"SELECT COD_LICIT
				FROM FGLICIT
				WHERE COD_LICIT = :cod_licit AND SUB_LICIT = :cod_sub AND EMP_LICIT = :emp",
			[
				'emp'           => Config::get('app.emp'),
				'cod_licit'     => $cod_licit,
				'cod_sub'       => $cod_sub,
			]
		);
	}

	private function getLicitFromLog($cod_licit, $cod_sub)
	{
		$fgLicitLogQuery = "SELECT COD_LICIT_NEW as COD_LICIT
				FROM FGLICIT_LOG
				WHERE COD_LICIT_OLD = :cod_licit AND SUB_LICIT = :cod_sub AND EMP_LICIT = :emp";

		return DB::select(
			$fgLicitLogQuery,
			[
				'emp'       => Config::get('app.emp'),
				'cod_licit' => $cod_licit,
				'cod_sub'   => $cod_sub,
			]
		);
	}

	public function getLicitLogs($codSub)
	{
		$fgLicitLogQuery = "SELECT * FROM FGLICIT_LOG
			LEFT JOIN FGLICIT ON FGLICIT.COD_LICIT = FGLICIT_LOG.COD_LICIT_NEW AND FGLICIT.EMP_LICIT = FGLICIT_LOG.EMP_LICIT AND FGLICIT.SUB_LICIT = FGLICIT_LOG.SUB_LICIT
			WHERE FGLICIT_LOG.SUB_LICIT = :cod_sub AND FGLICIT_LOG.EMP_LICIT = :emp
			ORDER BY FGLICIT_LOG.DATE_LICIT DESC";

		$licits = DB::select($fgLicitLogQuery, ['emp' => Config::get('app.emp'), 'cod_sub' => $codSub]);
		return $licits;
	}

	public function createLicitadorToAuthUserAndRepresented($codCli, $codSub, $idRepresented)
	{
		$represented = FgRepresentados::find($idRepresented);
		if(!$represented){
			return;
		}

		$nomRepre = mb_substr($represented->nom_representados, 0, 60);

		$this->cod = $codSub;
		$codLicit = $this->licitIncrement();

		FgLicit::create([
			'SUB_LICIT' => $codSub,
			'COD_LICIT' => $codLicit,
			'CLI_LICIT' => $codCli,
			'RSOC_LICIT' => $nomRepre,
		]);

		//create fglicit_representados
		FgLicitRepresentados::create([
			'SUB_LICITREPRESENTADOS' => $codSub,
			'COD_LICITREPRESENTADOS' => $codLicit,
			'REPRE_LICITREPRESENTADOS' => $idRepresented
		]);

		return $codLicit;
	}

    /*
    |--------------------------------------------------------------------------
    | Código licitador dummy user para el gestor únicamente
    |--------------------------------------------------------------------------
    */
    public function checkDummyLicitador()
    {
        if($this->cod == NULL){
            return NULL;
        }else{
            $licit = DB::select("SELECT COD_LICIT FROM FGLICIT WHERE COD_LICIT = :cod_licit AND SUB_LICIT = :cod_sub AND EMP_LICIT = :emp",
                                array(
                                    'emp'           => Config::get('app.emp'),
                                    'cod_licit'     => Config::get('app.dummy_bidder'),
                                    'cod_sub'       => $this->cod,
                                    )
                                );



            # Si no existe insertaremos
            if(count($licit) == 0) {
                $cod_licit = DB::select("INSERT INTO FGLICIT (EMP_LICIT, SUB_LICIT, COD_LICIT, CLI_LICIT, RSOC_LICIT) VALUES (:emp, :cod_sub, :cod_licit, :cli_licit, :rsoc)",
                                    array(
                                    'emp'           => Config::get('app.emp'),
                                    'cod_sub'       => $this->cod,
                                    'cod_licit'     => Config::get('app.dummy_bidder'),
                                    'cli_licit'     => Config::get('app.dummy_bidder'),
                                    'rsoc'          => 'proxy_bidder',
                                    )
                                );
            }

            return $licit;
        }
    }

	public function checkOrInstertMinisteryLicitador($ministeryLicit, $ministeryName)
	{
		if ($this->cod == null) {
			return null;
		}

		$licit = DB::select(
			"SELECT COD_LICIT FROM FGLICIT WHERE COD_LICIT = :cod_licit AND SUB_LICIT = :cod_sub AND EMP_LICIT = :emp",
			array(
				'emp'           => Config::get('app.emp'),
				'cod_licit'     => $ministeryLicit,
				'cod_sub'       => $this->cod,
			)
		);

		# Si no existe insertaremos
		if (count($licit) == 0) {
			$licit = DB::select(
				"INSERT INTO FGLICIT (EMP_LICIT, SUB_LICIT, COD_LICIT, CLI_LICIT, RSOC_LICIT) VALUES (:emp, :cod_sub, :cod_licit, :cli_licit, :rsoc)",
				array(
					'emp'           => Config::get('app.emp'),
					'cod_sub'       => $this->cod,
					'cod_licit'     => $ministeryLicit,
					'cli_licit'     => config('app.ministeryCli', $ministeryLicit),
					'rsoc'          => $ministeryName,
				)
			);
		}
		return $licit;
	}

    /*
    |--------------------------------------------------------------------------
    | Cerrar Lote
    |--------------------------------------------------------------------------
    */
    # Cerramos un lote en concreto
    public function cerrarLote()
    {

        try {
            \Log::info("dentro de cerrar lote");
          $a=DB::select("call CERRARLOTE(:subasta, :ref, :emp, :user_rp, :redondeo)",
                               array(
                                   'subasta'    => $this->cod,
                                   'ref'        => $this->lote,
                                   'emp'        => Config::get('app.emp'),
                                   'user_rp'     => 'admin',
                                   'redondeo'     => 2
                                   )
                               );


            $result = array(
                'status' => 'ok',
                'msg' => ""
                );


        } catch (\Exception $e) {
            \Log::error(__FILE__.' ::'. $e);

            $result = array(
                'status' => 'error',
                'msg' => trans(\Config::get('app.theme').'-app.msg_error.closing_lot')
                );
        }

        $this->add_email_adjudicado($result);

        return $result;
    }

    # Añadimos ordenes de licitción
    public function addOrden($no_check_cerrado = FALSE)
    {
        try {

            if($this->checkLicitador(1)) {

                if(!$no_check_cerrado){
                    # Mira si el lote está cerrado
                    $closed = DB::select("SELECT CERRADO_ASIGL0 FROM FGASIGL0 WHERE EMP_ASIGL0 = :emp AND SUB_ASIGL0 = :cod_sub AND REF_ASIGL0 = :ref",
                        array(
                            'emp'           => Config::get('app.emp'),
                            'cod_sub'       => $this->cod,
                            'ref'           => $this->ref
                            )
                        );

                    if (empty($closed) || empty(head($closed)->cerrado_asigl0) || head($closed)->cerrado_asigl0 == 'S'){
                        $res['status'] = 'error';
                        $res['msg'] = 'lot_closed';
                        return $res;
                    }


                    $expire = DB::select(" SELECT 'EXPIRE' FROM FGASIGL0
                                            JOIN FGSUB ON  FGSUB.EMP_SUB =  FGASIGL0.EMP_ASIGL0   AND   FGSUB.COD_SUB = FGASIGL0.SUB_ASIGL0
                                            WHERE
                                                FGASIGL0.EMP_ASIGL0 = :emp
                                            AND FGASIGL0.SUB_ASIGL0 = :cod_sub
                                            AND FGASIGL0.REF_ASIGL0 = :ref

                                            AND
                                            /* SI LA FECHA ES ANTERIOR A HOY */
                                            (TRUNC(FGASIGL0.FFIN_ASIGL0) < TRUNC(SYSDATE)
                                            OR
                                            /* O LA FECHA ES HOY Y LA HORA ES INFERIOR A LA ACTUAL */
                                             ( TRUNC(FGASIGL0.FFIN_ASIGL0) = TRUNC(SYSDATE)  AND  FGASIGL0.HFIN_ASIGL0 <= to_char(sysdate, 'HH24:MI:SS') )
                                            )
                                            AND FGSUB.TIPO_SUB IN ('O','P')
                                            ",
                        array(
                            'emp'           => Config::get('app.emp'),
                            'cod_sub'       => $this->cod,
                            'ref'           => $this->ref
                            )
                        );

                    if (!empty($expire) ){
                        $res['status'] = 'error';
                        $res['msg'] = 'lot_closed';
                        return $res;
                    }
                }


                # Insert on duplicate key update
                $sql = "MERGE INTO FGORLIC dest
                          USING( SELECT :licit user_id, :cod_sub sub , :emp emp FROM dual) src
                             ON( dest.LICIT_ORLIC = src.user_id and emp = dest.EMP_ORLIC and dest.SUB_ORLIC = src.sub and dest.REF_ORLIC = :ref)
                         WHEN MATCHED THEN
                           UPDATE SET HIMP_ORLIC = :imp, TIPOP_ORLIC = :type_bid, FEC_ORLIC = SYSDATE, HORA_ORLIC = TO_CHAR(SYSDATE, 'HH24:MI:SS'), TEL1_ORLIC = :tel1,  TEL2_ORLIC = :tel2
                         WHEN NOT MATCHED THEN
                           INSERT
                            (EMP_ORLIC, SUB_ORLIC, REF_ORLIC, LIN_ORLIC, LICIT_ORLIC, HIMP_ORLIC, FEC_ORLIC, TIPOP_ORLIC, HORA_ORLIC, TEL1_ORLIC, TEL2_ORLIC)
                                VALUES
                            (:emp, :cod_sub, :ref, (SELECT NVL((MAX(CAST(LIN_ORLIC AS Int)) + 1), 1) AS numero FROM FGORLIC WHERE EMP_ORLIC = :emp AND SUB_ORLIC = :cod_sub AND REF_ORLIC = :ref), :licit, :imp, SYSDATE, :type_bid, TO_CHAR(SYSDATE, 'HH24:MI:SS'), :tel1, :tel2)";

                $res = DB::select($sql,
                    array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        'licit'         => $this->licit,
                        'imp'           => $this->imp,
                        'type_bid'      => $this->type_bid,
                        'tel1'      => empty($this->tel1)? '' : $this->tel1,
                        'tel2'      => empty($this->tel2)? '' : $this->tel2
                        )
                    );

                $result = array(
                    'status'                 => 'success',
                    'msg'                    => 'add_bidding_order',
                    'cod_licit_actual'       => $this->licit,
                    'imp'                    => $this->imp,
                    //'actual_bid' => ToolsServiceProvider::moneyFormat($this->imp),
                    //'ref' => $this->ref
                    );

					SeoLib::saveEvent("ORDER");
            } else {
                $result = array(
                'status' => 'error',
                'msg' => 'no_licit',
                );
            }
			#llamamos al web service de ordenes
			$this->webServiceBid($this->licit, $this->cod, $this->ref, $this->imp, "", "ORDEN");


        } catch (\Exception $e) {
            \Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                        'msg'       => 'inserting_bid_order'
                        );
        }

        return $result;
    }

     public function addPuja($no_check_cerrado = FALSE, $type_asigl1 = 'N')
    {
        try {

            if($this->checkLicitador(1)) {

                $res = array();

                if(!$no_check_cerrado){
                    # Mira si el lote está cerrado
                    $closed = DB::select("SELECT CERRADO_ASIGL0 FROM FGASIGL0 WHERE EMP_ASIGL0 = :emp AND SUB_ASIGL0 = :cod_sub AND REF_ASIGL0 = :ref",
                        array(
                            'emp'           => Config::get('app.emp'),
                            'cod_sub'       => $this->cod,
                            'ref'           => $this->ref
                            )
                        );

                    if (empty($closed) || empty(head($closed)->cerrado_asigl0) || head($closed)->cerrado_asigl0 == 'S'){
                        $res['status'] = 'error';
                        $res['msg'] = 'lot_closed';
                        return $res;
                    }

                    $expire = DB::select("SELECT FGASIGL0.FFIN_ASIGL0, FGASIGL0.HFIN_ASIGL0, FGASIGL0.FFIN_ORIGINAL_ASIGL0, FGASIGL0.HFIN_ORIGINAL_ASIGL0, FGASIGL0.IMPRES_ASIGL0 FROM FGASIGL0
                                            JOIN FGSUB ON  FGSUB.EMP_SUB =  FGASIGL0.EMP_ASIGL0   AND   FGSUB.COD_SUB = FGASIGL0.SUB_ASIGL0
                                            WHERE
                                                FGASIGL0.EMP_ASIGL0 = :emp
                                            AND FGASIGL0.SUB_ASIGL0 = :cod_sub
                                            AND FGASIGL0.REF_ASIGL0 = :ref
                                            AND FGSUB.TIPO_SUB IN ('O','P')",
                        array(
                            'emp'           => Config::get('app.emp'),
                            'cod_sub'       => $this->cod,
                            'ref'           => $this->ref
                            ));
                    // si no esta vacio es que la subasta es de tipo 'O' o tipo 'P'
                        if(!empty($expire)){

                            $fecha = head($expire);
                            $fecha_fin=substr($fecha->ffin_asigl0,0,10)." ".$fecha->hfin_asigl0;
                            $fecha_fin_time=  strtotime($fecha_fin);
                            $diferencia =   $fecha_fin_time - time();

                            //el lote ya ha finalizado, no se debería poder pujar
                            if($diferencia <0){
                                $res['status'] = 'error';
                                $res['msg'] = 'lot_closed';
                                return $res;
                            }
                            //estamos en el último minuto
                            elseif( !empty(Config::get('app.increase_time_add')) && !empty(Config::get('app.increase_time_launch')) ){

								$fecha_fin_original = substr($fecha->ffin_original_asigl0, 0, 10). " " . $fecha->hfin_original_asigl0;

								$fecha_fin_original_time = strtotime($fecha_fin_original);

								$diferencia_original = time() - $fecha_fin_original_time;

								$maximum_time_increment = Config::get('app.maxium_time_increment', 0);

								$esSuperiorQueMaximo = !empty($maximum_time_increment) && $diferencia_original > $maximum_time_increment;

                                if ($diferencia <=Config::get('app.increase_time_launch') && $diferencia >=0 && !$esSuperiorQueMaximo){

                                    $fecha_fin_time =  $fecha_fin_time + Config::get('app.increase_time_add');
                                    $date = date("Y-m-d", $fecha_fin_time);
                                    $hour = date("H:i:s", $fecha_fin_time);

                                     $sql = "update FGASIGL0 set FFIN_ASIGL0 = TO_DATE(:ffin, 'YYYY-MM-DD'),  HFIN_ASIGL0=:hfin where EMP_ASIGL0 = :emp and SUB_ASIGL0 = :cod_sub and REF_ASIGL0 = :ref";

                                  DB::select($sql,
                                     array(
                                         'emp'                => Config::get('app.emp'),
                                         'cod_sub'            => $this->cod,
                                         'ref'                => $this->ref,
                                         'ffin'               => $date,
                                         'hfin'               => $hour
                                         )
                                     );
                                }
                            }
                     }
                }

				DB::beginTransaction();
                $sql_asigl1 = "INSERT INTO FGASIGL1
                            (EMP_ASIGL1, SUB_ASIGL1, REF_ASIGL1, LIN_ASIGL1, LICIT_ASIGL1, IMP_ASIGL1, FEC_ASIGL1, PUJREP_ASIGL1, TYPE_ASIGL1, HORA_ASIGL1)
                        VALUES
                            (:emp, :cod_sub, :ref, (SELECT NVL((MAX(CAST(LIN_ASIGL1 AS Int)) + 1), 1) AS numero FROM FGASIGL1 WHERE EMP_ASIGL1 = :emp AND SUB_ASIGL1 = :cod_sub AND REF_ASIGL1 = :ref), :licit, :imp,
                                SYSDATE, :type_bid, :type_asigl1,
                                TO_CHAR(SYSDATE, 'HH24:MI:SS')
                            )";

                $res = DB::select($sql_asigl1,
                    array(
                        'emp'                => Config::get('app.emp'),
                        'cod_sub'            => $this->cod,
                        'ref'                => $this->ref,
                        'licit'              => $this->licit,
                        'imp'                => $this->imp,
                        'type_bid'           => $this->type_bid,
                        'type_asigl1'        => $type_asigl1
                        )
                    );
					#la puja no debe ser automatica  y debe ser de tipo web
					if($type_asigl1 == 'N' && $this->type_bid =="W" ){
						SeoLib::saveEvent("BID");
					}


                    //la fghces1 y la asigl0 ya no van por subasta y referencia, para nocambiar todas las llamadas a esta funcion hago un join
                 $sql_hces1 = "update fghces1 set implic_hces1 = :imp, lic_hces1='S' where
                          num_hces1 = (select numhces_asigl0 from fgasigl0 where fgasigl0.sub_asigl0 =   :cod_sub and fgasigl0.ref_asigl0 = :ref and emp_asigl0 = :emp)
                          and
                          lin_hces1 = (select linhces_asigl0 from fgasigl0 where fgasigl0.sub_asigl0 =   :cod_sub and fgasigl0.ref_asigl0 = :ref and emp_asigl0 = :emp)
                          and emp_hces1 = :emp";

                 DB::select($sql_hces1,
                    array(
                        'emp'                => Config::get('app.emp'),
                        'cod_sub'            => $this->cod,
                        'ref'                => $this->ref,
                        'imp'                => $this->imp
                        )
                    );
                 //copiar en subalia la puja, DE MOMENTO SOLO HABRÁ PUJAS Y ORDENES EN LA BASE DE DATOS DE LOS CLIENTES, POR LO QUE NO SE COPIA A SUBALIA
              //  $this->add_puja_subalia($type_asigl1, $sql_asigl1, $sql_hces1);


			/**
			 * @todo 19/02/2025 - Introducción de puja auto y puja por distintas paletas
			 * Al realizar la puja auto no tiene sentido que inserte los pujadoreMT de la puja realizada en la ficha.
			 * En caso de añadir la pujaMT con ordenes valorar un segundo circuito para esas pujas defensoras.
			 */
			if(config('app.withMultipleBidders', false)) {

				//obtenemos la puja que se acaba de insertar
				$bid = FgAsigl1::query()->where([
					['sub_asigl1', $this->cod],
					['ref_asigl1', $this->ref],
					['licit_asigl1', $this->licit],
					['imp_asigl1', $this->imp],
				])->first();

				if($type_asigl1 == FgAsigl1::TYPE_NORMAL) {
					$this->addPujaMultiple($bid);
				}
				elseif($type_asigl1 == FgAsigl1::TYPE_AUTO) {
					$this->addAutoPujaMultiple($bid);
				}
			}

			DB::commit();


				#llamar a webservice externo notificando la puja
				$this->webServiceBid($this->licit, $this->cod, $this->ref, $this->imp, $type_asigl1, "PUJA");

				 // si no esta vacio es que la subasta es de tipo 'O' o tipo 'P'
				 if(Config::get("app.adjudicacion_reserva") && !empty($expire) && !empty(head($expire)->impres_asigl0) &&  head($expire)->impres_asigl0 <= $this->imp){

					$this->cerrarLote();

					$result = array(
						'status' => 'close',
						'msg' => 'correct_bid',
						'formatted_actual_bid' => ToolsServiceProvider::moneyFormat($this->imp),
						'actual_bid' => $this->imp,
						'ref' => $this->ref
						);
					return $result;
				 }

                $this->sin_pujas = false;
                $result = array(
                'status' => 'success',
                'msg' => 'correct_bid',
                'formatted_actual_bid' => ToolsServiceProvider::moneyFormat($this->imp),
                'actual_bid' => $this->imp,
                'ref' => $this->ref
                );
            } else {
                $result = array(
                'status' => 'error',
                'msg' => 'no_licit',
                );
            }

        } catch (\Exception $e) {

			DB::rollBack();
            Log::error(__FILE__.' ::'. $e);

            $result = array(
                'status' => 'error',
                'msg' => 'inserting_bid'
                );
        }

        return $result;
	}

	private function addPujaMultiple($bid)
	{
		$hasMultipleBidders = request('params.bidders');
		if(!$hasMultipleBidders){
			return false;
		}

		$sumRatios = 0;
		$biddersRequest = request('params.bidders');
		$bidders = array_map(function($bidderRequest) use ($bid, &$sumRatios){

			$sumRatios += $bidderRequest['ratio'];

			return [
				'emp_asigl1mt' => $bid->emp_asigl1,
				'sub_asigl1mt' => $bid->sub_asigl1,
				'ref_asigl1mt' => $bid->ref_asigl1,
				'lin_asigl1mt' => $bid->lin_asigl1,
				'ratio_asigl1mt' => $bidderRequest['ratio'],
				'nom_asigl1mt' => $bidderRequest['name'],
				'apellido_asigl1mt' => $bidderRequest['surname']
			];
		}, $biddersRequest);

		if($sumRatios != 100) {
			throw new \Exception("La suma de ratios debe ser igual a 100 %");
		}

		FgAsigl1Mt::insert($bidders);

		return true;
	}

	private function addAutoPujaMultiple($bid)
	{
		//obtenemos la línea de la última puja normal
		$lastBidLine = FgAsigl1::query()
		->where([
			['sub_asigl1', $bid->sub_asigl1],
			['ref_asigl1', $bid->ref_asigl1],
			['licit_asigl1', $bid->licit_asigl1],
			['type_asigl1', FgAsigl1::TYPE_NORMAL]
		])
		->orderBy('lin_asigl1', 'desc')
		->value('lin_asigl1');

		//de esa puja extraemos los datos de los licitadores MT
		$lastMultipleBidBidders = FgAsigl1Mt::query()
			->where([
				['sub_asigl1mt', $bid->sub_asigl1],
				['ref_asigl1mt', $bid->ref_asigl1],
				['lin_asigl1mt', $lastBidLine]
			])
			->get();

		//insertamos los licitadores en la puja automática
		$bidders = $lastMultipleBidBidders->map(function ($bidder) use ($bid) {
			return [
				'emp_asigl1mt' => $bid->emp_asigl1,
				'sub_asigl1mt' => $bid->sub_asigl1,
				'ref_asigl1mt' => $bid->ref_asigl1,
				'lin_asigl1mt' => $bid->lin_asigl1,
				'ratio_asigl1mt' => $bidder->ratio_asigl1mt,
				'nom_asigl1mt' => $bidder->nom_asigl1mt,
				'apellido_asigl1mt' => $bidder->apellido_asigl1mt
			];
		})->toArray();

		FgAsigl1Mt::insert($bidders);
	}

	/**
	 * Almacena una puja en una tabla auxiliar que no afecta a los circuitos habituales
	 */
	public function addPujaAux($typePuja)
	{
		if (!$this->checkLicitador(true)) {
			return ['status' => 'error', 'msg' => 'no_licit'];
		}

		$lot = FgAsigl0::select('ffin_asigl0', 'hfin_asigl0', 'descweb_hces1', 'impsalhces_asigl0', 'num_hces1', 'lin_hces1')
		->joinFghces1Asigl0()
			->where([
				['sub_asigl0', $this->cod],
				['ref_asigl0', $this->ref],
			])->first();

		if (!$lot) {
			return ['status' => 'error', 'msg' => 'no_lot'];
		}


		$maxLin = FgAsigl1_Aux::where([
			['sub_asigl1', $this->cod],
			['ref_asigl1', $this->ref]
		])->max('lin_asigl1');

		$maxLin = $maxLin ?: 0;

		$bid = FgAsigl1_Aux::create([
			'sub_asigl1' => $this->cod,
			'ref_asigl1' => $this->ref,
			'lin_asigl1' => $maxLin + 1,
			'licit_asigl1' => $this->licit,
			'imp_asigl1' => $this->imp,
			'fec_asigl1' => now()->format('Y-m-d H:i:s'),
			'pujrep_asigl1' => $typePuja,
			'type_asigl1' => 'N',
			'hora_asigl1' => now()->format('H:i:s'),
			'usr_update_asigl1' => 'WEB',
			'date_update_asigl1' => now()->format('Y-m-d H:i:s')
		]);

		//si es comprar
		$message = trans(config('app.theme') . "-app.msg_success.buying_lot", ['lot' => $lot->descweb_hces1, 'imp' => ToolsServiceProvider::moneyFormat($this->imp)]);

		//si es contraoferta
		if($typePuja == FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA){
			$message = trans(config('app.theme') . '-app.msg_success.counteroffer_success', ['imp' => ToolsServiceProvider::moneyFormat($this->imp)]);
		}
		elseif($typePuja == FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA){
			$urlSimilar = (new EmailLib(''))->getUrlGridLots($lot->num_hces1, $lot->lin_hces1, 'V', 0, $this->imp * 1.25);
			//$message = trans(config('app.theme') . '-app.msg_error.counteroffer_rejected', ['imp' => ToolsServiceProvider::moneyFormat($this->imp), 'url' => $urlSimilar]);
			$message = 'El Vendedor no ha aceptado tu Oferta. Puedes asegurar la Compra al precio indicado, hacer otra Oferta superior o consultar otros Vehiculos Similares acordes a tu presupuesto.';
		}
		/* if (in_array($typePuja, [FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA, FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA])) {
			$message = trans(config('app.theme') . '-app.msg_success.counteroffer_success', ['imp' => ToolsServiceProvider::moneyFormat($this->imp)]);
		} */

		return [
			'status' => 'success',
			'msg' => $message,
			'formatted_actual_bid' => ToolsServiceProvider::moneyFormat($this->imp),
			'imp' => $this->imp,
			'ref' => $this->ref,
			'bid' => $bid,
			'buy_price' => $lot->impsalhces_asigl0,
			'pujarep' => $typePuja,
			'similar_lots' => $urlSimilar ?? false
		];
	}

	public function addLowerBid($no_check_cerrado = FALSE, $pujrep_asigl1 = 'L')
	{

		if ($this->checkLicitador(1)) {

			$res = array();

			if (!$no_check_cerrado) {
				# Mira si el lote está cerrado
				$closed = DB::select(
					"SELECT CERRADO_ASIGL0 FROM FGASIGL0 WHERE EMP_ASIGL0 = :emp AND SUB_ASIGL0 = :cod_sub AND REF_ASIGL0 = :ref",
					array(
						'emp'           => Config::get('app.emp'),
						'cod_sub'       => $this->cod,
						'ref'           => $this->ref
					)
				);

				if (empty($closed) || empty(head($closed)->cerrado_asigl0) || head($closed)->cerrado_asigl0 == 'S') {
					$res['status'] = 'error';
					$res['msg'] = 'lot_closed';
					return $res;
				}


				$expire = DB::select(
					"SELECT FGASIGL0.FFIN_ASIGL0, FGASIGL0.HFIN_ASIGL0  FROM FGASIGL0
										JOIN FGSUB ON  FGSUB.EMP_SUB =  FGASIGL0.EMP_ASIGL0   AND   FGSUB.COD_SUB = FGASIGL0.SUB_ASIGL0
										WHERE
											FGASIGL0.EMP_ASIGL0 = :emp
										AND FGASIGL0.SUB_ASIGL0 = :cod_sub
										AND FGASIGL0.REF_ASIGL0 = :ref
										AND FGSUB.TIPO_SUB IN ('O','P')",
					array(
						'emp'           => Config::get('app.emp'),
						'cod_sub'       => $this->cod,
						'ref'           => $this->ref
					)
				);
				// si no esta vacio es que la subasta es de tipo 'O' o tipo 'P'
				if (!empty($expire)) {

					$fecha = head($expire);
					$fecha_fin = substr($fecha->ffin_asigl0, 0, 10) . " " . $fecha->hfin_asigl0;
					$fecha_fin_time =  strtotime($fecha_fin);
					$diferencia =   $fecha_fin_time - time();

					//el lote ya ha finalizado, no se debería poder pujar
					if ($diferencia < 0) {
						$res['status'] = 'error';
						$res['msg'] = 'lot_closed';
						return $res;
					}
					//estamos en el último minuto

					/* Por el momento no tenemos en cuanta el último minuto de la subasta en las pujas inferiores
						elseif( !empty(Config::get('app.increase_time_add')) && !empty(Config::get('app.increase_time_launch')) ){

							if ($diferencia <=Config::get('app.increase_time_launch') && $diferencia >0 ){

								$fecha_fin_time =  $fecha_fin_time + Config::get('app.increase_time_add');
								$date = date("Y-m-d", $fecha_fin_time);
								$hour = date("H:i:s", $fecha_fin_time);

								 $sql = "update FGASIGL0 set FFIN_ASIGL0 = TO_DATE(:ffin, 'YYYY-MM-DD'),  HFIN_ASIGL0=:hfin where EMP_ASIGL0 = :emp and SUB_ASIGL0 = :cod_sub and REF_ASIGL0 = :ref";

							  DB::select($sql,
								 array(
									 'emp'                => Config::get('app.emp'),
									 'cod_sub'            => $this->cod,
									 'ref'                => $this->ref,
									 'ffin'               => $date,
									 'hfin'               => $hour
									 )
								 );
							}
						} */
				}
			}

			#Comprobamos que no exista ya una puja inferior, superior a la actual
			$sql = "SELECT IMP_ASIGL1
					FROM FGASIGL1_AUX
					WHERE EMP_ASIGL1 = :emp AND SUB_ASIGL1 = :cod_sub AND REF_ASIGL1 = :ref AND LICIT_ASIGL1 = :licit AND IMP_ASIGL1 >= :imp";

			$params = array(
				'emp' => Config::get('app.emp'),
				'cod_sub' => $this->cod,
				'ref' => $this->ref,
				'licit' => $this->licit,
				'imp' => $this->imp,
			);

			$hasLowerBid = DB::select($sql, $params);

			if (!empty($hasLowerBid)) {
				$res['status'] = 'error';
				$res['msg'] = 'small_bid_inf';
				return $res;
			}

			$sql_asigl1 = "INSERT INTO FGASIGL1_AUX
						(EMP_ASIGL1, SUB_ASIGL1, REF_ASIGL1, LIN_ASIGL1, LICIT_ASIGL1, IMP_ASIGL1, FEC_ASIGL1, PUJREP_ASIGL1, TYPE_ASIGL1, HORA_ASIGL1)
					VALUES
						(:emp, :cod_sub, :ref, (SELECT NVL((MAX(CAST(LIN_ASIGL1 AS Int)) + 1), 1) AS numero FROM FGASIGL1_AUX WHERE EMP_ASIGL1 = :emp AND SUB_ASIGL1 = :cod_sub AND REF_ASIGL1 = :ref), :licit, :imp,
							SYSDATE, :type_bid, :type_asigl1,
							TO_CHAR(SYSDATE, 'HH24:MI:SS')
						)";

			$res = DB::select(
				$sql_asigl1,
				array(
					'emp'                => Config::get('app.emp'),
					'cod_sub'            => $this->cod,
					'ref'                => $this->ref,
					'licit'              => $this->licit,
					'imp'                => $this->imp,
					'type_bid'           => $pujrep_asigl1,
					'type_asigl1'        => 'N'
				)
			);

			$this->sin_pujas = false;
			$result = array(
				'status' => 'success',
				'msg' => 'correct_bid'
			);
		} else {
			$result = array(
				'status' => 'error',
				'msg' => 'no_licit',
			);
		}

		return $result;
	}


	#funcion que llama al webservice
	public function webServiceBid($licit, $codSub, $ref, $imp, $tipo, $metodo){
		if(Config::get('app.WebServiceBid')){

			$theme  = Config::get('app.theme');
			$rutaBidController = "App\Http\Controllers\\externalws\\$theme\BidController";

			$bidController = new $rutaBidController();

			$bidController->createBid($licit, $codSub, $ref, $imp, $tipo, $metodo );
		}
	}




    //DE MOMENTO NO SE HACE COPIA A SUBALIA
    private function add_puja_subalia($type_asigl1, $sql_asigl1, $sql_hces1){
         \Log::error("ADD PUJA SUBALIA ".\Config::get('app.auchouse_code') );

            if(\Config::get('app.auchouse_code')){

                $cliAucHhouse =\Config::get('app.auchouse_code');
                //try {
                $user_m = new User();
                $user_m->licit = $this->licit;
                $user_m->cod = $this->cod;
                $g = $user_m->getUserByLicit();
                \Log::info(print_r($g,true));
                    //el código de licitador es igual al de cliente pero sin los ceros de delante
                    if($g[0]->cli_licit == \Config::get('app.subalia_cli')){
                        $licit = $this->licit;
                    }else{
                        $licit = (int)$cliAucHhouse;
                    }



                    $id_origen = $cliAucHhouse."-".Config::get('app.emp')."-".$this->cod;
                    $sub_subalia = DB::connection("subalia")->select("select emp_sub,cod_sub from fgsub where idorigen_sub = :id_origen",array('id_origen'=>$id_origen ));
                    if(empty($sub_subalia)){
                         \Log::error("reTURN" );
                        return;
                    }

                    $emp = $sub_subalia[0]->emp_sub;
                    $cod_sub_subalia = $sub_subalia[0]->cod_sub;
                    //copiar puja
                    $res = DB::connection("subalia")->select($sql_asigl1,
                           array(
                               'emp'                => $emp,
                               'cod_sub'            => $cod_sub_subalia,
                               'ref'                => $this->ref,
                               'licit'              => $licit,
                               'imp'                => $this->imp,
                               'type_bid'           => $this->type_bid,
                               'type_asigl1'        => $type_asigl1
                               )
                           );
                    //actualizar implic
                    DB::connection("subalia")->select($sql_hces1,
                            array(
                                'emp'                => $emp,
                                'cod_sub'            => $cod_sub_subalia,
                                'ref'                => $this->ref,
                                'imp'                => $this->imp
                                )
                            );
                /*} catch (\Exception $e) {

                    \Log::error("Error al guardar en subalia cliAuchouse: $cliAucHhouse , subasta: ".$this->cod);
                }*/
            }


    }


    public function sobre_puja($importe_salida, $importe_orden1,$importe_orden2)
    {
         //necesitamos saber cual es el importe a partir del cual aplicamos el escalado
        $importe = max($importe_salida,$importe_orden2);

        $escalado_array = DB::select("SELECT ROWNUM, IMP_PUJAS, PUJA_PUJAS escalado, :importe FROM FGPUJAS
                                WHERE EMP_PUJAS = :emp AND :importe <= IMP_PUJAS
                                    AND ROWNUM = 1
                                ORDER BY IMP_PUJAS ASC",
                            array(
                                'emp'           => Config::get('app.emp'),
                                'importe'       => $importe
                                )
                            );

        if (empty($escalado_array))
              return $importe_orden1;
        elseif (empty($importe_orden2) || $importe_orden2==0){
            return $importe_salida;
        }
        else{
            $escalado = $escalado_array[0];
            $puja = $importe;

            while($puja <= $importe ){

                $puja +=$escalado->escalado;
            }



            if ($puja > $importe_orden1)
                return $importe_orden1;
            else


                return $puja;
        }

    }

	#puja Anterior, esta función devuelve el valor de la puja anterior al importe indicado
	public function pujaAnterior($bid, $scaleRanges){
		$end = false;
		$i = 0;
		$resultado = 0;
		while (!$end){

			if($bid > $scaleRanges[$i]->max){
					$i++;
			}else{
				   $val = $scaleRanges[$i]->min;
				   //recorremos los rangos de escala  mientras no supere el max vamos sumando el escalado
				   while ($val <= $scaleRanges[$i]->max  && !$end){
					   //si hemos superado el importe actual es que ya hemos encontrado la siguiente puja.
					   if($val > $bid){
							$resultado= $val- $scaleRanges[$i]->scale;
							$end = true;
					   }elseif($val == $bid){
							$resultado= $bid - $scaleRanges[$i]->scale;
							$end = true;
					   }else{// mientras no superemso el importe actual vamos sumando el escalado
						   $val +=$scaleRanges[$i]->scale;
					   }
				   }
			   }
		}

		return $resultado;
	}

	/**
	 * importe inicial, importe de orden mayor, segunda orden más grande
	 * @param int $importe_salida importe inicial
	 * @param int $importe_orden1 orden mas alta
	 * @param int $importe_orden2 orden actual
	 */
    public function sobre_puja_orden($importe_salida, $importe_orden1,$importe_orden2)
    {
		#si el importe de salida es 0 debemos cojer la siguiente puja válida
		if($importe_salida== 0){
			$importe_salida = head($this->AllScales())->scale;
		}
            // si no hay otra orden no hacemos lucha de ordenes y sale por importe de salida/reserva o por la orden si es mas pequeña que el precio de reserva
            if ((empty($importe_orden2) || $importe_orden2==0) ){
                return min($importe_salida,$importe_orden1 );
            }else{
                $nextbid = $this->NextScaleBid($importe_salida, $importe_orden2, TRUE);
                //si el esacalado excede la orden sacamos importe por la orden
                if($nextbid > $importe_orden1){
                    return $importe_orden1;
                }else{
                    return $nextbid;
                }

            }
    }

    //Devulve el valor que debería tener la siguiente puja teniendo en cuenta el valor actual y el escalado
    public function NextScaleBid($imp_salida,$imp_actual, $first_ol = FALSE)
    {
		#Escalado siguiendo el precio de salida del lote
		if( Config::get('app.scaleFromPrice')){
			return $this->NextScaleFromPrice($imp_salida,$imp_actual, $first_ol);
		}else{

			return $this->NextScaleNormal($imp_salida,$imp_actual, $first_ol);
		}


    }

	//Devulve el valor que debería tener la siguiente puja teniendo en cuenta el valor actual y el escalado
	public function NextScaleInverseBid($imp_salida,$imp_actual, $first_ol = FALSE)
	{
		if ( !empty($this->sin_pujas) &&  $this->sin_pujas && !$first_ol){
			return $imp_salida;
		}else{
			$scaleRanges = $this->AllScales();

			//$imp = max($imp_salida,$imp_actual);
			$end = false;
			$i = count($scaleRanges)-1;
			$val = 0;
			while (!$end){

					if($imp_actual <= $scaleRanges[$i]->min ){
						$i--;
					}else{

						if ($first_ol){
							//19/02/2018 pasamos el valor del importe para que nos ponga un valor correcto en caso de que no lo sea.
							$val = $this->NextScaleInverseBid($imp_salida,$imp_actual-1 , FALSE);

						}else{
							$val =$scaleRanges[$i]->max;
						}

						//mientras el importe sea más pequeño que el importe actual
						while ( $val >= $imp_actual ){

							#vamos sumando
							$val -=$scaleRanges[$i]->scale;

							//Si hemos llegado a 0 no podemos devolver 0 y devolvemos el ultimo importe
							if($val<= 0){
								$val +=$scaleRanges[$i]->scale;
								return $val;
							}

							#si pasamos de rango reducimos la i
							if($val <= $scaleRanges[$i]->min){

								$i --;
							}

						}

						$end = true;
					}
			}

			return $val;
		}
	}



	 private function NextScaleFromPrice($imp_salida,$imp_actual, $first_ol){

		if ( !empty($this->sin_pujas) &&  $this->sin_pujas && !$first_ol)
			return $imp_salida;
		else{
			$scaleRanges = $this->AllScales();

			//$imp = max($imp_salida,$imp_actual);
			$end = false;
			$i = 0;
			$val = 0;
			while (!$end){

					if($imp_salida >= $scaleRanges[$i]->max ){
						$i++;
					}else{
						if ($first_ol){
							//19/02/2018 pasamos el valor del importe para que nos ponga un valor correcto en caso de que no lo sea.
							$val = $this->NextScaleBid($imp_salida,$imp_actual-1 , FALSE);

						}else{
							$val =$imp_salida;
						}

						//mientras el importe sea más pequeño que el importe actual
						while ( $val <= $imp_actual ){

							#vamos sumando
							$val +=$scaleRanges[$i]->scale;

							#si pasamos de rango incrementamos la i
							if($val >= $scaleRanges[$i]->max){
								$i ++;
							}
						}


						$end = true;

					}


			}

			return $val;
		}
	 }

	 private function NextScaleNormal($imp_salida,$imp_actual, $first_ol){
		if ( !empty($this->sin_pujas) &&  $this->sin_pujas && !$first_ol)
		return $imp_salida;
		else{
			$scaleRanges = $this->AllScales();

			$imp = max($imp_salida,$imp_actual);
			$end = false;
			$i = 0;
			$val = 0;
			while (!$end){

					if($imp >= $scaleRanges[$i]->max ){
						$i++;
					}else{
						if ($first_ol){
							//19/02/2018 pasamos el valor del importe para que nos ponga un valor correcto en caso de que no lo sea.
							$val = $this->NextScaleBid(0,$imp-1 , FALSE);

						}else{
							$val = $scaleRanges[$i]->min;
						}

						//recorremos los rangos de escala  mientras no supere el max vamos sumando el escalado
						while ($val <= $scaleRanges[$i]->max  && !$end){
							//si hemos superado el importe actual es que ya hemos encontrado la siguiente puja.
							if($val > $imp_actual){
								$end = true;
							}else{// mientras no superemso el importe actual vamos sumando el escalado
								$val +=$scaleRanges[$i]->scale;

							}
						}
						if($val > $imp_actual){
								$end = true;
						}

					}


			}
			if($val==0){
				$val = $scaleRanges[$i]->scale;
			}

			return $val;
		}
	 }


     //valida si el importe pasado respeta el escalado
	 public function validateScaleInverse($imp_salida,$new_bid)
	 {

		try{

			if (!is_numeric($new_bid )){
				return false;
			}
			 //si es igual al valor de salida y aun no hay pujas es correcto
			 if ($imp_salida == $new_bid && $this->sin_pujas){
					return true;
			 }
			 //si hacen una puja por el valor de salida pero ya hay pujas
			 elseif( ($imp_salida == $new_bid && !$this->sin_pujas) || $imp_salida < $new_bid  ){
				  return false;
			 }
			 else{

				 $scaleRanges = $this->AllScales();

				 $end = false;
				 $validate = false;
				 $i = count($scaleRanges)-1;
				 while (!$end){

					 if($new_bid <= $scaleRanges[$i]->min){

							 $i--;
					}else{
							$val = $scaleRanges[$i]->max;

							//recorremos los rangos de escala  mientras no supere el max vamos sumando el escalado
							while ($val >= $scaleRanges[$i]->min  && !$end){

								//si hemos superado el importe actual es que ya hemos encontrado la siguiente puja.
								if($val < $new_bid){
									 $validate = false;
									 $end = true;
								}elseif($val == $new_bid){
									 $validate = true;
									 $end = true;
								}
								else{// mientras no superemso el importe actual vamos restando el escalado
									$val -=$scaleRanges[$i]->scale;
								}
							}
						}
				 }

				return $validate;
			 }

			}catch (\Exception $e) {
				\Log::info('Error validateScale: $imp_salida importe salida  new bid:'.$new_bid);
				  return false;
			}
	 }
     //valida si el importe pasado respeta el escalado
    public function validateScale($imp_salida,$new_bid)
    {

		#Escalado siguiendo el precio de salida del lote
		if( Config::get('app.scaleFromPrice')){
			return $this->validateScaleFromPrice($imp_salida,$new_bid);
		}else{

			return $this->validateScaleNormal($imp_salida,$new_bid);
		}
     }

	 private function validateScaleFromPrice($imp_salida,$new_bid){
		try{
			if (!is_numeric($new_bid )){
				return false;
			}
			 //si es igual al valor de salida y aun no hay pujas es correcto
			 if ($imp_salida == $new_bid && $this->sin_pujas){
					return true;
			 }
			 //si hacen una puja por el valor de salida pero ya hay pujas
			 elseif( ($imp_salida == $new_bid && !$this->sin_pujas) || $imp_salida > $new_bid  ){
				  return false;
			 }
			 else{
				 $scaleRanges = $this->AllScales();

				 $end = false;
				 $validate = false;
				 $i = 0;
				 while (!$end){

					 if($imp_salida >= $scaleRanges[$i]->max){

							 $i++;
						}else{
							$val = $imp_salida;

							//mientras el importe sea más pequeño que la puja a valorar
							while ( $val < $new_bid ){

								#vamos sumando
								$val +=$scaleRanges[$i]->scale;

								#si pasamos de rango incrementamos la i
								if($val >= $scaleRanges[$i]->max){
									$i ++;
								}
							}
							$end = true;

							if($val == $new_bid){
								$validate = true;
							}else{
								$validate = false;
							}


						}

				 }

				return $validate;
			 }

		}catch (\Exception $e) {
			\Log::info('Error validateScaleFromPrice: $imp_salida importe salida  new bid:'.$new_bid);
			  return false;
		}
	}

	 private function validateScaleNormal($imp_salida,$new_bid){
		try{

			if (!is_numeric($new_bid )){
				return false;
			}
			 //si es igual al valor de salida y aun no hay pujas es correcto
			 if ($imp_salida == $new_bid && $this->sin_pujas){
					return true;
			 }
			 //si hacen una puja por el valor de salida pero ya hay pujas
			 elseif( ($imp_salida == $new_bid && !$this->sin_pujas) || $imp_salida > $new_bid  ){
				  return false;
			 }
			 else{
				 $scaleRanges = $this->AllScales();

				 $end = false;
				 $validate = false;
				 $i = 0;
				 while (!$end){

					 if($new_bid >= $scaleRanges[$i]->max){

							 $i++;
						}else{
							$val = $scaleRanges[$i]->min;

							//recorremos los rangos de escala  mientras no supere el max vamos sumando el escalado
							while ($val <= $scaleRanges[$i]->max  && !$end){
								//si hemos superado el importe actual es que ya hemos encontrado la siguiente puja.
								if($val > $new_bid){
									 $validate = false;
									 $end = true;
								}elseif($val == $new_bid){
									 $validate = true;
									 $end = true;
								}
								else{// mientras no superemso el importe actual vamos sumando el escalado
									$val +=$scaleRanges[$i]->scale;
								}
							}

						}

				 }

				return $validate;
			 }

			}catch (\Exception $e) {
				\Log::info('Error validateScale: $imp_salida importe salida  new bid:'.$new_bid);
				  return false;
			}
	 }



     //devuelve el listado de escalados con maximos y minimos
     public function allScales()
     {

		$scales = null;
		if(!empty($this->scales)){
			$scales = $this->scales;
		}


        if(!empty($this->cod) && empty($scales)) {

            $scales = DB::select("SELECT IMP_PUJASSUB IMP_PUJAS, PUJA_PUJASSUB  as escale FROM FGPUJASSUB
                                    WHERE EMP_PUJASSUB = :emp
                                    AND SUB_PUJASSUB = :sub
                                    ORDER BY IMP_PUJAS ASC",
                               array(
                                   'emp'         =>  Config::get('app.emp'),
                                   'sub'         =>  $this->cod
                                   )
                               );


        }

         //si no hay subasta o la subasta no tiene pujas
        if(empty($scales)){
            $scales = DB::select("SELECT  IMP_PUJAS, PUJA_PUJAS as escale FROM FGPUJAS
                                           WHERE EMP_PUJAS = :emp

                                           ORDER BY IMP_PUJAS ASC",
                                  array(
                                      'emp'         =>  Config::get('app.emp')

                                      )
                                  );
        }
        if (empty($scales)){

                 return NULL;
        }
        else{

			if(empty($this->scales)){
				$this->scales = $scales;
			}

            $i = 0;
            $min = 0;
            $scale_range = array();
            foreach($scales as $scale) {

                //$values[$scale->imp_pujas] =array();
                $scale_range[$i] = new \stdClass();
                $scale_range[$i]->scale = $scale->escale;
                $scale_range[$i]->min = $min;
                $scale_range[$i]->max = $scale->imp_pujas;
                $min = $scale_range[$i]->max;
                $i++;
            }

            if($i > 0){
            //generamos un registro más con valor elevado para cubrir cualquier puja y que no quede fuera de rango
                $scale_range[$i] = new \stdClass();
                $scale_range[$i]->scale = $scale->escale;
                $scale_range[$i]->min = $min;
                $scale_range[$i]->max = 9999999999;
            }


            return $scale_range;

        }
     }




	# Escalado de pujas
    public function escalado()
    {
        $escalado = DB::select("SELECT ROWNUM, IMP_PUJAS, PUJA_PUJAS escalado, :importe FROM FGPUJAS
                                WHERE EMP_PUJAS = :emp AND :importe >= IMP_PUJAS
                                    AND ROWNUM = 1
                                ORDER BY IMP_PUJAS DESC",
                            array(
                                'emp'           => Config::get('app.emp'),
                                'importe'       => $this->imp
                                )
                            );
        if($escalado) {
            $escala = $escalado[0]->escalado;
        } else {
            $escalado = DB::select("SELECT * from FGPUJAS where  EMP_PUJAS = :emp AND PUJA_PUJAS=(select min(PUJA_PUJAS) from FGPUJAS WHERE EMP_PUJAS = :emp)",
                            array(
                                'emp'           => Config::get('app.emp')
                                )
                            );
            if(is_array($escalado) && count($escalado)>0)
            {
                $escala = $escalado[0]->puja_pujas;
            }
            else
            {
                $escala=1;
            }
        }

        if($this->escala != 1) {
            $importe = $this->imp;

            $total = ceil($importe / $escala);
            $total = $total * $escala;
        } else {
            $total = $escala;
        }

        return $total;
    }

    public function asignOrderToAllLotes(){


            $sql = "update   fghces1 a set orden_hces1 =
                    (select count(*) from fgasigl0
                    join fghces1 b on  emp_asigl0 = b.emp_hces1 and sub_asigl0 = b.sub_hces1 and numhces_asigl0 = b.num_hces1 and linhces_asigl0 = b.lin_hces1
                    where emp_asigl0 = a.emp_hces1 and sub_asigl0 = a.sub_hces1

                    and ref_asigl0 <= ( select ref_asigl0 from fgasigl0 where emp_asigl0 = a.emp_hces1 and sub_asigl0 = a.sub_hces1 and numhces_asigl0 = a.num_hces1 and linhces_asigl0 = a.lin_hces1))
                      where    emp_hces1 = :emp and   sub_hces1 = :cod_sub";

            $res = DB::select($sql,
                array(
                    'emp'      => Config::get('app.emp'),
                    'cod_sub'  => $this->cod
                    )
                );

         return true;

    }
    //si devuelve true es que esta bien
    function checkOrderLots(){
        //sumo todos los valores de orden y lo comparo al valor que tendría que dar si estuviera bien,s (min + max) / 2 * numero total de elementos
        $sql = "SELECT MAX(orden_hces1) AS MAX_ORDER, COUNT(FGASIGL0.REF_ASIGL0) NUM_LOTS, SUM(orden_hces1) VAL1, (1 + COUNT(FGASIGL0.REF_ASIGL0)) /2  * COUNT(FGASIGL0.REF_ASIGL0) VAL2   FROM FGHCES1
                JOIN FGASIGL0 ON FGASIGL0.EMP_ASIGL0 = FGHCES1.EMP_HCES1 AND FGASIGL0.SUB_ASIGL0 = FGHCES1.SUB_HCES1 AND FGASIGL0.NUMHCES_ASIGL0 = FGHCES1.NUM_HCES1  AND FGASIGL0.LINHCES_ASIGL0 = FGHCES1.LIN_HCES1
                WHERE FGHCES1.SUB_HCES1 = :cod_sub AND FGHCES1.EMP_HCES1 =:emp ";
        $res = DB::select($sql,
                array(
                    'emp'      => Config::get('app.emp'),
                    'cod_sub'  => $this->cod
                    )
                );

        if(!empty($res) && $res[0]->val1 == $res[0]->val2){
            return true;
        }else{
            return false;
        }
    }

    public function getNextAlarmLotes(){
        $res = DB::select("SELECT * FROM (
                                SELECT rownum rn, pu.* FROM (
                                        SELECT lotes.ref_hces1 FROM FGASIGL0 p
                                              INNER JOIN FGHCES1 lotes
                                                ON (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)
                                              INNER JOIN FGSUB subastas
                                                  ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
                                                WHERE p.EMP_ASIGL0 = :emp
                                                AND subastas.COD_SUB = :cod_sub
                                                AND lotes.ORDEN_HCES1 > :ref
                                                AND p.CERRADO_ASIGL0 = 'N'
                                                ORDER BY lotes.ORDEN_HCES1 ASC ) pu
                                        )".self::getOffset($this->page, $this->itemsPerPage),
                                        array(
                                            'emp'       => Config::get('app.emp'),
                                            'cod_sub'   => $this->cod,
                                            'ref'       => $this->lote,
                                            )
                                        );

        if (empty($res)){
            return FALSE;
        }

        $lotes = array();

        foreach ($res as $key => $value) {
             //solo mostrar mensaje de favorito si es el  que se encuentra a la distancia marcada
            if($value->rn == $this->itemsPerPage){
                $lotes[] = $value->ref_hces1;
            }
        }

        return $lotes;
    }

    # Obtiene la información de todos los lotes, por defecto obtenemos la informacion de pujas y ordenes ya que se cogia siempre
    public function getAllLotesInfo($lotes_info = array(), $get_pujas = true, $get_ordenes = true, $get_imagenes = true)
    {

        $lotes = array();

        foreach ($lotes_info as $key => $value) {

            $lotes[$key] = new \stdClass();
            if($value->ref_asigl0){
                $this->lote = $value->ref_asigl0;
                $this->ref = $value->ref_asigl0;
            }else{
                $this->lote = $value->ref_hces1;
                $this->ref = $value->ref_hces1;
            }
            $this->hces = $value->numhces_asigl0;
            if (empty($this->cod)){
                $this->cod = $value->cod_sub;
            }

            if(Config::get('app.pujas_maximas_mostradas') != -1) {
                $this->page = 1;
                $this->itemsPerPage = Config::get('app.pujas_maximas_mostradas');
            } else {
                # Si el valor es -1 (infinito) mostraremos todos los items
                $this->page = 'all';
                //$this->itemsPerPage = Config::get('app.pujas_maximas_mostradas');
            }
            if($get_pujas){
            	# Son los valores que se usan en la aplicación de tr, se descartan el resto.
				if(!empty($value->inversa_sub) && $value->inversa_sub == 'S'){
					$lotes[$key]->pujas = $this->getPujasInversas( false,  $value->cod_sub);
				}else{
					$lotes[$key]->pujas = $this->getPujas( false,  $value->cod_sub);
				}

            }else{
                $lotes[$key]->pujas = array();
            }

            if($get_ordenes){
                $ordenes = $this->getOrdenes('', $value->cod_sub);
            }else{
                $ordenes = array();
            }

            if (!empty($ordenes)) {
                $lotes[$key]->ordenes = $ordenes;
            }else{
                $lotes[$key]->ordenes = array();
            }

            if (!empty($lotes[$key]->pujas)) {
                $lotes[$key]->max_puja = head($lotes[$key]->pujas);
                $lotes[$key]->actual_bid = $lotes[$key]->max_puja->imp_asigl1;
            } else {
                $lotes[$key]->max_puja = 0;
                $lotes[$key]->actual_bid = $value->impsalhces_asigl0;
            }

            if(!empty($value->himp_csub)){
                $lotes[$key]->himp_csub  = $value->himp_csub;
            }else{
                 $lotes[$key]->himp_csub  = NULL;
            }
            if(!empty($value->sub_hces1)){
                $lotes[$key]->sub_hces1  = $value->sub_hces1;
            }else{
                 $lotes[$key]->sub_hces1  = NULL;
            }
            if(!empty($value->fini_asigl0)){
                $lotes[$key]->fini_asigl0  = $value->fini_asigl0;
            }else{
                 $lotes[$key]->fini_asigl0  = NULL;
            }

            if(!empty($value->orders_start)){
                $lotes[$key]->orders_start  = $value->orders_start;
            }else{
                 $lotes[$key]->orders_start  = NULL;
            }
            if(!empty($value->orders_end)){
                $lotes[$key]->orders_end  = $value->orders_end;
            }else{
                 $lotes[$key]->orders_end  = NULL;
            }
            if(!empty($value->nobj_hces1)){
                $lotes[$key]->nobj_hces1  = $value->nobj_hces1;
            }else{
                 $lotes[$key]->nobj_hces1  = 0;
            }

            if(empty($value->impsalweb_asigl0)){
                $value->impsalweb_asigl0=0;
			}

			if(!empty($value->ministerio_hces1)){
				$lotes[$key]->ministerio_hces1= $value->ministerio_hces1;
			}else {
				$lotes[$key]->ministerio_hces1='N';
			}

			if(!empty($value->permisoexp_hces1)){
				$lotes[$key]->permisoexp_hces1= $value->permisoexp_hces1;
			}else {
				$lotes[$key]->permisoexp_hces1='N';
			}

			if(!empty($value->infotr_hces1)){
				$lotes[$key]->infotr_hces1= $value->infotr_hces1;
			}else {
				$lotes[$key]->infotr_hces1='';
			}

			if(!empty($value->imgfriendly_hces1)){
				$lotes[$key]->imgfriendly_hces1= $value->imgfriendly_hces1;
			}else {
				$lotes[$key]->imgfriendly_hces1='';
			}

			if(!empty($value->controlstock_hces1)){
				$lotes[$key]->controlstock_hces1= $value->controlstock_hces1;
			}else {
				$lotes[$key]->controlstock_hces1='N';
			}

			if(!empty($value->stock_hces1)){
				$lotes[$key]->stock_hces1= $value->stock_hces1;
			}else {
				$lotes[$key]->stock_hces1='0';
			}

			$lotes[$key]->ocultarps_asigl0 = $value->ocultarps_asigl0 ?? 'N';

			$lotes[$key]->pc_hces1 = ToolsServiceProvider::moneyFormat($value->pc_hces1 ?? 0);

            $lotes[$key]->formatted_actual_bid = ToolsServiceProvider::moneyFormat($lotes[$key]->actual_bid);
            /* IMPORTANTE se debe usar el campo impsalhces_asigl0 como precio de salida  mantengo el campo formatted_impsalhces_asigl0 para que no haya ningun problema con los blames que no hayan siido modificados */
            //$lotes[$key]->formatted_impsal_hces1 = ToolsServiceProvider::moneyFormat($value->impsalhces_asigl0);
			//En caso de existir salida web, se prioriza mostrar ese precio por encima del original
            $lotes[$key]->formatted_impsalhces_asigl0 = ToolsServiceProvider::moneyFormat(!empty($value->impsalweb_asigl0) ? $value->impsalweb_asigl0 : $value->impsalhces_asigl0);

			$lotes[$key]->formatted_imptash_asigl0 = ToolsServiceProvider::moneyFormat($value->imptash_asigl0);
            $lotes[$key]->formatted_imptas_asigl0 = ToolsServiceProvider::moneyFormat($value->imptas_asigl0);
            $lotes[$key]->formatted_impres_asigl0 = ToolsServiceProvider::moneyFormat($value->impres_asigl0);
            $lotes[$key]->formatted_impsalweb_asigl0 = ToolsServiceProvider::moneyFormat($value->impsalweb_asigl0);
            //QUITADO EL 2017_07_26, NO VEO QUE SE USE

            $lotes[$key]->cod_sub           = $value->cod_sub;
            $lotes[$key]->orden_hces1       = $value->orden_hces1;
            $lotes[$key]->cerrado_asigl0    = $value->cerrado_asigl0;
            $lotes[$key]->remate_asigl0     = $value->remate_asigl0;
            $lotes[$key]->fac_hces1         = $value->fac_hces1;
            $lotes[$key]->lic_hces1         = $value->lic_hces1;


            $lotes[$key]->des_sub           = $value->des_sub;
            $lotes[$key]->titulo_hces1      = $value->titulo_hces1;
            /* IMPORTANTE se debe usar el campo impsalhces_asigl0 como precio de salida mantengo el campo impsal_hces1 para que no haya ningun problema con los blames que no hayan siido modificados*/
           $lotes[$key]->impsalhces_asigl0 = $value->impsalhces_asigl0;
           $lotes[$key]->impsalweb_asigl0 = $value->impsalweb_asigl0;

           $lotes[$key]->imptash_asigl0 = $value->imptash_asigl0;
           $lotes[$key]->imptas_asigl0 = $value->imptas_asigl0;

            //$lotes[$key]->ref_hces1         = $value->ref_hces1;
            //si existe la referencia en asigl0 es la que hay que usar no la de hces1
            if (isset($value->destacado_asigl0) ){
                $lotes[$key]->destacado_asigl0    = $value->destacado_asigl0;
			}

			if (isset($value->reference) ){
                $lotes[$key]->reference    = $value->reference;
            }else{
				$lotes[$key]->reference    = null;
			}

            if (isset($value->retirado_asigl0) ){
                $lotes[$key]->retirado_asigl0    = $value->retirado_asigl0;
            }else{
                $lotes[$key]->retirado_asigl0    ="N";
            }

            if (isset($value->ref_asigl0) ){
                $lotes[$key]->ref_asigl0         = $value->ref_asigl0;
            }
            if (isset($value->impres_asigl0) ){
                $lotes[$key]->impres_asigl0         = $value->impres_asigl0;
            }else{
                $lotes[$key]->impres_asigl0 = 0;
            }
            if (isset($value->sec_hces1)){
                $lotes[$key]->sec_hces1 = $value->sec_hces1;
            }else{
                $lotes[$key]->sec_hces1 = NULL;
            }

            if (isset($value->desadju_asigl0)){
                $lotes[$key]->desadju_asigl0 = $value->desadju_asigl0;
            }else{
                $lotes[$key]->desadju_asigl0 = 'N';
            }

			if (isset($value->es_nft_asigl0)){
                $lotes[$key]->es_nft_asigl0 = $value->es_nft_asigl0;
            }else{
                $lotes[$key]->es_nft_asigl0 = 'N';
            }

            $lotes[$key]->lin_hces1         = $value->lin_hces1;
            $lotes[$key]->num_hces1         = $value->num_hces1;
            $lotes[$key]->tipo_sub          = $value->tipo_sub;
            $lotes[$key]->subc_sub          = $value->subc_sub;
            $lotes[$key]->id_auc_sessions   = $value->id_auc_sessions;
            $lotes[$key]->compra_asigl0     = $value->compra_asigl0;
            $lotes[$key]->name              = $value->name;
            if(isset($value->descdet_hces1)){
                $lotes[$key]->descdet_hces1     = ToolsServiceProvider::friendlyDesc($value->descdet_hces1);
            }else{
                $lotes[$key]->descdet_hces1 = null;
            }
            $lotes[$key]->imagen            = $this->getLoteImg($value );
            if($get_imagenes){
                $lotes[$key]->imagenes          = $this->getLoteImages($value);
                $lotes[$key]->videos          = $this->getLoteVideos($value);
            }else{
                $lotes[$key]->imagenes          = array();

            }
			if(empty($lotes[$key]->videos )){
				$lotes[$key]->videos          = array();
			}

           if (isset($value->desc_hces1)){
            $lotes[$key]->desc_hces1        = ToolsServiceProvider::friendlyDesc($value->desc_hces1);
           }else{
               $lotes[$key]->desc_hces1        = "";
           }


            if (isset($value->descweb_hces1) ){
                 $lotes[$key]->descweb_hces1 = $value->descweb_hces1;
            }else{

                    $lotes[$key]->descweb_hces1 = "";
            }

            if (isset($value->webfriend_hces1) ){
            $lotes[$key]->webfriend_hces1 = $value->webfriend_hces1;
            }else{
               $lotes[$key]->webfriend_hces1 = "";
            }
            if (isset($value->start_session) ){
                $lotes[$key]->start_session     = $value->start_session;
            }
            if (isset($value->end_session) ){
                $lotes[$key]->end_session       = $value->end_session;
            }

            if (isset($value->orders_start) ){
                $lotes[$key]->orders_start     = $value->orders_start;
            }
            if (isset($value->orders_end) ){
                $lotes[$key]->orders_end       = $value->orders_end;
            }

            if(isset($value->implic_hces1)){
               $lotes[$key]->implic_hces1 = $value->implic_hces1;
            }

            if(isset($value->id_hces1)){
               $lotes[$key]->id_hces1 = $value->id_hces1;
            }else{
                $lotes[$key]->id_hces1 = NULL;
            }

            if(!empty($this->licit) && $this->licit > 0)
            {
              $lotes[$key]->session_name      = $this->getNameSessionslote($this->licit,$value->ref_hces1 );
            }
            if(isset($value->close_at)) {
                $lotes[$key]->close_at          = $value->close_at;
            } else {
                $lotes[$key]->close_at  = false;
            }


            //añadir campo de subasta abierta
            if(isset($value->subabierta_sub)){
                $lotes[$key]->subabierta_sub  = $value->subabierta_sub;
            }else{
                $lotes[$key]->subabierta_sub  = 'N';
            }

             //añadir campo de subasta carrito
            if(isset($value->opcioncar_sub)){
                $lotes[$key]->opcioncar_sub  = $value->opcioncar_sub;
            }else{
                $lotes[$key]->opcioncar_sub  = 'N';
            }

			  //añadir campo de subasta carrito
			if(isset($value->ordentel_sub)){
                $lotes[$key]->ordentel_sub  = $value->ordentel_sub;
            }else{
                $lotes[$key]->ordentel_sub  = 0;
            }


            // Añadir propiedades en caso de necesitarlas

            /* trabnsporte Peso y medidas */
            if(isset($value->transport_hces1)) {
                $lotes[$key]->transport_hces1 = $value->transport_hces1;
            }else{
                 $lotes[$key]->transport_hces1 = NULL;
            }
            if(isset($value->alto_hces1)) {
                $lotes[$key]->alto_hces1= $value->alto_hces1;
            }
            if(isset($value->ancho_hces1)) {
                $lotes[$key]->ancho_hces1 = $value->ancho_hces1;
            }
            if(isset($value->grueso_hces1)) {
                $lotes[$key]->grueso_hces1  = $value->grueso_hces1;
            }
            if(isset($value->peso_hces1)) {
                $lotes[$key]->peso_hces1  = $value->peso_hces1;
            }
            if(isset($value->embalaje_hces1)) {
                $lotes[$key]->embalaje_hces1  = $value->embalaje_hces1;
            }

            if(isset($value->alm_hces1)) {
                $lotes[$key]->alm_hces1  = $value->alm_hces1;
            }

            if(isset($value->contextra_hces1)){
                $lotes[$key]->contextra_hces1  = $value->contextra_hces1;
            }else{
                 $lotes[$key]->contextra_hces1  = NULL;
            }

			if(isset($value->inversa_sub)){
                $lotes[$key]->inversa_sub  = $value->inversa_sub;
            }else{
                 $lotes[$key]->inversa_sub  = 'N';
            }

            $lotes[$key]->open_price = NULL;

            if($value->tipo_sub == 'W' && ($value->subc_sub == 'S' || $value->subc_sub == 'A')  && $lotes[$key]->subabierta_sub == 'O'  ){

                //si no se han cargado antes las ordenes
                if(!$get_ordenes){
                    $ordenes = $this->getOrdenes('', $lotes[$key]->cod_sub);
                }
                $this->sin_pujas = false;
                $lotes[$key]->open_price = $this->price_open_auction($lotes[$key]->impsalhces_asigl0,$ordenes);
            }

            $lotes[$key]->subc_sub          = $value->subc_sub;

            if(isset($value->prop_hces1)){
                $lotes[$key]->prop_hces1  = $value->prop_hces1;
            }else{
                 $lotes[$key]->prop_hces1  = NULL;
            }

            if(isset($value->oferta_asigl0)){
                $lotes[$key]->oferta_asigl0  = $value->oferta_asigl0;
            }else{
                 $lotes[$key]->oferta_asigl0  = 0;
            }

            if(isset($value->comlhces_asigl0)){
                $lotes[$key]->comlhces_asigl0  = $value->comlhces_asigl0;
            }else{
                 $lotes[$key]->comlhces_asigl0  = 0;
            }

            if(isset($value->cosembcarg_hces1)){
                 $lotes[$key]->cosembcarg_hces1  = $value->cosembcarg_hces1;
            }else{
                 $lotes[$key]->cosembcarg_hces1  = 0;
            }

            if(isset($value->impadj_asigl0)){
                 $lotes[$key]->impadj_asigl0  = $value->impadj_asigl0;
            }else{
                 $lotes[$key]->impadj_asigl0  = 0;
            }

			$lotes[$key]->ocultarps_asigl0 = $value->ocultarps_asigl0 ?? 'N';

			$lotes[$key]->isItp = ToolsServiceProvider::isITPLot($lotes[$key]->sub_hces1, $lotes[$key]->ref_asigl0);

             $this->CleanStrLote($lotes[$key]);

        }

        return $lotes;
    }

    public function getLoteImg($lote)
    {
        $imagen = Config::get('app.emp').'-'.$lote->num_hces1. '-' .$lote->lin_hces1.'.jpg';
        return  $imagen;
    }

    public function getLoteImages($lote)
    {
        if(Config::get("app.new_image_folders")){
            $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'].Config::get('app.img_lot').'/'.Config::get('app.emp').'/'.$lote->num_hces1.'/';
        }else{
            $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'].Config::get('app.img_lot').'/';
        }

         $ruta_img = Config::get('app.emp').'-'.$lote->num_hces1. '-' .$lote->lin_hces1;

        $imagen = $ruta_img.'.jpg';

        $imagenes = array($imagen);

		#Nuevo metodo de recuperar todas las imagenes de un lote
		foreach (glob($ruta_carpeta.$ruta_img.'_*') as $file) {
			if (file_exists($file)){
					#debemos ordenar las iamgenes ya que si no lo hace alfabeticamente y 100 va despues de 1 y antes de 2
					$pos = explode('_', basename($file)); #nos quedamos con la parte derecha del _ que es el contador más .jpg de 005-902186-1_10.jpg nosquedamos con 10.jpg
					$index =  explode('.', basename($pos[1]));#ahora queremos la parte izquierda de 10.jpg nosquedamos con 10
					$imagenes[intval($index[0])] = basename($file);

			}
		}
		ksort($imagenes);
        return $imagenes;
    }




    public function getLoteVideos($lote)
    {
		$customPathVideos = Config::get('app.custom_path_video', '');
		$emp = $lote->emp_asigl0 ?? Config::get('app.emp');

		if(!empty($customPathVideos)){
			$ruta_carpeta = public_path("/$customPathVideos/$lote->num_hces1/$lote->lin_hces1/");
			$ruta_http = config('app.url')."/$customPathVideos/$lote->num_hces1/$lote->lin_hces1/";
		}
		else{
			$ruta_carpeta = public_path("/files/videos/$emp/$lote->num_hces1/$lote->lin_hces1/");
			$ruta_http = config('app.url')."/files/videos/$emp/$lote->num_hces1/$lote->lin_hces1/";
		}

        $videos = array();

        if (is_dir($ruta_carpeta) && $gestor = opendir($ruta_carpeta)) {
            while (false !== ($entrada = readdir($gestor))) {
                if ($entrada != "." && $entrada != "..") {
                    $videos[] = $ruta_http.$entrada;
                }
            }
            closedir($gestor);
        }



        return $videos;
    }




    # Hace la puja inicial del lote para aquellos lotes que tienen ordenes de licitación desde el ERP, etc.
   public function calculateStartBid( $lote )
    {

        # limite de pujas mostradas
            if(Config::get('app.pujas_maximas_mostradas') != -1) {
                $this->page = 1;
                $this->itemsPerPage = Config::get('app.pujas_maximas_mostradas');
            } else {
                # Si el valor es -1 (infinito) mostraremos todos los items
                $this->page = 'all';
            }
        $this->lote = $lote->ref_asigl0;
        $this->ref = $lote->ref_asigl0;
       /*
        if (empty($lote->ordenes)){
            return $lote;
        }

        */
		$importeSalida = $lote->impsalhces_asigl0;
		#si el importe de salida es 0 debemos cojer la siguiente puja válida
		if($importeSalida== 0){

			$importeSalida = head($this->AllScales())->scale;
		}

        //se debe superar el preci ode reserva y el precio de salida
       // 2017_10_10 DE MOMENTO NO
       // if ( empty($lote->max_puja) && $lote->ordenes[0]->himp_orlic >= $lote->impsalhces_asigl0 && $lote->ordenes[0]->himp_orlic >= $lote->impres_asigl0) {
        if (!empty($lote->ordenes) &&  empty($lote->max_puja) && $lote->ordenes[0]->himp_orlic >= $importeSalida ) {





            if($lote->impres_asigl0 > 0){
                $precio_salida = max($importeSalida, $lote->impres_asigl0);
            }else{
                $precio_salida = $importeSalida;
            }

            if (count($lote->ordenes) > 1) {

				if( \Config::get('app.use_credit')){

					$this->sobrePujaOrdenCredit($lote->ordenes,$precio_salida,$lote->cod_sub, $lote->reference,$lote->ref_asigl0);

				}else{

					$orden_w     = head($lote->ordenes);
					$orden_l     = $lote->ordenes[1];
					$this->licit = $orden_w->cod_licit;
					#hay un nuevo tipo de ordendes especiales, para simular el preci ode reserva, debemos hacer la puja con R si es de este tipo
					if($orden_w->tipop_orlic == 'R'){
						$this->type_bid     = 'R';
					#si la orden es telefónica, se marca la puja de libro como B, para saber que es telefónica y de libro
					}elseif($orden_w->tipop_orlic == 'T'){
						$this->type_bid     = 'B';
					}else{
						$this->type_bid     = 'E';
					}

					$this->imp    = $this->sobre_puja_orden($precio_salida, $orden_w->himp_orlic, $orden_l->himp_orlic);
					$res = $this->addPuja();
				}

            } else {
                $orden = head($lote->ordenes);
				$this->licit        = $orden->cod_licit;
					#hay un nuevo tipo de ordendes especiales, para simular el precio de reserva, debemos hacer la puja con R si es de este tipo
				if($orden->tipop_orlic == 'R'){
					$this->type_bid     = 'R';
				#si la orden es telefónica, se marca la puja de libro como B, para saber que es telefónica y de libro
				}elseif($orden->tipop_orlic == 'T'){
					$this->type_bid     = 'B';
				}else{
					$this->type_bid     = 'E';
				}

                if ($orden->himp_orlic >= $precio_salida) {

					# comprobamos si el usuario puede hacer la puja o si haciendola supera su credito
					if( \Config::get('app.use_credit')){
						$this->sobrePujaOrdenCredit($lote->ordenes,$precio_salida,$lote->cod_sub, $lote->reference,$lote->ref_asigl0);
					}
					else{
						$this->imp    = $this->sobre_puja_orden($precio_salida, $orden->himp_orlic,0);
						$res = $this->addPuja();
					}

                }

            }



        }
        # Se repite para obtener si hay pujas nuevas de las ordenes de licitacion, ademas de que se debe calcular por que hay subasta abiertas que pueden tener pujas
            $lote->pujas = $this->getPujas(false);

            if (!empty($lote->pujas)) {
                $lote->max_puja = head($lote->pujas);
                $lote->actual_bid = $lote->max_puja->imp_asigl1;
            } else {
                $lote->max_puja = 0;
                $lote->actual_bid = $lote->impsalhces_asigl0;
            }

        return $lote;

    }


    public function cancelarPuja($licit = NULL)
    {
        $where = "";
        if(!empty($licit)){
            $where = " AND LICIT_ASIGL1 = $licit ";
        }

        $sql = "DELETE FROM FGASIGL1 WHERE EMP_ASIGL1 = :emp AND SUB_ASIGL1 = :cod_sub AND REF_ASIGL1 = :ref  AND IMP_ASIGL1 = :imp $where";
        $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        'imp'           => $this->imp
                        );

        DB::select($sql, $bindings);
        //guardamos el log
        $this->cancelarLog('P', $licit);
        //buscamos las pujas actuales para ese lote, ordenadas por precio descendiente para encontrar la más alta
        $sql = "SELECT * FROM FGASIGL1 WHERE EMP_ASIGL1 = :emp AND SUB_ASIGL1 = :cod_sub AND REF_ASIGL1 = :ref ORDER BY IMP_ASIGL1 DESC";
        $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref
                        );
        $pujas =  DB::select($sql, $bindings);
        //si no hay pujas marcamos FGHCES1 como que no hay lic
        if (count($pujas) == 0){
           $implic = 0;
           $lic = 'N';
        }else{
            $implic = $pujas[0]->imp_asigl1;
            $lic = 'S';
        }
            $sql = "update fghces1 set implic_hces1 = :implic, lic_hces1= :lic where
                            num_hces1 = (select numhces_asigl0 from fgasigl0 where fgasigl0.sub_asigl0 =   :cod_sub and fgasigl0.ref_asigl0 = :ref and emp_asigl0 = :emp)
                          and
                          lin_hces1 = (select linhces_asigl0 from fgasigl0 where fgasigl0.sub_asigl0 =   :cod_sub and fgasigl0.ref_asigl0 = :ref and emp_asigl0 = :emp)

                            and emp_hces1 = :emp";


             $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        'implic'        => $implic,
                        'lic'           => $lic
                        );

                 DB::select($sql, $bindings);



    }

    # Borramos una Orden de Licitacion
    public function cancelarOrden($licit = NULL)
    {
        $where = "";

		$orlic = FgOrlic::where([
			['sub_orlic', $this->cod],
			['ref_orlic', $this->ref],
			['himp_orlic', $this->imp]
		]);


        if(!empty($licit)){
            $where = " AND LICIT_ORLIC = $licit ";

			$orlic->where('licit_orlic', $licit);
        }

        if(Config::get('app.dontRemoveBooksOrders')){
            $where = $where . "AND (TIPOP_ORLIC != 'P' OR TIPOP_ORLIC != 'O' )";

			$orlic->where([
				['tipop_orlic', '!=', 'P'],
				['tipop_orlic', '!=', 'O', 'OR']
			]);
        }

		$orlic = $orlic->first();

        $sql = "DELETE FROM FGORLIC WHERE EMP_ORLIC = :emp AND SUB_ORLIC = :cod_sub AND REF_ORLIC = :ref AND HIMP_ORLIC = :imp $where";

        $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        'imp'           => $this->imp,

                        );

        DB::select($sql, $bindings);

        $this->cancelarLog('O', $licit, $orlic->himp_orlic ?? null, $orlic->fec_orlic ?? null, Web_Cancel_Log::ACCION_ELIMINAR);
    }

    # Log de Cancelar puja, se usa en TR
    public function cancelarLog($tipo, $licit = NULL, $imp = NULL, $originalDate = null, $action = null)
    {
        if(empty($licit) && $this->licit){
            $licit = $this->licit;
        }
         if(empty($imp) && $this->imp){
            $imp = $this->imp;
        }
        if($this->is_gestor) {
            $this->is_gestor = 'S';
        } else {
            $this->is_gestor = 'N';
        }

        $sql = "INSERT INTO WEB_CANCEL_LOG (ID_EMP, ID_SUB, FECHA, ID_LICIT, IS_GESTOR, TIPO, LOTE, IMP, FECHA_INICIAL, ACCION) VALUES (:emp, :cod_sub, :fecha, :licit, :is_gestor, :tipo, :lote, :imp, :fecha_inicial, :accion)";
        $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'licit'         => $licit,
                        'fecha'         => $date ?? date('Y-m-d H:i:s'),
                        'is_gestor'     => $this->is_gestor,
                        'tipo'          => $tipo,
                        'lote'          => $this->ref,
                        'imp'          => $imp,
						'fecha_inicial' => $originalDate,
						'accion' => $action
                        );

        DB::select($sql, $bindings);
    }


    # Lotes listos para cerrar.  CRONJOB.
    public function getLotesToClose()
    {
        # Parametros a parsear en el SQL con PDO
        $params = array(
            'emp'       =>  Config::get('app.emp'),
        );

        $sql = "SELECT * FROM (
            SELECT rownum rn, pu.* FROM (
              SELECT cat.SEC_ORTSEC1, cat0.DES_ORTSEC0 categoria, p.*, subastas.*, lotes.*, csub.FAC_CSUB, ws.ESTADO, ws.REANUDACION, auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name,

               (CASE WHEN p.ffin_asigl0 IS NOT NULL AND p.hfin_asigl0 IS NOT NULL
                        THEN REPLACE(TO_DATE(TO_CHAR(p.ffin_asigl0, 'DD/MM/YY') || ' ' || p.hfin_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                        ELSE null END) close_at,
                        y
              /*Puja máxima por lote*/
                (SELECT
                    (CASE WHEN MAX(asigl1.imp_asigl1) IS NOT NULL
                        THEN MAX(asigl1.imp_asigl1)
                        ELSE asigl0.impsalhces_asigl0 END)
                    FROM FGASIGL0 asigl0
                        LEFT JOIN FGASIGL1 asigl1
                        ON (asigl1.ref_asigl1 = asigl0.REF_ASIGL0 AND asigl1.sub_asigl1 = asigl0.SUB_ASIGL0)
                     WHERE asigl0.sub_asigl0 = subastas.COD_SUB AND lotes.EMP_HCES1= asigl0.EMP_ASIGL0 AND lotes.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = asigl0.LINHCES_ASIGL0
                    GROUP BY asigl0.impsalhces_asigl0) as max_puja

                FROM FGASIGL0 p
                    INNER JOIN FGHCES1 lotes
                      ON   (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)
                    INNER JOIN FGSUB subastas
                        ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
                    LEFT JOIN FGHCES1SR lotessr
                      ON (lotessr.LIN_HCES1SR = lotes.LIN_HCES1 AND lotessr.NUM_HCES1SR = lotes.NUM_HCES1 AND lotessr.EMP_HCES1SR = lotes.EMP_HCES1)

                    /* Categorias */
                    LEFT JOIN FGORTSEC1 cat
                        ON (cat.SEC_ORTSEC1 = lotes.SEC_HCES1 AND cat.SUB_ORTSEC1 = subastas.COD_SUB AND cat.EMP_ORTSEC1 = :emp)
                    LEFT JOIN FGORTSEC0 cat0
                      ON (cat.LIN_ORTSEC1 = cat0.LIN_ORTSEC0 AND cat0.SUB_ORTSEC0 = subastas.COD_SUB AND cat0.EMP_ORTSEC0 = :emp)

                    LEFT JOIN FGCSUB csub ON (csub.SUB_CSUB = subastas.COD_SUB AND csub.EMP_CSUB = :emp AND csub.REF_CSUB = lotes.REF_HCES1)


                    JOIN \"auc_sessions\" auc ON (auc.\"auction\" = subastas.COD_SUB AND auc.\"company\" = :emp)
                    /* Tipo de estado en subastas a tiempo real por si esta pausada y cuando reanuda */
                    LEFT JOIN WEB_SUBASTAS ws ON (ws.ID_SUB = subastas.COD_SUB AND ws.ID_EMP = :emp AND  ws.session_reference = auc.\"reference\")
                    WHERE p.CERRADO_ASIGL0 = 'N' AND p.EMP_ASIGL0 = :emp AND

                    /*Descarta los lotes facturados*/
                    /*NOT EXISTS( SELECT FAC_CSUB FROM FGCSUB csub WHERE csub.SUB_CSUB = subastas.COD_SUB AND csub.EMP_CSUB = :emp AND csub.REF_CSUB = lotes.REF_HCES1) AND*/

                     (TO_DATE(TO_CHAR(p.FFIN_ASIGL0, 'DD/MM/YY') || ' ' || p.HFIN_ASIGL0, 'DD/MM/YY HH24:MI:SS') ) <= (SELECT SYSDATE FROM DUAL) AND

                    /* Limita el resultado de lotes segun los parametros DREF a HREF */
                    lotes.REF_HCES1 >= subastas.DREF_SUB
                    AND lotes.REF_HCES1 <=
                    (CASE WHEN subastas.HREF_SUB > 0
                        THEN subastas.HREF_SUB
                        ELSE 99999999999 END)
                    AND

                    subastas.TIPO_SUB IN (".$this->tipo.") AND subastas.SUBC_SUB IN ('S')
                      ) pu )";

        $subasta = DB::select($sql, $params);

        return $subasta;
    }

 		public function getImgSource ()
 		{
 			$imgsrc = DB::select
    	("SELECT PATSART_PARAMS
    		FROM FSPARAMS
        WHERE EMP_PARAMS = :emp AND CLA_PARAMS = :cla",
        array(
        	'emp' => Config::get('app.emp'),
        	'cla' => 1
        )
    	);
	    return $imgsrc;
    }

    public function  CleanStrLote($lote)
    {


            $strLib = new StrLib();
            if (isset($lote->desc_hces1)){
                $lote->desc_hces1 =  $strLib->CleanStr($lote->desc_hces1 );
            }
            if (isset($lote->titulo_hces1)){
                $lote->titulo_hces1 =  $strLib->CleanStr($lote->titulo_hces1 );
            }
            if (isset($lote->desc)){
                $lote->desc =  $strLib->CleanStr($lote->desc );
            }
            if (isset($lote->descdet_hces1)){
                $lote->descdet_hces1 =  $strLib->CleanStr($lote->descdet_hces1 );
            }
            if (isset($lote->descweb_hces1)){
                $lote->descweb_hces1 =  $strLib->CleanStr($lote->descweb_hces1 );
            }
            if (isset($lote->des_sub)){
                $lote->des_sub =  $strLib->CleanStr($lote->des_sub );
            }
            if (isset($lote->name)){
                $lote->name =  $strLib->CleanStr($lote->name );
            }
            if (isset($lote->contextra_hces1)){
                $lote->contextra_hces1 =  $strLib->CleanStr($lote->contextra_hces1 );
			}
			if (isset($lote->infotr_hces1)){
                $lote->infotr_hces1 =  $strLib->CleanStr($lote->infotr_hces1 );
			}




           return $lote;
    }
    //funcion que devuelve el listado de subastas
    function auctionList($estado = 'S', $type = NULL)
    {

		$params = array();
        //en historico ordenamos las nuevas antes
        if($estado == 'H'){
            $order_by = "session_start DESC";
        }else{
             $order_by = "session_start ASC";
        }
        if(empty($type)){
            $tipo_sub = " sub.TIPO_SUB IN ('O', 'P', 'W', 'V', 'M', 'I') AND ";
        }else{
			$tipo_sub = " sub.TIPO_SUB = :type AND ";
			$params['type'] = $type;

        }
		$join="";
		$where="";
		/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
		if(Config::get("app.restrictVisibility")){
			//si no hay usuario logeado devolvemos vacio
			if(empty(Session::get('user.cod'))){
				return [];
			}

			$join = $this->restrictVisibilityAuction("join");
			$where = $this->restrictVisibilityAuction("where");
			$params['codCli'] = Session::get('user.cod');
		}

		if(Config::get('app.restrictInvited', false)){

			if(!Session::has('user')){
				return [];
			}

			$codCli = Session::get('user.cod');
			$ownersInvites = FgSubInvites::query()
				->where('invited_codcli_subinvites', $codCli)
				->pluck('owner_codcli_subinvites');

			//añadimos el código del usuario logeado para mostrar sus propias subastas
			$ownersInvites->push($codCli);

			$ownerInvitesSqlFormat = implode(',', $ownersInvites->toArray());
			$where .= " AND agrsub_sub IN ($ownerInvitesSqlFormat) ";
		}

		if(Config::get('app.agrsub', null)){
			$auctionGroup = Config::get('app.agrsub');
			$where .= " AND agrsub_sub = :agrsub ";
			$params['agrsub'] = $auctionGroup;
		}

        $sql ="
            SELECT cod_sub, des_sub,  orders_start, orders_end,  tipo_sub, reference,name, description, id_auc_sessions, session_start, session_end,subastatr_sub, emp_sub, subc_sub,expofechas_sub,expohorario_sub,expolocal_sub,sesfechas_sub,seshorario_sub,seslocal_sub,
            emp_sub ||  '_' || cod_sub || '_' ||    reference as file_code,upcatalogo,uppdfadjudicacion,uppreciorealizado,upcatalogo_lang,uppreciorealizado_lang,upmanualuso_lang,COLORCAL_SUB ,CALINI_SUB, CALFIN_SUB
            FROM (
                SELECT sub.COD_SUB cod_sub, sub.EMP_SUB, sub.SUBC_SUB, sub.tipo_sub, sub.subastatr_sub,COLORCAL_SUB,CALINI_SUB, CALFIN_SUB,
                       NVL(fgsublang.DES_SUB_LANG,  sub.DES_SUB) des_sub,
                       NVL(fgsublang.EXPOFECHAS_SUB_LANG,  sub.expofechas_sub) expofechas_sub,
                       NVL(fgsublang.EXPOHORARIO_SUB_LANG,  sub.expohorario_sub) expohorario_sub,
                       NVL(fgsublang.EXPOLOCAL_SUB_LANG,  sub.expolocal_sub) expolocal_sub,
                       NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub) sesfechas_sub,
                       NVL(fgsublang.SESHORARIO_SUB_LANG,  sub.seshorario_sub) seshorario_sub,
                       NVL(fgsublang.SESLOCAL_SUB_LANG,  sub.seslocal_sub) seslocal_sub,
                       NVL(auc_lang.\"name_lang\",   auc.\"name\" ) name,
					   NVL(auc_lang.\"description_lang\",   auc.\"description\" ) description,
                       auc.\"reference\" reference ,auc.\"id_auc_sessions\" id_auc_sessions, auc.\"start\" session_start, auc.\"end\" session_end,
                        auc.\"orders_start\" as orders_start,  auc.\"orders_end\" as orders_end,auc.\"upCatalogo\" upcatalogo,auc.\"uppdfadjudicacion\" uppdfadjudicacion, auc.\"upPrecioRealizado\" uppreciorealizado,
                        auc_lang.\"upCatalogo_lang\" upcatalogo_lang,auc_lang.\"upPrecioRealizado_lang\" uppreciorealizado_lang, auc_lang.\"upManualUso_lang\" upmanualuso_lang
                FROM FGSUB sub
                JOIN FGASIGL0 lotes ON (sub.COD_SUB = lotes.SUB_ASIGL0 AND lotes.EMP_ASIGL0 = :emp)
                JOIN \"auc_sessions\" auc ON (auc.\"auction\" = sub.cod_sub AND auc.\"company\" = :emp)
                LEFT JOIN \"auc_sessions_lang\" auc_lang on ( auc_lang.\"id_auc_session_lang\" = auc.\"id_auc_sessions\" and auc_lang.\"auction_lang\" = sub.cod_sub and auc_lang.\"company_lang\" = :emp and auc_lang.\"lang_auc_sessions_lang\" = :lang)
                LEFT JOIN FGSUB_LANG fgsublang ON (sub.EMP_SUB = fgsublang.EMP_SUB_LANG AND sub.COD_SUB = fgsublang.COD_SUB_LANG AND  fgsublang.LANG_SUB_LANG = :lang)

				$join

                WHERE
                    auc.\"start\" IS NOT NULL AND
		    auc.\"end\" IS NOT NULL AND
                     auc.\"init_lot\" IS NOT NULL AND
		    auc.\"end_lot\" IS NOT NULL AND
                    sub.SUBC_SUB = :estado AND
                    $tipo_sub
                    sub.EMP_SUB = :emp
				$where
                GROUP BY sub.COD_SUB, NVL(fgsublang.DES_SUB_LANG,  sub.DES_SUB), sub.EMP_SUB, sub.SUBC_SUB,NVL(fgsublang.EXPOFECHAS_SUB_LANG,  sub.expofechas_sub),NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub),NVL(fgsublang.EXPOLOCAL_SUB_LANG,  sub.expolocal_sub),NVL(fgsublang.EXPOHORARIO_SUB_LANG,  sub.expohorario_sub),NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub),NVL(fgsublang.SESHORARIO_SUB_LANG,  sub.seshorario_sub),NVL(fgsublang.SESLOCAL_SUB_LANG,  sub.seslocal_sub), auc.\"orders_start\",  auc.\"orders_end\", sub.tipo_sub, NVL(auc_lang.\"name_lang\", auc.\"name\" ), NVL(auc_lang.\"description_lang\", auc.\"description\" ), auc.\"id_auc_sessions\",auc.\"reference\", auc.\"start\" , auc.\"end\",auc.\"upCatalogo\",auc.\"uppdfadjudicacion\",auc.\"upPrecioRealizado\",subastatr_sub,auc_lang.\"upCatalogo_lang\",auc_lang.\"upPrecioRealizado_lang\",auc_lang.\"upManualUso_lang\",COLORCAL_SUB,CALINI_SUB, CALFIN_SUB

            )
            ORDER BY  $order_by
		";

		$params['emp'] = Config::get('app.emp');
		$params['estado'] = $estado;
		$params['lang'] = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

        //quitamos espacios en blanco
        $name_cache ="auctions_". str_replace(" ","", $order_by. $type.$estado);

        $auctions = CacheLib::useCache($name_cache, $sql, $params);

        return $auctions;
    }
    //devuelve el registro de adjudicación de un lote
    public function getAssignetPrice()
    {
        $params = array(
            'emp'       => Config::get('app.emp'),
            'cod'   => $this->cod,
            'ref' => $this->lote

        );
        $sql ="SELECT * FROM  FGCSUB csub WHERE csub.EMP_CSUB = :emp AND csub.SUB_CSUB =  :cod AND csub.REF_CSUB = :ref";

        $res = DB::select($sql, $params);

        if(count($res) > 0){
            return head($res);
        }else{
            return NULL;
        }

    }

    public function getUrlSeo($cod_sub){
        return DB::TABLE('FGHCES1')
        ->select('WEBMETAT_HCES1','WEBMETAD_HCES1','WEBFRIEND_HCES1')
        ->where('SUB_HCES1',$cod_sub)
        ->get();
    }

    function get_csub($emp){
            $sql = "select fgcsub.* from fgcsub
                    where EMP_CSUB = :emp
                    and SUB_CSUB = :sub
                    and REF_CSUB = :ref";

            $params = array(
                'emp'       =>  $emp,
                'sub'   => $this->cod,
                'ref' => $this->lote
                );

            $licit_csub = DB::select($sql, $params);
            if (count($licit_csub) > 0){
                return head($licit_csub);
            }else{
                return null;
            }

    }
    //ver el precio
    function price_open_auction($precio_salida, $ordenes){
		if($precio_salida== 0){
			$precio_salida = head($this->AllScales())->scale;
		}
        $orden_1 = 0;
        $orden_2 = 0;
        if(count($ordenes)>0){
            $orden_1 =  $ordenes[0]->himp_orlic;
            #si la orden máxima es más pequeña que el preci ode salida, no habrá pujas en la subasta
            if($orden_1 < $precio_salida){
                return 0;
            }
            if(count($ordenes)>1){
                $orden_2 =  $ordenes[1]->himp_orlic;
            }
            return  $this->sobre_puja_orden($precio_salida, $orden_1, $orden_2);
        }else{
            return  0;
        }


    }
    //devuelve los textos de un lote en todos los idiomas
     public function getMultilanguageTextLot($num_hces,$lin_hces){
        $res = DB::select("SELECT TITULO_HCES1, DESC_HCES1, DESCWEB_HCES1,DESCDET_HCES1, DESCDET_HCES1_LANG, TITULO_HCES1_LANG, DESC_HCES1_LANG, DESCWEB_HCES1_LANG, DESCDET_HCES1_LANG, LANG_HCES1_LANG, webfriend_hces1,webfriend_hces1_lang  FROM FGHCES1 HCES1
                            LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1 AND HCES1_LANG.NUM_HCES1_LANG = HCES1.NUM_HCES1)
                            WHERE
                                    HCES1.EMP_HCES1 = :emp
                                AND
                                    HCES1.NUM_HCES1 = :num
                                AND
                                    HCES1.LIN_HCES1 = :lin",
                                        array(
                                            'emp'   => Config::get('app.emp'),
                                            'num'   => $num_hces,
                                            'lin'   => $lin_hces,
                                            )
                                        );

        if (empty($res)){
            return array();
        }

        $texts = array();
        $locales = Config::get('app.locales');
        $language_complete =  Config::get('app.language_complete');
        //guardamos el texto en el idioma principal
        $strLib = new StrLib();
        $primary_lang = strtoupper(key($locales));
        $texts[$primary_lang] = new \stdClass();
        $texts[$primary_lang]->titulo_hces1 = $strLib->CleanStr(head($res)->titulo_hces1);
		if(empty($texts[$primary_lang]->titulo_hces1)){
			$texts[$primary_lang]->titulo_hces1 = $strLib->CleanStr(head($res)->descweb_hces1);
		}
        $texts[$primary_lang]->desc_hces1 = $strLib->CleanStr(head($res)->desc_hces1);
        $texts[$primary_lang]->descweb_hces1 = $strLib->CleanStr(head($res)->descweb_hces1);
        $texts[$primary_lang]->descdet_hces1 = $strLib->CleanStr( nl2br(head($res)->descdet_hces1));
		$texts[$primary_lang]->webfriend_hces1 = $strLib->CleanStr(head($res)->webfriend_hces1);



        foreach ($res as  $text_lang) {
            $lang = strtoupper(array_search($text_lang->lang_hces1_lang, $language_complete));
            //si encuentra idioma
            if($lang !== false){
                $texts[$lang] = new \stdClass();
                $texts[$lang]->titulo_hces1 = $strLib->CleanStr($text_lang->titulo_hces1_lang);
				if(empty($texts[$lang]->titulo_hces1)){
					$texts[$lang]->titulo_hces1 = $strLib->CleanStr($text_lang->descweb_hces1_lang);
				}
                $texts[$lang]->desc_hces1 = $strLib->CleanStr($text_lang->desc_hces1_lang);
                $texts[$lang]->descweb_hces1 = $strLib->CleanStr($text_lang->descweb_hces1_lang);
                $texts[$lang]->descdet_hces1 = $strLib->CleanStr(nl2br($text_lang->descdet_hces1_lang));
                $texts[$lang]->webfriend_hces1 = $strLib->CleanStr(head($res)->webfriend_hces1_lang);
            }

        }
        foreach($language_complete as $key_lang => $lang_complete){
            if(!isset($texts[strtoupper($key_lang)])){
                $texts[strtoupper($key_lang)] = new \stdClass();
                $texts[strtoupper($key_lang)]->titulo_hces1 = $texts[$primary_lang]->titulo_hces1;
				if(empty($texts[strtoupper($key_lang)]->titulo_hces1)){
					$texts[strtoupper($key_lang)]->titulo_hces1 = $texts[$primary_lang]->descweb_hces1;
				}
                $texts[strtoupper($key_lang)]->desc_hces1 = $texts[$primary_lang]->desc_hces1;
                $texts[strtoupper($key_lang)]->descweb_hces1 = $texts[$primary_lang]->descweb_hces1;
                $texts[strtoupper($key_lang)]->descdet_hces1 = nl2br($texts[$primary_lang]->descdet_hces1);
                $texts[strtoupper($key_lang)]->webfriend_hces1 = $strLib->CleanStr(head($res)->webfriend_hces1);
            }
        }


        return $texts;
    }

    public function getCancelarPuja($count = false){

           $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'licit'           => $this->licit,
                        'ref'   => $this->ref
                        );


          if(empty($count)){
             $sql = "Select * cuantos
                       FROM WEB_CANCEL_LOG WCL
                       INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = WCL.ID_SUB AND AUC.\"company\" = WCL.ID_EMP
                       WHERE WCL.ID_EMP = :emp AND WCL.ID_SUB = :cod_sub AND WCL.ID_LICIT = :licit
                       AND
                       AUC.\"init_lot\" >= :ref
                       AND
                       AUC.\"init_lot\" <= :ref";
            return DB::select($sql, $bindings);
          }else{
               $sql = "Select count(WCL.id_sub) cuantos
                       FROM WEB_CANCEL_LOG WCL
                       INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = WCL.ID_SUB AND AUC.\"company\" = WCL.ID_EMP
                       WHERE WCL.ID_EMP = :emp AND WCL.ID_SUB = :cod_sub AND WCL.ID_LICIT = :licit
					   AND WCL.FECHA > AUC.\"start\"";

				if(!empty(Config::get('app.time_to_automatic_blocking_licit_cancel_bids', 0))){
					$sql .= " AND WCL.FECHA > '" . date("d/m/Y H:i:s", strtotime( "-" . Config::get('app.time_to_automatic_blocking_licit_cancel_bids', '0') . " second"))."' ";
				}

				$sql .= " AND AUC.\"init_lot\" <= :ref
                       AND AUC.\"end_lot\" >= :ref";
               return head(DB::select($sql, $bindings))->cuantos;
          }

    }

    public function titularidadMultiple($num,$lin){
        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'num'   => $num,
            'lin'   => $lin
            );

        $sql = "SELECT CLI_HCESMT FROM FGHCESMT WHERE EMP_HCESMT = :emp AND NUM_HCESMT = :num AND LIN_HCESMT = :lin";
           return DB::select($sql, $bindings);
    }

    //Lote que esta en otra subasta activa
    public function getLotActiveSession($num,$lin){
        $params = array(
            'emp'       => Config::get('app.emp'),
            'num'   => $num,
            'lin'      => $lin,
            'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))

        );

        $sql="SELECT *
                FROM FGASIGL0  asigl0
                INNER JOIN FGHCES1  hces1 ON (hces1.EMP_HCES1 = :emp AND hces1.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND hces1.LIN_HCES1 = asigl0.LINHCES_ASIGL0)
                LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG =  HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1 AND HCES1_LANG.LANG_HCES1_LANG = :lang)
                INNER JOIN FGSUB SUB ON SUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND SUB.COD_SUB = ASIGL0.SUB_ASIGL0
                INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = SUB.COD_SUB AND AUC.\"company\" = ASIGL0.EMP_ASIGL0

                WHERE
                asigl0.cerrado_asigl0 = 'N'
                AND asigl0.EMP_ASIGL0 = :emp
                AND asigl0.NUMHCES_ASIGL0 = :num
                AND  asigl0.LINHCES_ASIGL0 = :lin
                AND sub.subc_sub = 'S'
                AND AUC.\"init_lot\" >= asigl0.ref_asigl0
                AND AUC.\"init_lot\" <= asigl0.ref_asigl0";


        return $res = DB::select($sql, $params);
    }

    public function add_email_adjudicado($result){
        try {

            if(Config::get('app.add_email_adjudicado') && $result['status'] == 'ok'){
                $inf_sub = $this->getInfSubasta();
                 $exist = DB::table('WEB_EMAIL_CLOSLOT')
                        ->where('ID_EMP',Config::get('app.emp'))
                        ->where('ID_SUB',$this->cod)
                        ->where('ID_REF',$this->lote)
                        ->where('sended','=','N')
                        ->get();

                if(count($exist) ==0  && $inf_sub->subc_sub == 'S'){
                    DB::table('WEB_EMAIL_CLOSLOT')->insert([
                    ['ID_EMP' => Config::get('app.emp'), 'ID_SUB' => $this->cod,'ID_REF'=>$this->lote, 'SENDED' => 'N']
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error Insert into  cod sub: $this->cod, ref:$this->lot ");
            return;
        }


    }

    public function getCedentesSub(){
        $propietarios = array();
         $params = array(
            'emp'       => Config::get('app.emp'),
            'gemp'       => Config::get('app.gemp'),
            'sub'   => $this->cod
        );

          $sql="SELECT cli.cod_cli, cli.rsoc_cli
                FROM FGASIGL0  asigl0
                INNER JOIN FGHCES1  hces1 ON (hces1.EMP_HCES1 = :emp AND hces1.NUM_HCES1 = asigl0.NUMHCES_ASIGL0  AND hces1.LIN_HCES1 = asigl0.LINHCES_ASIGL0)
                INNER JOIN FXCLI cli on (cli.cod_cli = hces1.PROP_HCES1 and  cli.gemp_cli = :gemp)
                WHERE
                asigl0.EMP_ASIGL0 = :emp
                AND asigl0.sub_asigl0 = :sub";
          $res = DB::select($sql, $params);

          foreach($res as $val){
              $propietarios[$val->cod_cli] = $val->rsoc_cli;
          }
         return $propietarios;
    }

    public function getAlmLot()

    {

        $sql = "Select ALM.*
            FROM FGASIGL0 p
            INNER JOIN FGHCES1 lotes
            ON (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)
            LEFT JOIN FGCSUB CSUB ON (CSUB.EMP_CSUB = p.EMP_ASIGL0 AND CSUB.sub_CSUB = p.SUB_ASIGL0 AND REF_CSUB = p.REF_ASIGL0)
            LEFT JOIN FXALM ALM ON (ALM.COD_ALM = lotes.ALM_HCES1 AND p.EMP_ASIGL0=ALM.EMP_ALM)
            WHERE p.EMP_ASIGL0      = :emp
            AND p.SUB_ASIGL0    = :cod_sub
            AND p.REF_ASIGL0    = :lote
            ";

        return DB::select($sql,
                            array(
                                'emp'       => Config::get('app.emp'),
                                'cod_sub'   => $this->cod,
                                'lote'      => $this->lote,
                                )
                            );


    }

    public function getParametersSub()
	{
		$parameters = Cache::remember('fgprmsub', 600, function () {
			return DB::select("select * from fgprmsub where emp_prmsub = :emp", ['emp' => Config::get('app.emp')]);
		});
		//original
		//$parameters = DB::select("select * from fgprmsub where emp_prmsub = :emp", ['emp' => Config::get('app.emp')]);
        if(count($parameters) > 0){
            return head($parameters);
        }else{
            return NULL;
        }
    }

    public function get_year($num,$lin){
        $sql = "select \"year\" from \"object_types_values\"  where \"company\" = :company and \"transfer_sheet_number\" = :num and \"transfer_sheet_line\" = :lin ";
        $params = array(
            'company'       => Config::get('app.emp'),
            'num'   => $num,
            'lin'      => $lin,
            );
        $year =  DB::select($sql,$params);

        if(!empty($year)){
            return $year[0]->year;
        }else{
            return null;
        }

    }

    public function lotsNumLin($num,$lin,$all = true){
       $val = DB::table('FGASIGL0')
               ->select('FGASIGL0.*,himp_csub')
                ->leftJoin('FGCSUB FGC',function($join){
                    $join->on('FGC.EMP_CSUB','=','FGASIGL0.EMP_ASIGL0')
                    ->on('FGC.SUB_CSUB','=','FGASIGL0.sub_asigl0')
                    ->on('FGC.REF_CSUB','=','FGASIGL0.ref_asigl0');
                })
               ->where('numhces_asigl0',$num)
               ->where('linhces_asigl0',$lin)
               ->whereNotNull('fini_asigl0')
               ->where('emp_asigl0',\Config::get('app.emp'))
               ->orderBy('fini_asigl0','desc');
               if($all){
                   return $val->first();
               }else{
                   return $val->get();
               }

    }

    public function update_zero_price(){
        $importe = $this->NextScaleBid(0, 1,False);


        $sql="UPDATE  fgasigl0 SET impsalhces_asigl0 = :importe where emp_asigl0= :emp and sub_asigl0 = :cod_sub AND impsalhces_asigl0 = 0";

        $params = array(
            'emp'       => Config::get('app.emp'),
            'cod_sub'   => $this->cod,
            'importe'      => $importe,
            );
        \Log::error("Modificar los lotes con valor 0");
          DB::select($sql,$params);
    }

    public function getAuctionWithSession($id_auc_sessions){
        $sql = "Select sub.*
               FROM \"auc_sessions\" auc
               JOIN FGSUB sub ON (auc.\"auction\" = sub.cod_sub AND sub.EMP_SUB = auc.\"company\")
               where auc.\"company\" = :emp
               AND auc.\"id_auc_sessions\" = :id_auc_sessions
               AND sub.subc_sub IN ('S','A')
        ";

        $params = array(
			'emp'   =>  Config::get('app.emp'),
			'id_auc_sessions'   =>  $id_auc_sessions,
        );

        $auctions = DB::select($sql, $params);

        if(!empty($auctions)){
            return head($auctions);
        }else{
            return NULL;
        }
    }

    public function getFiles($cod_sub)
	{
        $sql = "Select files.\"description\", files.\"type\", files.\"path\" , files.\"img\" , files.\"url\" "
                . "FROM \"auc_sessions_files\" files "
                . "WHERE files.\"company\" = :emp "
                . "AND files.\"auction\" = :cod_sub "
                . "AND files.\"lang\" = :lang "
                . "ORDER BY files.\"order\"";

        $params = array(
            'emp'   =>  Config::get('app.emp'),
            'lang'  => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')),
            'cod_sub' => $cod_sub
        );

        $files = DB::select($sql, $params);
        return $files;
	}

	/**
	 * Devuelve el primer archivo de una subasta sin tener en cuenta el idioma
	 * Vico lo necesitaba para no tener que subir los archivos en todos los idiomas
	 */
	public function getFirstFileWithoutLocale($auctionId)
	{
		$file = DB::table('"auc_sessions_files"')
			->select('"type"', '"path"', '"url"')
			->where([
				['"company"', '=', Config::get('app.emp')],
				['"auction"', '=', $auctionId]
			])
			->first();

		return $file;
	}

	/**
	 * Comprueba si un usuario tiene crédito suficiente para realizar una puja
	 * En el caso de no pasar referencia de sesión, se comprueba el crédito utilizado
	 * en todas las sesiones de la subasta
	 */
	public static function allowBidCredit($codSub, $referenceSession, $licit, $imp)
	{
			$credit = FxCli::getCurrentCredit($codSub,$licit);

			#ya adjudicado
			$user = new User();
			$totalAdjudicado = empty($referenceSession)
				? $user->getSumAdjudicacionesSubasta($codSub, $licit)
				: $user->getTotalAdjudicado($codSub, $referenceSession, $licit);

			# si al sumar esta puja se han pasado del credito devolvemos error
			return ($imp + $totalAdjudicado) <= $credit;
	}

	#sobre puja inicial de subasta en caso de que haya credito, funcion recursiva
	public function sobrePujaOrdenCredit($ordenes,$precio_salida, $codSub, $referenceSession, $ref ){
		if($precio_salida== 0){
			$precio_salida = head($this->AllScales())->scale;
		}

		$ordenGanadora=null;
		$importeSuperar=0;
		$user = new User();
		foreach($ordenes as $orden){

			$credit = FxCli::getCurrentCredit($codSub,$orden->cod_licit);
			$totalAdjudicado = $user->getSumAdjudicacionesSubasta($codSub, $orden->cod_licit);
			#como minimo será cero, no deberia salir numero negativos pero por si acaso.
			$disponible = max($credit - $totalAdjudicado, 0);

			#cogemos el credito disponible si es más pequeño que la orden
			if($orden->himp_orlic > $disponible){

				Log::info("CREDIT-Precio salida, No hay credito suficiente, Subasta: $codSub lote: $ref licitador: ". $orden->cod_licit  ." orden: " . $orden->himp_orlic. " disponible: $disponible  ");
				$orden->himp_orlic = $disponible;
			}

			#si el importe no llega al precio de salida no tenemos encuenta esa orden
			if($orden->himp_orlic >= $precio_salida){

				#si no exite ganadora o si esta es mas grande o igual pero se realizó antes
				if (empty($ordenGanadora) || $orden->himp_orlic > $ordenGanadora->himp_orlic
				|| ($orden->himp_orlic == $ordenGanadora->himp_orlic  && strtotime($orden->fec_orlic) < strtotime($ordenGanadora->fec_orlic) )){
					#si ya existia una ganadora, cojemos su importe ya que será el máximo rival
					if(!empty( $ordenGanadora)){
						$importeSuperar=  $ordenGanadora->himp_orlic;
					}
					$ordenGanadora = $orden;
				}elseif($orden->himp_orlic > $importeSuperar){
					#si la orden que viene es más grande que la ganadora que ya teniamos

						$importeSuperar = $orden->himp_orlic;

				}
			}

		}

		if(!empty($ordenGanadora)){

			$this->imp    = $this->sobre_puja_orden($precio_salida, $ordenGanadora->himp_orlic, $importeSuperar);
			if($ordenGanadora->tipop_orlic == 'R'){
				$this->type_bid     = 'R';
			#si la orden es telefónica, se marca la puja de libro como B, para saber que es telefónica y de libro
			}elseif($ordenGanadora->tipop_orlic == 'T'){
				$this->type_bid     = 'B';
			}else{
				$this->type_bid     = 'E';
			}
			$this->licit = $ordenGanadora->cod_licit;

			$res = $this->addPuja();
		}

	}

	/**
	 * Verifica si a un lote se le debe aplicar o no el importe de licencia de exportación
	 */
	public function hasExportLicense($num_hces1, $lin_hces1)
	{
		$params = [
			'emp' => config('app.emp'),
			'num_hces' => $num_hces1,
			'lin_hces' => $lin_hces1,
		];

		$query = 'select "exportacion" from "object_types_values" where "company" = :emp and "transfer_sheet_number" = :num_hces and "transfer_sheet_line" = :lin_hces';
		$exportacion = collect(DB::select($query, $params))->first();

		if($exportacion && $exportacion->exportacion != 'N'){
			return true;
		}

		return false;
	}


	#devuelve la parte de la query que se debe incluir si queremos restringir la visibilidad usando la tabla FGVISIBILIDAD
	public function restrictVisibilityAuction($part){

		if($part == "join"){
			return "left join
			-- buscar subastas del usuario, o de cualquier usuario,  agrupamos por subastas por que da igual que tengan un lote o más
			(
			select  FGVISIBILIDAD.EMP_VISIBILIDAD, FGVISIBILIDAD.SUB_VISIBILIDAD, MAX(FGVISIBILIDAD.CLI_VISIBILIDAD) CLI_VISIBILIDAD  from FGVISIBILIDAD
			-- NO PODEMOS COJER UNA OPCION COMO VISIBLE SI EL USUARIO LA TIENE PUESTA COMO NO VISIBLE EXPRESAMENTE POR ESO COMPARAMOS SUBASTA Y LOTE con la inversa, SI EL LOTE COINCIDE CON UNO QUE NO ES VISIBLE NO SE PUEDE VER, O SI EL USUARIO TIENE LA SUBASTA ENTERA COMO NO VISIBLE
			left Join FGVISIBILIDAD   INVERSA ON INVERSA.EMP_VISIBILIDAD = FGVISIBILIDAD.EMP_VISIBILIDAD AND INVERSA.SUB_VISIBILIDAD = FGVISIBILIDAD.SUB_VISIBILIDAD AND (INVERSA.REF_VISIBILIDAD = FGVISIBILIDAD.REF_VISIBILIDAD  OR INVERSA.REF_VISIBILIDAD IS NULL)    AND  INVERSA.INVERSO_VISIBILIDAD = 'S' AND  INVERSA.CLI_VISIBILIDAD =  :codCli
			where (FGVISIBILIDAD.CLI_VISIBILIDAD =  :codCli OR FGVISIBILIDAD.CLI_VISIBILIDAD is null )  AND FGVISIBILIDAD.INVERSO_VISIBILIDAD ='N'  AND INVERSA.CLI_VISIBILIDAD IS NULL
			group by FGVISIBILIDAD.EMP_VISIBILIDAD, FGVISIBILIDAD.SUB_VISIBILIDAD
			) VISIBILIDAD_SUBASTAS on VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = EMP_SUB and VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = COD_SUB

			-- mirar si el usuario tiene visibilidad en todas las subastas
			left join FGVISIBILIDAD VISIBILIDAD_TODAS_SUBASTAS ON VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD =  :codCli  AND INVERSO_VISIBILIDAD ='N' AND VISIBILIDAD_TODAS_SUBASTAS.SUB_VISIBILIDAD IS NULL

			-- MIRAMOS SI HAY ALGUNA NORMA INVERSA PARA ESTE USUARIO O PARA TODOS que afecte a todas las subastas
			left join FGVISIBILIDAD INVERSO_VISIBILIDAD_SUBASTAS on INVERSO_VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = EMP_SUB and INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = COD_SUB AND INVERSO_VISIBILIDAD_SUBASTAS.INVERSO_VISIBILIDAD ='S' AND
				(INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD = :codCli OR INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD is null ) AND INVERSO_VISIBILIDAD_SUBASTAS.REF_VISIBILIDAD IS NULL
			";
		}elseif($part=="where"){
			return"--comprobar que el left join visibilidad subasta devuelve resultado para esta subasta o que el usuario tiene visibilidad en todas las subastas
			AND (VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL)

			-- si el usuario no tiene visibilidad universal o  si la tiene pero no tiene oculta esta subasta o esa subasta esta oculta y el tiene esa subasta activa o almenos un lote
			AND( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NULL OR ( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL AND (VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NULL)))
			";
		}else{
			return "";
		}



	}


	#devuelve la parte de la query que se debe incluir si queremos restringir la visibilidad usando la tabla FGVISIBILIDAD
	public function restrictVisibilityLot($part){
		if($part == "join"){
			return "
			-- buscar lotes con permiso de visualizacion para este usuario o para todos
			left join FGVISIBILIDAD VISIBILIDAD_LOTES ON VISIBILIDAD_LOTES.EMP_VISIBILIDAD = emp_asigl0 and  (VISIBILIDAD_LOTES.CLI_VISIBILIDAD = :codCli OR   VISIBILIDAD_LOTES.CLI_VISIBILIDAD IS NULL )  AND VISIBILIDAD_LOTES.INVERSO_VISIBILIDAD ='N' AND VISIBILIDAD_LOTES.SUB_VISIBILIDAD = SUB_ASIGL0 AND VISIBILIDAD_LOTES.REF_VISIBILIDAD  = REF_ASIGL0


			-- buscar subastas con todos los lotes, de este usuario o de todos
			left join FGVISIBILIDAD VISIBILIDAD_SUBASTAS ON  VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = emp_asigl0 and  (VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD = :codCli OR   VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD IS NULL )  AND VISIBILIDAD_SUBASTAS.INVERSO_VISIBILIDAD ='N'  AND VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = SUB_ASIGL0 AND VISIBILIDAD_SUBASTAS.REF_VISIBILIDAD IS NULL


			-- mirar si el usuario tiene visibilidad en todas las subastas
			left join FGVISIBILIDAD VISIBILIDAD_TODAS_SUBASTAS ON VISIBILIDAD_TODAS_SUBASTAS.EMP_VISIBILIDAD = emp_asigl0 and VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD = :codCli  AND VISIBILIDAD_TODAS_SUBASTAS.INVERSO_VISIBILIDAD ='N' AND VISIBILIDAD_TODAS_SUBASTAS.SUB_VISIBILIDAD IS NULL

			-- MIRAMOS SI HAY ALGUNA NORMA INVERSA PARA ESTE USUARIO O PARA TODOS
			left join FGVISIBILIDAD INVERSO_VISIBILIDAD_SUBASTAS on INVERSO_VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = EMP_ASIGL0 and INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = SUB_ASIGL0 AND INVERSO_VISIBILIDAD_SUBASTAS.INVERSO_VISIBILIDAD ='S' AND
				(INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD = :codCli OR INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD is null ) AND INVERSO_VISIBILIDAD_SUBASTAS.REF_VISIBILIDAD IS NULL


			left join FGVISIBILIDAD INVERSO_VISIBILIDAD_LOTE on INVERSO_VISIBILIDAD_LOTE.EMP_VISIBILIDAD = EMP_ASIGL0 and INVERSO_VISIBILIDAD_LOTE.SUB_VISIBILIDAD = SUB_ASIGL0 AND INVERSO_VISIBILIDAD_LOTE.INVERSO_VISIBILIDAD ='S' AND
				(INVERSO_VISIBILIDAD_LOTE.CLI_VISIBILIDAD = :codCli OR INVERSO_VISIBILIDAD_LOTE.CLI_VISIBILIDAD is null ) AND INVERSO_VISIBILIDAD_LOTE.REF_VISIBILIDAD= REF_ASIGL0


			";
		}elseif($part=="where"){
			return"
			-- el lote no está bloqueado, o el usuario tiene permiso expreso de ver ese lote()
			AND ( INVERSO_VISIBILIDAD_LOTE.REF_VISIBILIDAD is null or (VISIBILIDAD_LOTES.REF_VISIBILIDAD is not null AND VISIBILIDAD_LOTES.CLI_VISIBILIDAD is not null) )

			--comprueba si puede ver el lote, o si puede ver la subasta entera, o si este usuario puede ver todas las subastas
			AND ( VISIBILIDAD_LOTES.REF_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL)

			-- si una subasta esta como invisible, solo afecta a los usuarios que pueden ver todas las subastas por lo que  si el usuario no tiene visibilidad universal o  si la tiene pero no tiene oculta esta subasta, o si esta oculta pero el usuario tiene expresamente activa esta subasta o un lote de la subasta
			AND( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NULL OR ( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL AND  (INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NULL OR  VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR   VISIBILIDAD_LOTES.REF_VISIBILIDAD IS NOT NULL)))


			-- SI LA SUBASTA NO ESTA BLOQUEADA O SI ESTA  BLOQUEADA PERO EL USUARIO TIENE EXPRESAMENTE VISIBLE LA SUBASTA O ALGUN LOTE
			AND (INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NULL OR (VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_LOTES.CLI_VISIBILIDAD IS NOT NULL)  )

			";
		}else{
			return "";
		}


	}

	public function getCalendarsLinks($auctions)
	{
		$calendarsLinks = array_map(function($auction) {

			$link = ToolsServiceProvider::url_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions, $auction->reference);
			if($auction->tipo_sub === FgSub::TIPO_SUB_PRESENCIAL && strtotime($auction->session_end) > time()){
				$link = ToolsServiceProvider::url_real_time_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions);
			}

			$titleText = "Inicio de subasta " . $auction->des_sub;

			$calendars = [
				'auction_id' => $auction->id_auc_sessions,
				'google' => $this->getGoogleCalendarLink($titleText, $auction->des_sub, $auction->session_start, $auction->session_end, $link),
				'outlook' => $this->getOutlookCalendarLink($titleText, $auction->des_sub, $auction->session_start, $auction->session_end, $link),
				'yahoo' => $this->getYahooCalendarLink($titleText, $auction->des_sub, $auction->session_start, $auction->session_end, $link),
				'icalendar' => $this->getICalendarLink($titleText, $auction->des_sub, $auction->session_start, $auction->session_end, $link)
			];

			return $calendars;
		}, $auctions);

		return $calendarsLinks;
	}

	private function getGoogleCalendarLink($title, $description, $startDate, $endDate, $link)
	{
		$startDate = $this->changeDateFormatToCalendar($startDate);
		$endDate = $this->changeDateFormatToCalendar($endDate);
		$details = "$description<br><br><a href='$link'>$link</a>";
		return "https://www.google.com/calendar/render?action=TEMPLATE&text=" . $title . "&dates=" . $startDate . "/" . $endDate . "&details=" . $details . "&location=";
	}

	private function getOutlookCalendarLink($title, $description, $startDate, $endDate, $link)
	{
		$startDate = $this->changeDateFormatToCalendar($startDate, 'Y-m-d\TH:i:s\Z');
		$endDate = $this->changeDateFormatToCalendar($endDate, 'Y-m-d\TH:i:s\Z');
		$details = "$description<br><br><a href='$link'>$link</a>";
		return "https://outlook.live.com/calendar/0/deeplink/compose?path=/calendar/action/compose&rru=addevent&startdt=" . $startDate . "&enddt=" . $endDate . "&subject=" . $title . "&body=" . $details . "&location=";
	}

	private function getYahooCalendarLink($title, $description, $startDate, $endDate, $link)
	{
		$startDate = $this->changeDateFormatToCalendar($startDate);
		$endDate = $this->changeDateFormatToCalendar($endDate);
		$details = "$description<br><br><a href='$link'>$link</a>";
		return "https://calendar.yahoo.com/?v=60&view=d&type=20&title=" . $title . "&st=" . $startDate . "&et=" . $endDate . "&desc=" . $details . "&in_loc=";
	}

	private function getICalendarLink($title, $description, $startDate, $endDate, $link)
	{
		$startDate = $this->changeDateFormatToCalendar($startDate);
		$endDate = $this->changeDateFormatToCalendar($endDate);

		$fileData = "
		BEGIN:VCALENDAR
		VERSION:2.0
		PRODID:-//ZContent.net//Zap Calendar 1.0//EN
		CALSCALE:GREGORIAN
		METHOD:PUBLISH
		BEGIN:VEVENT
		SUMMARY:$title
		DESCRIPTION:$description
		DTSTART:$startDate
		DTEND:$endDate
		DTSTAMP:$startDate
		URL:$link
		LOCATION:
		END:VEVENT
		END:VCALENDAR";

		//eliminar espacios en blanco entre lineas
		$fileData = preg_replace('/^\s+|\s+$/m', '', $fileData);

		$base64File = base64_encode($fileData);

		return "data:text/calendar;base64," . $base64File;
	}

	private function changeDateFormatToCalendar($date, $format = 'Ymd\THis\Z')
	{
		$timeZone = config('app.timezone', 'Europe/Madrid');
		$date = new \DateTime($date, new \DateTimeZone($timeZone));
		$date->setTimezone(new \DateTimeZone('UTC'));
		$date = $date->format($format);

		return $date;
	}

	public function getNextScaleValue($import)
	{
		if(!$this->getAttribute('cod') && !$this->cod) {
			return 0;
		}

		$this->cod = $this->cod ?? $this->getAttribute('cod');

		$scales = $this->allScales();
		$nextScale = collect($scales)->first(function($scale) use ($import) {
			return $scale->min < $import && $import < $scale->max;
		});

		return $nextScale->scale ?? 0;
	}

	public function getActiveAuctionsUserHasBids()
	{
		$auctions = FgSub::query()
			->select('cod_sub')
			->joinlangSub()
			->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)
			->when(Config::get('app.agrsub'), function($query) {
				$query->where('agrsub_sub', Config::get('app.agrsub'));
			})
			->whereExists(function($query) {
				$query->select(DB::raw(1))
					->from('fgasigl1')
					->join('fglicit', 'fglicit.emp_licit = fgasigl1.emp_asigl1 and fglicit.cod_licit = fgasigl1.licit_asigl1 and fglicit.sub_licit = fgasigl1.sub_asigl1')
					->whereColumn('fgasigl1.sub_asigl1', 'fgsub.cod_sub')
					->where('fgasigl1.emp_asigl1', Config::get('app.emp'))
					->where('fglicit.cli_licit', $this->licit);
				})
			->get();

		return $auctions;
	}

	public function getActiveAuctionsUserHasFavorites()
	{
		$auctions = FgSub::query()
			->select('cod_sub')
			->joinlangSub()
			->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)
			->when(Config::get('app.agrsub'), function($query) {
				$query->where('agrsub_sub', Config::get('app.agrsub'));
			})
			->whereExists(function($query) {
				$query->select(DB::raw(1))
					->from('web_favorites')
					->whereColumn('web_favorites.id_sub', 'fgsub.cod_sub')
					->whereColumn('web_favorites.id_emp', 'fgsub.emp_sub')
					->where('web_favorites.cod_cli', $this->licit);
				})
			->get();

		return $auctions;
	}

	/**
	 * Verifica si un usuario puede pujar en una subasta según credito o riescli
	 * @param array $user
	 * @param object $lote
	 * @param Subasta $subasta
	 * @param bool $is_gestor
	 * @param User $gestor
	 * @return bool
	 */
	function canBid($user, $lote, $subasta, $is_gestor)
	{
		// Verifica si puede pujar usando crédito
		if ($this->usesCredit($user, $lote)) {
			return $this->validateCreditBid($subasta);
		}

		// Verifica el límite de adjudicaciones del cliente en subastas
		if ($this->adjudicationLimit($user, $lote, $is_gestor)) {
			return $this->validateAdjudications($user, $subasta);
		}

		// Si no se cumple ninguna de las condiciones anteriores, se permite la puja
		return true;
	}

	private function usesCredit($user, $lote)
	{
		return Config::get('app.use_credit') && count($user) > 0 && $lote->tipo_sub === 'W';
	}

	private function validateCreditBid($subasta)
	{
		$puedePujar = self::allowBidCredit($subasta->cod, null, $subasta->licit, $subasta->imp);
		return $puedePujar;
	}

	private function adjudicationLimit($user, $lote, $is_gestor)
	{
		return !Config::get('app.use_credit')
			&& Config::get('app.disabled_ries_cli') == false
			&& in_array($lote->tipo_sub, ['W', 'O'])
			&& !$is_gestor
			&& count($user) > 0
			&& !empty($user[0]->max_adj)
			&& $user[0]->max_adj > 0;
	}

	/**
	 * Calcula el importe total de las adjudicaciones, más el importe de las órdenes y/o pujas
	 * y verifica si el importe total supera el límite de adjudicaciones del cliente
	 * @param array $user
	 * @param Subasta $subasta
	 * @param User $gestor
	 */
	public function validateAdjudications($user, $subasta)
	{
		$imp_adjudic = 0;
		if(Config::get('app.check_adjudications_for_ries_by_auction', false)) {
			//sumar importe de adjudicaciones de la subasta
			$imp_adjudic = (new User)->getSumAdjudicacionesSubasta($subasta->cod, $subasta->licit);
		}
		else {
			//sumar importe de adjudicaciones de la sesión
			$id_auc_sessions = $subasta->getIdAucSessionslote($subasta->cod, $subasta->lote);
			$get_session = $subasta->get_session($id_auc_sessions);
			$adjudic = (new User)->getAllAdjudicacionesSession($subasta->cod, $get_session->reference, $subasta->licit);
			$imp_adjudic = array_sum(array_column($adjudic, 'himp_csub'));
		}

		$total_imp_adju = $imp_adjudic + $subasta->imp;

		// Calcular importe de órdenes
		$importeOrdenes = $this->calculateBidsOrOrderAmount($subasta, $user);

		// Verificar límite de adjudicaciones
		if (($total_imp_adju + $importeOrdenes) > $user[0]->max_adj) {
			return false;
		}

		return true;
	}

	private function calculateBidsOrOrderAmount($subasta, $user)
	{
		if (Config::get('app.max_orders_ries_cli', false)) {
			return FgOrlic::getTotalOrdersInAuction($subasta->cod, $subasta->ref, $user[0]->cli_licit, true);
		}

		if (Config::get('app.max_orders_and_bids_ries_cli', false)) {
			return FgPujaMax::sumBidderAmountForAuction($subasta, true);
		}

		return 0;
	}

	

	public static function auctionsToViews()
	{
		if(static::$allAuctions){
			return static::$allAuctions;
		}

		$subastasQuery = FgSub::query()
			->when(Config::get('app.lang_sub_in_global', false), function($query) {
				$query->joinLangSub();
			})
			->joinSessionSub()
			->addSelect('subc_sub')
			->where('subc_sub', '!=', 'N');

		if (!Session::get('user.admin')) {
			$subastasQuery->where('subc_sub', '!=', 'A');
		}

		/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
		if(Config::get("app.restrictVisibility")){
			$subastasQuery = $subastasQuery->Visibilidadsubastas(Session::get('user.cod'));
		}

		if(Config::get('app.agrsub', null)) {
			$subastasQuery->where('agrsub_sub', Config::get('app.agrsub'));
		}

		$subastasQuery = $subastasQuery->orderBy('session_start', 'asc')->get();

		$subastas = $subastasQuery
			->groupBy([
				//Si se es admin, las subastas subc_sub A las unificamos con las S
				function ($item, $key) {
					if (Session::has('user') && Session::get('user.admin') && $item['subc_sub'] == 'A') {
						return 'S';
					} else {
						return $item['subc_sub'];
					}
				},
				'tipo_sub', 'cod_sub'
			], $preserveKeys = false);

		static::$allAuctions = $subastas;
		return $subastas;
	}
}
