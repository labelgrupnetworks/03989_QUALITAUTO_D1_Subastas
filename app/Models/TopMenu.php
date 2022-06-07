<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;
use Routing;
use App\Models\Content;

class TopMenu extends Model
{

    public static $page;
    public static $itemsPerPage;

    #Obtiene los datos del menú, se acaba de montar en la vista.
    public static function getMenu($type = 'ALL', $subc = 'S')
    {
        if($type == 'ALL'){
            $tipo_sub = "  sub.TIPO_SUB IN ('O', 'W', 'V') AND ";
        }elseif($type == 'W'){
            $tipo_sub = " sub.TIPO_SUB = 'W' AND  auc.\"end\" > sysdate AND";
        }elseif($type == 'V'){
            $tipo_sub = " sub.TIPO_SUB = 'V' AND ";
        }elseif($type == 'O'){
            $tipo_sub = " sub.TIPO_SUB = 'O' AND ";
        }        
        
        
        if($subc == 'H'){
            $subc_sub =" sub.SUBC_SUB = 'H' AND   ";
        }elseif($subc == 'S'){
            $subc_sub =" sub.SUBC_SUB = 'S' AND ";
        }elseif($subc == 'A'){
            $subc_sub =" sub.SUBC_SUB = 'A' AND ";
        }

        $subastas = DB::select("
            SELECT cod_sub, des_sub,  orders_start, orders_end, tipo_sub, name, id_auc_sessions, session_start, session_end,
            CONCAT (LISTAGG(CONCAT('|#', lin), '#|') WITHIN GROUP (ORDER BY des_cat), '#|') \"id_categories\", 
            CONCAT (LISTAGG(CONCAT('|#', des_cat), '#|') WITHIN GROUP (ORDER BY des_cat),'#|') \"categories\"
            FROM (
                SELECT sub.COD_SUB cod_sub, sub.DES_SUB des_sub, sec0.DES_ORTSEC0 des_cat, sec0.LIN_ORTSEC0 lin, sub.tipo_sub,
                        
                       auc.\"name\" name, auc.\"id_auc_sessions\" id_auc_sessions , auc.\"start\" session_start, auc.\"end\" session_end,
                       auc.\"orders_start\" as orders_start,  auc.\"orders_end\" as orders_end
                FROM FGSUB sub 
                JOIN FGASIGL0 lotes ON (sub.COD_SUB = lotes.SUB_ASIGL0 AND lotes.EMP_ASIGL0 = :emp)
                JOIN \"auc_sessions\" auc ON (auc.\"auction\" = sub.cod_sub AND auc.\"company\" = :emp)
                LEFT JOIN FGORTSEC0 sec0 ON (sec0.SUB_ORTSEC0 = sub.COD_SUB AND sec0.EMP_ORTSEC0 = :emp)
                LEFT JOIN FGORTSEC1 sec1 ON (sec1.SUB_ORTSEC1 = sub.COD_SUB AND sec1.LIN_ORTSEC1 = sec0.LIN_ORTSEC0 AND sec1.EMP_ORTSEC1 = :emp)
                JOIN WEB_CONFIG wc ON (wc.EMP = :emp AND wc.KEY = 'enable_general_auctions' AND value = 1)
                WHERE                    
                    auc.\"start\" IS NOT NULL AND
                    auc.\"end\" IS NOT NULL AND
                    $subc_sub
                    $tipo_sub
                    sub.EMP_SUB = :emp
                GROUP BY sub.COD_SUB, sec0.LIN_ORTSEC0, sec0.DES_ORTSEC0, sec1.ORDEN_ORTSEC1, sub.DES_SUB,    auc.\"orders_start\" ,  auc.\"orders_end\" , sub.tipo_sub, auc.\"name\", auc.\"id_auc_sessions\", auc.\"start\" , auc.\"end\" 
                ORDER BY sec1.ORDEN_ORTSEC1
            ) GROUP BY cod_sub, des_sub, orders_start, orders_end, tipo_sub, name, id_auc_sessions, session_start, session_end
            ORDER BY cod_sub DESC, id_auc_sessions ASC
        ",  array(
                'emp'   =>  Config::get('app.emp')
            )
        );
        
        

        if (empty($subastas)){
            return array();//FALSE;
        }

        $menu = array();

