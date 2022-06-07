<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;
/**
 * Description of AucIndex
 *
 * @author LABEL-RSANCHEZ
 */
class AucIndex {
    //put your code here
    public $emp ;

    public function __construct()
    {
        $this->emp = Config::get('app.emp');
    }

    public function getAucIndexByKeyname ($keyname, $id_lang){
        $res = DB::table('WEB_AUC_INDEX')
                    ->join('WEB_AUC_INDEX_LANG','WEB_AUC_INDEX_LANG.ID_WEB_AUC_INDEX','=','WEB_AUC_INDEX.ID_WEB_AUC_INDEX')
                    ->where('WEB_AUC_INDEX_LANG.KEY_NAME',$keyname)
                    ->where('WEB_AUC_INDEX.ENABLED',1)
                    ->where('WEB_AUC_INDEX_LANG.ID_LANG',strtoupper($id_lang))
                    ->where('WEB_AUC_INDEX.ID_EMP',$this->emp)
                    ->first();

         if(!empty($res) && $res->type == "F"){

            $res->sections = "'".str_replace(",","','", $res->sections)."'";
            $res->lots = array();
        }elseif(!empty($res) && $res->type == "S"){
            $res->sections ="";
            $sessions = DB::table('WEB_AUC_INDEX_LOTS')
                        ->where('WEB_AUC_INDEX_LOTS.ID_WEB_AUC_INDEX', $res->id_web_auc_index)
                        ->get();

            if(!empty($sessions)){
                foreach ($sessions as $session){
                    //si no hay lotes pondremos null
                    if (empty($session->lots)){
                        $res->lots[$session->id_auc_session] = null;
                    }else{
                        $res->lots[$session->id_auc_session] =  "'".str_replace(",","','", $session->lots)."'"; ;
                    }
                }
            }else{
                return NULL;
            }
        }


            return $res;
    }

    public function getAucTable(){
        $data = DB::table('WEB_AUC_INDEX')
                ->select('id_web_auc_index','title','type','enabled','orden','parent')
                ->where('ID_EMP',$this->emp)
                ->orderBy('orden','asc')
                ->get();
        return $data;
    }

    /*public function getAucMenu(){
        $data = DB::table('WEB_AUC_INDEX')
                ->where('ID_EMP',$this->emp)
                ->where('TYPE','P')
                ->orderBy('orden','asc')
                ->get();
        return $data;
    }*/

    public function getInfPadre($type){
        $data = DB::table('WEB_AUC_INDEX')
        ->where('ID_EMP',$this->emp)
        ->where('TYPE',$type)
        ->get();

        return $data;
    }

     public function getInfAux($id){
        $data = DB::table('WEB_AUC_INDEX')
        ->where('ID_EMP',$this->emp)
        ->where('id_web_auc_index',$id)
        ->first();

        return $data;
    }

    public function getInfAuxLang($id){
        $data = DB::table('WEB_AUC_INDEX_LANG')
        ->where('ID_WEB_AUC_INDEX',$id)
        ->get();

        return $data;
    }

    public function getAucFXSEC(){
        $data = DB::table('FXSEC')
        ->select("FXSEC.*")
        ->JOIN('FXTSEC', function ($join){
                $join->on('FXTSEC.GEMP_TSEC', '=', 'FXSEC.GEMP_SEC')
                        ->on('FXTSEC.COD_TSEC','=', 'FXSEC.TSEC_SEC');

            })
            ->WHERE('WEB_TSEC', '=', 'S')
        ->orderBy('DES_SEC','asc')
        ->get();

        return $data;
    }

    public function Max_Auc_Index(){
        $max_id = DB::table('WEB_AUC_INDEX')->max('ID_WEB_AUC_INDEX');
        return($max_id);
    }

