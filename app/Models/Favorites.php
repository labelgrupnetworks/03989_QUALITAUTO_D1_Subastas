<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;
use Exception;
use Log;
use App\Models\Subasta;
use App\Models\V5\Web_Favorites;
use Session;

class Favorites extends Model
{
    private $cod_sub;
    private $cod_licit;
    private $ref;
    public  $list_licit;

    # Opciones para paginador
    public $page;
    public $itemsPerPage;

    public function __construct($cod_sub, $cod_licit)
    {
        $this->cod_sub    = $cod_sub;
        $this->cod_licit  = $cod_licit;
    }

    public function set($cod_sub = FALSE, $cod_licit = FALSE){

        if (!empty($cod_sub)){
            $this->cod_sub  = $cod_sub;
        }

        if (!empty($cod_licit)){
            $this->cod_licit  = $cod_licit;
        }
    }

    public function getFav($ref)
    {
        $this->ref = $ref;
        return $this->getFavs(TRUE);
    }

    public function getFavs($by_ref = FALSE){
        try {

            $where = '';

            $bindings = array(
                'cod_sub'   => strtoupper($this->cod_sub),
                'emp' => Config::get('app.emp'),
                'cli_licit' => $this->cod_licit
            );

            if ($by_ref){
                $bindings['ref'] = $this->ref;
                $where = "AND ID_REF = :ref";
            }

            $sql = "SELECT * FROM WEB_FAVORITES WHERE upper(ID_SUB) = :cod_sub AND ID_EMP = :emp AND ID_LICIT = :cli_licit ".$where." ORDER BY FECHA DESC";

            $res = DB::select($sql, $bindings);

            if (empty($res)){

               $result = array(
                        'status'    => 'error',
                    );

               return $result;
            }

            $lfavoritos = "";
            foreach ($res as $key => $value) {
                if ($key > 0){
                    $lfavoritos .= ", ";
                }
                $lfavoritos .= $value->id_ref;
            }
            //2018_05_03 modificado antes lotes.*, ahora solo algunso campos
            $sql = "SELECT rownum rn, p.*, subastas.*, lotes.ref_hces1, lotes.orden_hces1, lotes.titulo_hces1,lotes.desc_hces1,lotes.fac_hces1,lotes.lic_hces1,lin_hces1,num_hces1,
                                    auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name FROM FGASIGL0 p
                                              INNER JOIN FGHCES1 lotes
                                              ON (lotes.EMP_HCES1 = :emp AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0  AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0)

                                              INNER JOIN FGSUB subastas ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
                                              JOIN \"auc_sessions\" auc ON (auc.\"auction\" = subastas.COD_SUB AND auc.\"company\" = :emp)
                                                WHERE p.EMP_ASIGL0      = :emp
                                                AND subastas.COD_SUB    = :cod_sub
                                                AND p.REF_ASIGL0 IN ($lfavoritos)
                                                AND p.REF_ASIGL0 >= auc.\"init_lot\"
                                                AND  p.REF_ASIGL0 <= auc.\"end_lot\"";


            $bindings = array(
                'emp'       => Config::get('app.emp'),
                'cod_sub'   => $this->cod_sub
            );

            $res = DB::select($sql, $bindings);

            $subasta = new Subasta();
            $subasta->cod = $this->cod_sub;
            $fav_res = $subasta->getAllLotesInfo($res, true);

            $result = array(
                        'status' => 'success',
                        'data'   => $fav_res
                    );


        } catch (Exception $e) {

            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                    );
        }