        # Montamos el array con los nombres y urls friendly
        foreach ($subastas as $key => $value) {
            //$id_categories = self::getSeparatorValue($value->id_categories);
            //$categories = self::getSeparatorValue($value->categories);

            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['name'] = ucfirst(mb_strtolower($value->name, "UTF-8"));
            $m_encode = str_slug($value->name). '-'. $value->id_auc_sessions;
            
            
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['url_friendly'] = Routing::translateSeo('subasta').$value->cod_sub."-".$m_encode;
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['url_friendly_tiempo_real'] = Routing::translateSeo('api/subasta').$value->cod_sub."-".$m_encode;
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['session_start'] = date_create_from_format('Y-m-d H:i:s',$value->session_start);
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['session_end'] = date_create_from_format('Y-m-d H:i:s',$value->session_end);

            /*foreach ($id_categories as $k => $v) {

                if (empty($v) || empty($menu[$value->cod_sub]['categories'][$v])) {
                    continue;
                }

                $menu[$value->cod_sub]['categories'][$v]['name'] = ucfirst(mb_strtolower($categories[$k], "UTF-8"));
                $u_encode = str_slug($categories[$k]);
                $menu[$value->cod_sub]['categories'][$v]['url_friendly'] = Routing::slug('subasta')."-".$value->cod_sub.Routing::slug('category', true)."/".$v."-".$u_encode;
            }*/
        }

        return $menu;
    }
    
    
    
    
    