	#03-02-21 Creo que ya no se usa, si se está usando,  arreglar codigo para evitar injection sql y descomentar
	/*
    public function insert_auc_index($max_id,$id_emp,$type,$parent,$enabled,$title,$orden,$sections,$file_url){
        DB::select("INSERT INTO WEB_AUC_INDEX (ID_WEB_AUC_INDEX,ID_EMP,TYPE,PARENT,ENABLED,TITLE,ORDEN,SECTIONS,URL_RESOURCE) "
                    . "VALUES (".$max_id.",'".$id_emp."','$type','".$parent."','$enabled','".$title."',".$orden.",'".$sections."','".$file_url."')");
            return $max_id;
    }
*/
    public function update_auc_index($id,$id_emp,$type,$parent,$enabled,$title,$sections,$file_url){

          DB::table('WEB_AUC_INDEX')
            ->where('ID_EMP',$id_emp)
            ->where('ID_WEB_AUC_INDEX',$id)
            ->update(['TYPE' => $type,'PARENT' => $parent,'ENABLED' => $enabled,'TITLE' => $title,'SECTIONS' => $sections,'URL_RESOURCE'=>$file_url,'ENABLED'=>$enabled]);

         return $id;
    }

    public function getAucSession(){

         $data = DB::table('"auc_sessions"')
         ->JOIN('FGSUB','"auc_sessions"."auction"', '=', 'FGSUB.COD_SUB')
         ->where('FGSUB.SUBC_SUB','=','S')
         ->where('FGSUB.emp_SUB',$this->emp)
         ->where('"company"',$this->emp)
         ->where(function ($query) {
            $query->orWhere('FGSUB.TIPO_SUB','=','P')
                  ->orWhere('FGSUB.TIPO_SUB','=','O');
        })
         ->orderBy('"name"','asc')
         ->get();

         return($data);
    }

    public function getAucIndexSession(){
        $data = DB::table('"WEB_AUC_INDEX"')
         ->orderBy('"TITLE"','asc')
         ->where('WEB_AUC_INDEX.TYPE','S')
         ->get();
         return($data);
    }

    public function getAucIndexLots($id){
        $data = DB::table('"WEB_AUC_INDEX_LOTS"')
         ->where('ID_WEB_AUC_INDEX',$id)
         ->get();
         return($data);
    }

    public function Max_Auc_Orden($parent){
        $data = DB::table('WEB_AUC_INDEX')
         ->select('orden')
         ->where('PARENT',$parent)
         ->orderBy('ORDEN','desc')
         ->first();
        return($data);
    }

     public function InsertAucIndexLang($id_lang,$id,$lang,$key_lang,$title_lang,$subtitle_lang){
        DB::table('WEB_AUC_INDEX_LANG')->insert([
            ['id_web_auc_index_lang' => $id_lang, 'id_web_auc_index' => $id,'id_lang'=>$lang, 'key_name' => $key_lang, 'title' => $title_lang, 'subtitle' => $subtitle_lang]
        ]);
    }

    public function MaxIdAucIndexLang(){
        $max_id =DB::table('WEB_AUC_INDEX_LANG')->max('id_web_auc_index_lang');
                return $max_id;
    }

     public function deleteAucIndexLang($id){
        DB::table('WEB_AUC_INDEX_LANG')
                    ->where('ID_WEB_AUC_INDEX',$id)
                    ->delete();
    }

    public function DeleteLots($id){
        DB::table('WEB_AUC_INDEX_LOTS')
            ->where('ID_WEB_AUC_INDEX',$id)
            ->delete();
    }

    public function MaxIdLots(){
        $max_id =DB::table('WEB_AUC_INDEX_LOTS')->max('ID_WEB_AUC_INDEX_LOTS');
                return $max_id;
    }

    public function InsertLots($max_lots,$id,$id_auc_session,$lots){
        DB::table('WEB_AUC_INDEX_LOTS')->insert([
            ['id_web_auc_index_lots' => $max_lots, 'id_web_auc_index' => $id,'id_auc_session'=>$id_auc_session, 'lots' => $lots]
        ]);
    }

    public function getMenu($id){
        $data = DB::table('WEB_AUC_INDEX')
                ->select('id_web_auc_index','title')
                ->where('ID_EMP',$this->emp)
                ->where('PARENT',$id)
                ->orderBy('orden','asc')
                ->get();
        return $data;
    }


