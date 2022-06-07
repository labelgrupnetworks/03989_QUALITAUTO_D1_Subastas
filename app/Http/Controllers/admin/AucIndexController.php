<?php

namespace App\Http\Controllers\admin;
use Request;
use Controller;
use Config;

use App\Models\AucIndex;

class AucIndexController extends Controller
{

        public $emp ;
        public $lang ;
        public $language ;

        public function __construct()
        {
            $this->emp = Config::get('app.emp');
            $this->lang = Config::get('app.locales');
            $this->language = Config::get('app.locale');
        }


    //Ver todos los Aux Index que hay
    public function index()
    {
        $content = new AucIndex();
        $data['Secciones'] = $content->getAucTable();
        $data['Parent'] = $content->getAucMenu();
        $data['FamiliaSession'] = $content->getAucMenuNotParent();
        return \View::make('admin::pages.aucIndex',array('data' => $data));
    }

    public function SeeAuxIndex($id = NULL){
        $content = new AucIndex();
        $type_p='P';
        $data['inf'] = $content->getInfAux($id);
        $data['lang'] = $content->getInfAuxLang($id);
        $data['fxsec'] = $content->getAucFXSEC();
        $data['padre'] = $content->getInfPadre($type_p);
        $data['idiomes'] = $this->lang;

        $data['LotsSession'] = $content->getAucSession();
        $data['Sessions']=  $content->getAucIndexSession();
        $data['Lots']=  $content->getAucIndexLots($id);
        $data['Menu'] = $content->getMenu($id);

        return \View::make('admin::pages.editAucIndex',array('data' => $data));
    }

    public function EditAuIndex(){

        $sections='';
        $content = new AucIndex();

        $id=Request::input('id');
        $title=Request::input('title');
        $type=Request::input('type');
        $enabled_temp=Request::input('enabled');
        $parent=Request::input('parent');

        if(empty($enabled_temp)){
            $enabled=0;
        }else{
            $enabled=1;
        }
        $coma='';
        //Secciones las guardamos en comas y el ultimo borramos la coma de mas
        if(!empty(Request::input('sections'))){
            $sections_temp=Request::input('sections');
            foreach ($sections_temp as $value){
                //Mirmaos si queire todas las categorias
                if($value == 'ALL'){
                   $sections=$value;
                    break;
                }
                $sections.=$coma.$value;
                $coma=',';
            }
        }

        $file_url=null;
        if($type=='P' || empty($parent)){
            $parent=0;
            $sections=null;
        }elseif($type=='S'){
             $id_auc_session=Request::input('id_auc_session');
             $file_url=Request::input('file_url');
        }

        //Comprueva si existe
       if($id==0){
            $max_id = $content->Max_Auc_Index();
            $max_id ++;
            $orden_temp = $content->Max_Auc_Orden($parent);
            if(!empty($orden_temp)){
                $orden = $orden_temp->orden;
                $orden++;
            }else{
                $orden = 1;
            }
            //Nuevo auc-index
            $id = $content->insert_auc_index($max_id,$this->emp,$type,$parent,$enabled,$title,$orden,$sections,$file_url);
            //Bucle de los idiomas, hacemos un insert de las traducciones
        }else{
            $content->update_auc_index($id,$this->emp,$type,$parent,$enabled,$title,$sections,$file_url);
        }
        //Max id de idiomas
        $id_lang=$content->MaxIdAucIndexLang();
        $content->deleteAucIndexLang($id);
        foreach($this->lang as $lang => $idiom){
            $lang = strtoupper($lang);
            $title_lang=Request::input('title_'.$lang);
            $subtitle_lang=Request::input('subtitle_'.$lang);
            $key_lang=Request::input('key_'.$lang);

            $id_lang++;

            $content->InsertAucIndexLang($id_lang,$id,$lang,$key_lang,$title_lang,$subtitle_lang);
         }

         //Eliminamos todos los Lotes de WEB_AUC_INDEX_LOTS
         //Si no es S que no lo haga
         if($type=='S'){
            $content->DeleteLots($id);
            $max_lots = $content->MaxIdLots();
            $num_sessions = Request::input('num_sessions');
            for ($x=0; $x<$num_sessions; $x++){

                $id_auc_session=Request::input('id_auc_session_'.$x);
                $lots=Request::input('lots_'.$x);
                if(!empty($id_auc_session) && !empty($lots)){
                    $max_lots++;
                    $content->InsertLots($max_lots,$id,$id_auc_session,$lots);
                }else{
                    $num_sessions++;
                }
            }
         }

        return $id;
    }

    //Mostramos Menu Orden

     public function save(){
         $i = 1;
         /*Recibimos Orden del Menu i vamos guardando*/
         //Un padre (P) se pone como hijo error,
         //Un hijo (S,F) se pone como padre error
         //Dentro de un padre hay otro padre que sea (S,P,F) error
         foreach ($_POST as $key) {
            foreach($key as $value ){
                 $id_parent = $value['id'];
                 $content->saveMenuOrder($id_parent,$i);
                 $type=$content->getMenuParent($id_parent);
                 if($type->type == 'P'){
                    if(!empty($value['children'])){
                        foreach($value['children'] as $children ){
                            $type=$content->getMenuParent($children['id']);
                            if($type->type == "P"){
                               return '1' ;
                            }else{
                                if(empty($children['children'])){
                                    $i++;
                                    $id_children = $children['id'];
                                    $content->saveMenuParent($id_parent,$id_children,$i);
                                }else{
                                    return '3' ;
                                }
                            }
                        }
                    }
                 }else{
                     return '2' ;
                 }
            $i++;
            }
         }
         return '0' ;
     }

}