/* 2017_07_06 lo quito por que he hecho una nueva funcion en subasta auctionList para listar subastas en una página, si qe quiere hacer un menu se debería aprovechar la función  get menu
    public static function getMenuHistorico()
    {
        $sql = "
            SELECT * FROM ( 
            SELECT rownum rn, pu.* FROM (
            SELECT cod_sub, des_sub, dfecorlic, dhoraorlic, hfecorlic, hhoraorlic, tipo_sub, name,id_auc_sessions, session_start, session_end,
            CONCAT (REPLACE(dfecorlic, '00:00:00'), ' ' || dhoraorlic) inicio_ol,
            CONCAT (REPLACE(hfecorlic, '00:00:00'), ' ' || hhoraorlic) fin_ol,

            CONCAT (LISTAGG(CONCAT('|#', lin), '#|') WITHIN GROUP (ORDER BY des_cat), '#|') \"id_categories\", 
            CONCAT (LISTAGG(CONCAT('|#', des_cat), '#|') WITHIN GROUP (ORDER BY des_cat),'#|') \"categories\"
            FROM (
                SELECT sub.COD_SUB cod_sub, sub.DES_SUB des_sub, sec0.DES_ORTSEC0 des_cat, sec0.LIN_ORTSEC0 lin, 
                       sub.DFECORLIC_SUB dfecorlic, sub.DHORAORLIC_SUB dhoraorlic, sub.HFECORLIC_SUB hfecorlic, sub.HHORAORLIC_SUB hhoraorlic, sub.tipo_sub, auc.\"name\" name, auc.\"id_auc_sessions\" id_auc_sessions, auc.\"start\" session_start, auc.\"end\" session_end
                FROM FGSUB sub
                JOIN FGASIGL0 p ON (sub.COD_SUB = p.SUB_ASIGL0 AND p.EMP_ASIGL0 = :emp)
                JOIN FGHCES1 lotes
                      ON (lotes.SUB_HCES1 = p.SUB_ASIGL0 AND lotes.REF_HCES1 = p.REF_ASIGL0 AND lotes.EMP_HCES1 = :emp)
                JOIN \"auc_sessions\" auc ON (auc.\"auction\" = sub.cod_sub AND auc.\"company\" = :emp)
                LEFT JOIN FGORTSEC0 sec0 ON (sec0.SUB_ORTSEC0 = sub.COD_SUB AND sec0.EMP_ORTSEC0 = :emp)
                LEFT JOIN FGORTSEC1 sec1 ON (sec1.SUB_ORTSEC1 = sub.COD_SUB AND sec1.LIN_ORTSEC1 = sec0.LIN_ORTSEC0 AND sec1.EMP_ORTSEC1 = :emp)
                JOIN WEB_CONFIG wc ON (wc.EMP = :emp AND wc.KEY = 'enable_historic_auctions' AND value = 1)
                WHERE
                    sub.SUBC_SUB = 'H' AND
                    sub.EMP_SUB = :emp AND
                    lotes.REF_HCES1 >= sub.DREF_SUB

                    AND lotes.REF_HCES1 <= 
                    (CASE WHEN sub.HREF_SUB > 0
                        THEN sub.HREF_SUB
                        ELSE 99999999999 END)
                    
                GROUP BY sub.COD_SUB, sec0.LIN_ORTSEC0, sec0.DES_ORTSEC0, sec1.ORDEN_ORTSEC1, sub.DES_SUB, sub.DFECORLIC_SUB, sub.DHORAORLIC_SUB, sub.HFECORLIC_SUB, sub.HHORAORLIC_SUB, sub.tipo_sub, auc.\"name\", auc.\"id_auc_sessions\" , auc.\"start\" , auc.\"end\" 
                ORDER BY sec1.ORDEN_ORTSEC1
            ) GROUP BY cod_sub, des_sub, dfecorlic, dhoraorlic, hfecorlic, hhoraorlic, tipo_sub, name,id_auc_sessions, session_start, session_end
            ORDER BY cod_sub DESC, id_auc_sessions ASC) pu )".self::getOffset(self::$page, self::$itemsPerPage);

        $subastas = DB::select($sql,  array(
                'emp'   =>  Config::get('app.emp')
            )
        );

        if (empty($subastas)) {
            return FALSE;
        }

        $menu = array();

        # Montamos el array con los nombres y urls friendly
        foreach ($subastas as $key => $value) {
            $id_categories = self::getSeparatorValue($value->id_categories);
            $categories = self::getSeparatorValue($value->categories);
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['name'] = ucfirst(mb_strtolower($value->name, "UTF-8"));
            $m_encode = str_slug($value->name). '-'. $value->id_auc_sessions;
            
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['url_friendly'] = Routing::slug('subasta')."-".$value->cod_sub."-".$m_encode;
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['session_start'] = date_create_from_format('Y-m-d H:i:s',$value->session_start);
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['session_end'] = date_create_from_format('Y-m-d H:i:s',$value->session_end);

            if (!empty($id_categories)) 
            {
                foreach ($id_categories as $k => $v) 
                {

                    if(empty($v) || empty($menu[$value->id_auc_sessions]['categories'][$v]))
                        continue;

                    $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['categories'][$v]['name'] = ucfirst(mb_strtolower($categories[$k], "UTF-8"));
                    $u_encode = str_slug($categories[$k]);
                    $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['categories'][$v]['url_friendly'] = Routing::slug('subasta')."-".$value->cod_sub.Routing::slug('category', true)."/".$v."-".$u_encode;
                }
            }
        }

        return $menu;
    }
    */
   
    public static function getOffset($page, $itemsPerPage)
    {   
        $result = FALSE;
        if(empty($page) or $page == 1) {
            $start  = 1;
            $offset = 1;
        } else {
            $start  = $itemsPerPage;

            if($page > 2) {
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

    /*
    public static function getMenuTienda()
    {

        $subastas = DB::select("
            SELECT cod_sub, des_sub, dfecorlic, dhoraorlic, hfecorlic, hhoraorlic, tipo_sub, name, id_auc_sessions, session_start, session_end,
            CONCAT (REPLACE(dfecorlic, '00:00:00'), ' ' || dhoraorlic) inicio_ol,
            CONCAT (REPLACE(hfecorlic, '00:00:00'), ' ' || hhoraorlic) fin_ol,
            CONCAT (LISTAGG(CONCAT('|#', lin), '#|') WITHIN GROUP (ORDER BY des_cat), '#|') \"id_categories\", 
            CONCAT (LISTAGG(CONCAT('|#', des_cat), '#|') WITHIN GROUP (ORDER BY des_cat),'#|') \"categories\"
            FROM (
                SELECT sub.COD_SUB cod_sub, sub.DES_SUB des_sub, sec0.DES_ORTSEC0 des_cat, sec0.LIN_ORTSEC0 lin, 
                       sub.DFECORLIC_SUB dfecorlic, sub.DHORAORLIC_SUB dhoraorlic, sub.HFECORLIC_SUB hfecorlic, sub.HHORAORLIC_SUB hhoraorlic, sub.tipo_sub,
                       auc.\"name\" name , auc.\"id_auc_sessions\" id_auc_sessions, auc.\"start\" session_start, auc.\"end\" session_end
                FROM FGSUB sub 
                LEFT JOIN FGASIGL0 lotes ON (sub.COD_SUB = lotes.SUB_ASIGL0 AND lotes.EMP_ASIGL0 = :emp)
                JOIN \"auc_sessions\" auc ON (auc.\"auction\" = sub.cod_sub AND auc.\"company\" = :emp)
                LEFT JOIN FGORTSEC0 sec0 ON (sec0.SUB_ORTSEC0 = sub.COD_SUB AND sec0.EMP_ORTSEC0 = :emp)
                LEFT JOIN FGORTSEC1 sec1 ON (sec1.SUB_ORTSEC1 = sub.COD_SUB AND sec1.LIN_ORTSEC1 = sec0.LIN_ORTSEC0 AND sec1.EMP_ORTSEC1 = :emp)
                JOIN WEB_CONFIG wc ON (wc.EMP = :emp AND wc.KEY = 'enable_direct_sale_auctions' AND value = 1)
                WHERE
                    auc.\"start\" IS NOT NULL AND
                    auc.\"end\" IS NOT NULL AND
                    sub.SUBC_SUB = 'S' AND
                    sub.EMP_SUB = :emp AND
                   ( sub.TIPO_SUB = 'V' )
                GROUP BY sub.COD_SUB, sec0.LIN_ORTSEC0, sec0.DES_ORTSEC0, sec1.ORDEN_ORTSEC1, sub.DES_SUB, sub.DFECORLIC_SUB, sub.DHORAORLIC_SUB, sub.HFECORLIC_SUB, sub.HHORAORLIC_SUB, sub.tipo_sub, auc.\"name\", auc.\"id_auc_sessions\", auc.\"start\" , auc.\"end\" 
                ORDER BY sec1.ORDEN_ORTSEC1
            ) GROUP BY cod_sub, des_sub, dfecorlic, dhoraorlic, hfecorlic, hhoraorlic, tipo_sub, name, id_auc_sessions, session_start, session_end
            ORDER BY cod_sub DESC, id_auc_sessions ASC
        ",  array(
                'emp'   =>  Config::get('app.emp')
            )
        );

        if (empty($subastas)){
            return FALSE;
        }

        $menu = array();

        # Montamos el array con los nombres y urls friendly
        foreach ($subastas as $key => $value) {
            $id_categories = self::getSeparatorValue($value->id_categories);
            $categories = self::getSeparatorValue($value->categories);

            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['name'] = ucfirst(mb_strtolower($value->name, "UTF-8"));
            $m_encode = str_slug($value->name). '-'. $value->id_auc_sessions;

         
            
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['url_friendly'] = Routing::slug('subasta/vt')."-".$value->cod_sub."-".$m_encode;
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['session_start'] = date_create_from_format('Y-m-d H:i:s',$value->session_start);
            $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['session_end'] = date_create_from_format('Y-m-d H:i:s',$value->session_end);

            foreach ($id_categories as $k => $v) {

                if(empty($v))
                    continue;

                $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['categories'][$v]['name'] = ucfirst(mb_strtolower($categories[$k], "UTF-8"));
                $u_encode = str_slug($categories[$k]);
                $menu[$value->cod_sub."_|:sep:|_".$value->des_sub][$value->id_auc_sessions]['categories'][$v]['url_friendly'] = Routing::slug('subasta/vt')."-".$value->cod_sub.Routing::slug('category', true)."/".$v."-".$u_encode;
            }
        }

        return $menu;
    }
*/
    # Cargamos los botones de menu configurables
    public static function getContentMenu()
    {
        $Menu = new Content();
        $Menu->tipo     = 3; // Tipo menu
        $Menu->parent   = '0,null';
        //$Menu->lang     = Config::get(\App::getLocale());
        $Menu->lang     = strtoupper(\App::getLocale());

        return $Menu->getContent();
    }

    public static function getSeparatorValue($values){
        preg_match_all('%\|#([0-z]*)#\|%', $values, $matches);
        return end($matches);
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
    
    public static function getMenuAucIndex(){
        
        $emp = Config::get('app.emp');
        
        $data = DB::table('WEB_AUC_INDEX')
                ->where('WEB_AUC_INDEX.ID_EMP',$emp)
                ->orderBy('WEB_AUC_INDEX.ORDEN','asc')
                ->get();
       return $data;
    }
    
    public static function getMenuTradAucIndex(){
         $data = DB::table('WEB_AUC_INDEX_LANG')
                ->where('ID_LANG','ES')
                ->get();
       return $data;
    }
}