    //ORDENAR MENU

     public function getAucMenu(){
        $data = DB::table('WEB_AUC_INDEX')
                ->where('ID_EMP',$this->emp)
                ->where('TYPE','P')
                ->orderBy('orden','asc')
                ->get();
        return $data;
    }

    public function getAucMenuNotParent(){
        $data = DB::table('WEB_AUC_INDEX')
                ->where('ID_EMP',$this->emp)
                ->where('TYPE','!=','P')
                ->orderBy('orden','asc')
                ->get();
        return $data;
    }

    public function saveMenuOrder($id_parent,$i){
            DB::table('WEB_AUC_INDEX')
            ->where('ID_WEB_AUC_INDEX',$id_parent)
            ->update(['ORDEN' => $i]);

    }

    public function saveMenuParent($id_parent,$id_children,$i){
            DB::table('WEB_AUC_INDEX')
            ->where('ID_WEB_AUC_INDEX',$id_children)
            ->update(['ORDEN' => $i,'PARENT' => $id_parent]);

    }

    public function getMenuParent($id){
         $data = DB::table('WEB_AUC_INDEX')
            ->select('TYPE')
            ->where('ID_WEB_AUC_INDEX',$id)
            ->first();
         return $data;
    }

    public function getMenuWeb($type){
        $data = DB::table('WEB_AUC_INDEX_LANG')
            ->select('WEB_AUC_INDEX_LANG.*')
            ->join('WEB_AUC_INDEX','WEB_AUC_INDEX.ID_WEB_AUC_INDEX','=','WEB_AUC_INDEX_LANG.ID_WEB_AUC_INDEX')
            ->where('WEB_AUC_INDEX.ID_EMP',$this->emp)
            ->where('KEY_NAME',$type)
            ->first();

         return $data;
    }

    public function getMenuWebHijo($id_web_auc_index_lang){
        $sql= 'Select WEB_AUC_INDEX_LANG.title, WEB_AUC_INDEX_LANG.subtitle, WEB_AUC_INDEX.url_resource, WEB_AUC_INDEX_LANG.key_name, WEB_AUC_INDEX_LANG.id_lang, WEB_AUC_INDEX.sections '
                . 'from WEB_AUC_INDEX_LANG '
                . 'inner join WEB_AUC_INDEX on WEB_AUC_INDEX_LANG.ID_WEB_AUC_INDEX = WEB_AUC_INDEX.ID_WEB_AUC_INDEX '
                . 'inner join WEB_AUC_INDEX_LANG PADRE on PADRE.ID_LANG = WEB_AUC_INDEX_LANG.ID_LANG '
                . 'where WEB_AUC_INDEX.PARENT = PADRE.ID_WEB_AUC_INDEX '
                . 'and PADRE.ID_WEB_AUC_INDEX_LANG = :id_web_auc_index_lang '
                . 'and WEB_AUC_INDEX.id_emp = :emp '
                . 'and WEB_AUC_INDEX.ENABLED = 1 order by WEB_AUC_INDEX.orden asc';


        $data = DB::Select($sql, array("id_web_auc_index_lang" => $id_web_auc_index_lang, "emp" =>$this->emp ));


         return $data;


    }

    public function getKeyBySec($sec, $id_lang){
        $res = DB::table('WEB_AUC_INDEX')
                ->select("WEB_AUC_INDEX_LANG.KEY_NAME","WEB_AUC_INDEX_LANG.TITLE")
                ->join('WEB_AUC_INDEX_LANG','WEB_AUC_INDEX_LANG.ID_WEB_AUC_INDEX','=','WEB_AUC_INDEX.ID_WEB_AUC_INDEX')
                ->where("SECTIONS","like","%$sec%")
                ->where('WEB_AUC_INDEX.ENABLED',1)
                ->where('WEB_AUC_INDEX_LANG.ID_LANG',strtoupper($id_lang))
                ->where('WEB_AUC_INDEX.ID_EMP',$this->emp)
                ->first();

            return $res;


    }


}