        return $result;
    }

    public function setFav($ref)
    {

        $already_exits = $this->getFav($ref);

        if (!empty($already_exits['data'])){
            $result = array(
                'status' => 'error',
                'msg'    => 'already_added_to_fav'
            );

            return $result;
        }

         $test =array(
                        'cod_sub'   => ($this->cod_sub),
                        'emp'       => Config::get('app.emp'),
                        'cli_licit' => $this->cod_licit,
                        'ref'       => $this->ref
                        );

        try  {
            $user_temp = Session::get('user.cod');

            $res = DB::select("INSERT INTO WEB_FAVORITES (ID_LICIT, ID_SUB, ID_EMP, ID_REF, FECHA, COD_CLI) VALUES (:cli_licit, :cod_sub, :emp, :ref, to_char(sysdate, 'yyyy/mm/dd hh24:mi:ss'), :user_cod)",
                    array(
                        'cod_sub'   => ($this->cod_sub),
                        'emp'       => Config::get('app.emp'),
                        'cli_licit' => $this->cod_licit,
                        'ref'       => $this->ref,
                        'user_cod'  => Session::get('user.cod')
                        )
                );


            $result = array(
                    'status'    => 'success',
                    'msg'       => 'fav_added'
                );

            $data =  $this->getFav($ref);

            if (!empty($data['data'])){
                $result['data'] = $data['data'][0];
            }

        } catch (Exception $e) {

            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                        );
        }

        return $result;
    }


    # Borramos un mensaje y todos sus idiomas
    public function removeFav($ref)
    {
        $already_exits = $this->getFav($ref);
        if (empty($already_exits['data'])){
             $result = array(
                'status' => 'error',
                'msg'       => 'delete_fav_error'
            );

            return $result;
        }

        try {

            $bindings = array(
                        'cod_sub'   => strtoupper($this->cod_sub),
                        'emp'       => Config::get('app.emp'),
                        'cli_licit' => $this->cod_licit,
                        'ref'       => $this->ref
                        );

            $sql = "DELETE FROM WEB_FAVORITES WHERE upper(ID_SUB) = :cod_sub AND ID_EMP = :emp AND ID_LICIT = :cli_licit AND ID_REF = :ref ";
            $res = DB::select($sql, $bindings);

            $result = array(
                        'status'    => 'success',
                        'msg'       => 'deleted_fav_success'
                        );

        } catch (Exception $e) {
            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                        'msg'       => 'delete_fav_error'
                        );
        }

        return $result;
    }

    # Cogemos los favoritos de un usuario con todos los codigos de licitador
    public function getFavsByLicits($by_ref = FALSE)
    {
        try {
            $res = array();
            $where = '';

            $bindings = array(
                'emp' => Config::get('app.emp'),
            );

            if ($by_ref){
                $bindings['ref'] = $this->ref;
                $where = "AND ID_REF = :ref";
            }

            //$sql = "SELECT * FROM WEB_FAVORITES WHERE ID_EMP = :emp AND ID_LICIT IN (".$this->list_licit.") ".$where." ORDER BY FECHA DESC";
            if(!empty($this->list_licit)){
                $sql = "SELECT * FROM WEB_FAVORITES FAVORITES
                        JOIN FGSUB SUB ON SUB.COD_SUB = FAVORITES.ID_SUB AND SUB.EMP_SUB = FAVORITES.ID_EMP
                        JOIN FGASIGL0 ASIGL0 ON ASIGL0.SUB_ASIGL0 = FAVORITES.id_sub AND ASIGL0.REF_ASIGL0 = FAVORITES.id_ref AND ASIGL0.EMP_ASIGL0 = FAVORITES.id_emp
                        WHERE ASIGL0.CERRADO_ASIGL0 = 'N' AND FAVORITES.ID_EMP = :emp AND SUB.SUBC_SUB IN ('S','A') AND FAVORITES.ID_SUB || '-' || FAVORITES.ID_LICIT IN (".$this->list_licit.") ".$where." ORDER BY FAVORITES.ID_SUB,FAVORITES.ID_REF";

                $res = DB::select($sql, $bindings);
            }

            if (empty($res)){

               $result = array(
                        'status'    => 'error',
                        'data'=>null
                    );

               return $result;
            }

            $lfavoritos = "";
            foreach ($res as $key => $value) {
                if ($key > 0){
                    $lfavoritos .= ", ";
                }
                $lfavoritos .= "'".$value->id_ref."-".$value->id_sub."'";
                //$codigos_licitador .= $coma . "'". $key->sub_licit."-".$key->cod_licit."'";
            }

            $sql = "
            SELECT * FROM (
                SELECT rownum rn, p.*, subastas.*,EMP_HCES1,NUM_HCES1,LIN_HCES1,SUB_HCES1,SEC_HCES1,REF_HCES1,
NVL(lotes_lang.titulo_hces1_lang, lotes.titulo_hces1) titulo_hces1,
NVL(lotes_lang.desc_hces1_lang, lotes.desc_hces1) desc_hces1 ,
NVL(lotes_lang.DESCWEB_HCES1_LANG, lotes.DESCWEB_HCES1) DESCWEB_HCES1,
COMP_HCES1,COML_HCES1,LIN2_HCES1,LIC_HCES1,FAC_HCES1,COB_HCES1,IMPLIC_HCES1,
IMPSAL_HCES1,AFRAL_HCES1,NFRAL_HCES1,AFRAP_HCES1,NFRAP_HCES1,PROP_HCES1,FECDEV_HCES1,UBI_HCES1,
ALM_HCES1,PESO_HCES1,FECPENDEV_HCES1,OBS_HCES1,IMPTAS_HCES1,NOBJ_HCES1,FIRMA_HCES1,ALTO_HCES1,ANCHO_HCES1,GRUESO_HCES1,DERECHOS_HCES1,
ID_HCES1,IDORIGEN_HCES1,DESTACADO_HCES1,ORDEN_HCES1,TIPOOBJ_HCES1,DESCDET_HCES1,LOTEAPARTE_HCES1,IMGCATALOGO_HCES1,SITU_HCES1,PC_HCES1,IMPRES_HCES1,
SITUA_HCES1,IMGCAT_HCES1,IMPSALINI_HCES1,IMPRESINI_HCES1,IMPTASINI_HCES1,PCINI_HCES1,COMPINI_HCES1,COMLINI_HCES1,FECLIC_HCES1,IMPTASHINI_HCES1,IMPTASH_HCES1,
WEBMETAT_HCES1,WEBMETAD_HCES1,WEBFRIEND_HCES1,ANCHOUMED_HCES1,ALTOUMED_HCES1,PESOUMED_HCES1,GRUESOUMED_HCES1,DIAM_HCES1,DIAMUMET_HCES1,PESOVOL_HCES1,DESCCONTR_HCES1,
OBSDET_HCES1,TALLA_HCES1,TRANSPORT_HCES1,IMPNOVA_HCES1,
 auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name FROM FGASIGL0 p
                                                  INNER JOIN FGHCES1 lotes ON (lotes.SUB_HCES1 = p.SUB_ASIGL0 AND lotes.REF_HCES1 = p.REF_ASIGL0 AND lotes.EMP_HCES1 = :emp)
                                                  INNER JOIN FGSUB subastas ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
                                                  JOIN \"auc_sessions\" auc ON (auc.\"auction\" = subastas.COD_SUB AND auc.\"company\" = :emp)
                                                  LEFT JOIN FGHCES1_LANG lotes_lang
                                                  ON (lotes_lang.EMP_HCES1_LANG = :emp AND lotes_lang.NUM_HCES1_LANG = lotes.NUM_HCES1  AND lotes_lang.LIN_HCES1_LANG = lotes.LIN_HCES1 AND lotes_lang.LANG_HCES1_LANG = :lang)
                                                  WHERE p.EMP_ASIGL0     = :emp
                                                  AND lotes.REF_HCES1 || '-' || lotes.SUB_HCES1 IN ($lfavoritos)
                                                  AND lotes.REF_HCES1 >= auc.\"init_lot\"
                                                  AND lotes.REF_HCES1 <= auc.\"end_lot\"
                                                  AND p.cerrado_asigl0 = 'N'
                                                  ORDER BY p.ffin_asigl0 , p.hfin_asigl0 desc, p.ref_asigl0
                        ) pu " .\Tools::getOffset($this->page, $this->itemsPerPage). " ";

            $bindings = array(
                'emp'       => Config::get('app.emp'),
                'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))
            );

            $res = DB::select($sql, $bindings);

            $subasta = new Subasta();
            $subasta->cod = $this->cod_sub;
            $fav_res = $subasta->getAllLotesInfo($res);

            $result = array(
                        'status' => 'success',
                        'data'   => $fav_res
                    );

        } catch (Exception $e) {

            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                    );
        }
        return $result;
    }


    public function getFavsByCodCli($by_ref = FALSE)
    {
        try {
            $res = array();
            $where = '';

            $bindings = array(
                'emp' => Config::get('app.emp'),
            );

            if ($by_ref){
                $bindings['ref'] = $this->ref;
                $where = "AND ID_REF = :ref";
            }

            //$sql = "SELECT * FROM WEB_FAVORITES WHERE ID_EMP = :emp AND ID_LICIT IN (".$this->list_licit.") ".$where." ORDER BY FECHA DESC";
            if(!empty($this->list_licit)){
                $sql = "SELECT * FROM WEB_FAVORITES FAVORITES
                        JOIN FGSUB SUB ON SUB.COD_SUB = FAVORITES.ID_SUB AND SUB.EMP_SUB = FAVORITES.ID_EMP
                        WHERE FAVORITES.ID_EMP = :emp AND SUB.SUBC_SUB IN ('S','A') AND FAVORITES.ID_SUB || '-' || FAVORITES.ID_LICIT IN (".$this->list_licit.") ".$where." ORDER BY FAVORITES.ID_SUB,FAVORITES.ID_REF";


                $res = DB::select($sql, $bindings);
            }

            if (empty($res)){

               $result = array(
                        'status'    => 'error',
                        'data'=>null
                    );

               return $result;
            }

            $lfavoritos = "";
            foreach ($res as $key => $value) {
                if ($key > 0){
                    $lfavoritos .= ", ";
                }
                $lfavoritos .= "'".$value->id_ref."-".$value->id_sub."'";
                //$codigos_licitador .= $coma . "'". $key->sub_licit."-".$key->cod_licit."'";
            }

            $sql = "
            SELECT * FROM (
                SELECT rownum rn, p.*, subastas.*,EMP_HCES1,NUM_HCES1,LIN_HCES1,SUB_HCES1,SEC_HCES1,REF_HCES1,
NVL(lotes_lang.titulo_hces1_lang, lotes.titulo_hces1) titulo_hces1,
NVL(lotes_lang.desc_hces1_lang, lotes.desc_hces1) desc_hces1 ,
NVL(lotes_lang.DESCWEB_HCES1_LANG, lotes.DESCWEB_HCES1) DESCWEB_HCES1,
COMP_HCES1,COML_HCES1,LIN2_HCES1,LIC_HCES1,FAC_HCES1,COB_HCES1,IMPLIC_HCES1,
IMPSAL_HCES1,AFRAL_HCES1,NFRAL_HCES1,AFRAP_HCES1,NFRAP_HCES1,PROP_HCES1,FECDEV_HCES1,UBI_HCES1,
ALM_HCES1,PESO_HCES1,FECPENDEV_HCES1,OBS_HCES1,IMPTAS_HCES1,NOBJ_HCES1,FIRMA_HCES1,ALTO_HCES1,ANCHO_HCES1,GRUESO_HCES1,DERECHOS_HCES1,
ID_HCES1,IDORIGEN_HCES1,DESTACADO_HCES1,ORDEN_HCES1,TIPOOBJ_HCES1,DESCDET_HCES1,LOTEAPARTE_HCES1,IMGCATALOGO_HCES1,SITU_HCES1,PC_HCES1,IMPRES_HCES1,
SITUA_HCES1,IMGCAT_HCES1,IMPSALINI_HCES1,IMPRESINI_HCES1,IMPTASINI_HCES1,PCINI_HCES1,COMPINI_HCES1,COMLINI_HCES1,FECLIC_HCES1,IMPTASHINI_HCES1,IMPTASH_HCES1,
WEBMETAT_HCES1,WEBMETAD_HCES1,WEBFRIEND_HCES1,ANCHOUMED_HCES1,ALTOUMED_HCES1,PESOUMED_HCES1,GRUESOUMED_HCES1,DIAM_HCES1,DIAMUMET_HCES1,PESOVOL_HCES1,DESCCONTR_HCES1,
OBSDET_HCES1,TALLA_HCES1,TRANSPORT_HCES1,IMPNOVA_HCES1,
 auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name FROM FGASIGL0 p
                                                  INNER JOIN FGHCES1 lotes ON (lotes.SUB_HCES1 = p.SUB_ASIGL0 AND lotes.REF_HCES1 = p.REF_ASIGL0 AND lotes.EMP_HCES1 = :emp)
                                                  INNER JOIN FGSUB subastas ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)
                                                  JOIN \"auc_sessions\" auc ON (auc.\"auction\" = subastas.COD_SUB AND auc.\"company\" = :emp)
                                                  LEFT JOIN FGHCES1_LANG lotes_lang
                                                  ON (lotes_lang.EMP_HCES1_LANG = :emp AND lotes_lang.NUM_HCES1_LANG = lotes.NUM_HCES1  AND lotes_lang.LIN_HCES1_LANG = lotes.LIN_HCES1 AND lotes_lang.LANG_HCES1_LANG = :lang)
                                                  WHERE p.EMP_ASIGL0     = :emp
                                                  AND lotes.REF_HCES1 || '-' || lotes.SUB_HCES1 IN ($lfavoritos)
                                                  AND lotes.REF_HCES1 >= auc.\"init_lot\"
                                                  AND lotes.REF_HCES1 <= auc.\"end_lot\"

                        ) pu " .\Tools::getOffset($this->page, $this->itemsPerPage);

            $bindings = array(
                'emp'       => Config::get('app.emp'),
                'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))
            );

            $res = DB::select($sql, $bindings);

            $subasta = new Subasta();
            $subasta->cod = $this->cod_sub;
            $fav_res = $subasta->getAllLotesInfo($res);

            $result = array(
                        'status' => 'success',
                        'data'   => $fav_res
                    );

        } catch (Exception $e) {

            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                    );
        }
        return $result;
    }



    public function getFavsSub($cod_sub = null, $cod_cli){
          $where = '';

            $bindings = array(
                'emp' => Config::get('app.emp'),
                'cod_cli' => $cod_cli,

            );

           if (!empty($cod_sub)){
                $bindings['cod_sub'] = $cod_sub;
                $where = "AND ID_SUB = :cod_sub";
            }



            $sql = "SELECT FAVORITES.id_sub,FAVORITES.id_ref,FAVORITES.id_licit FROM WEB_FAVORITES FAVORITES
                    JOIN FGSUB SUB ON SUB.COD_SUB = FAVORITES.ID_SUB AND SUB.EMP_SUB = FAVORITES.ID_EMP
                    WHERE FAVORITES.ID_EMP = :emp AND SUB.SUBC_SUB IN ('S','A') AND FAVORITES.COD_CLI = :cod_cli $where";


            $value_favs = DB::select($sql, $bindings);
            $favs = array();

            foreach($value_favs as $val_favs){
                $favs['lot'][$val_favs->id_sub][$val_favs->id_ref] = true;
                if(empty($favs['licit'][$val_favs->id_sub])){
                    $favs['licit'][$val_favs->id_sub] = $val_favs->id_licit;
                }
            }
            return $favs;
    }

    //obtiene todos los clientes que tienen una subasta y referencia como favorita
    public static function getCliFavs($cod_sub, $id_ref){

        $bindings = array(
            'emp' => Config::get('app.emp'),
            'cod_sub' => $cod_sub,
            'id_ref' => $id_ref
            );

        $sql = "SELECT cod_cli
            FROM WEB_FAVORITES FAVORITES
            WHERE ID_EMP = :emp AND ID_SUB = :cod_sub AND ID_REF = :id_ref";

        $cli_favs = DB::select($sql, $bindings);
        return $cli_favs;
    }

	public function isFavorite($cod_sub, $ref)
	{

		if(!session('user.cod')){
			return false;
		}

		$isFavoirte = Web_Favorites::where([
			['id_sub', $cod_sub],
			['id_ref', $ref],
			['cod_cli', session('user.cod')]
		])->first();

		return (!$isFavoirte) ? false : true;
	}


}
