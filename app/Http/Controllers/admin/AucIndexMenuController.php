<?php

namespace App\Http\Controllers\admin;
use Controller;
use Config;

use App\Models\AucIndexMenu;

class AucIndexMenuController extends Controller
{

        public $emp ;
        public $content ;

        public function __construct()
        {
            $this->emp = Config::get('app.emp');
            $this->content = new AucIndexMenu();
        }


    //Mostramos Menu Orden
    public function index()
    {

        $data['Parent'] = $this->content->getAucMenu();
        $data['FamiliaSession'] = $this->content->getAucMenuNotParent();
        return \View::make('admin::pages.aucIndexMenu',array('data' => $data));
    }

     public function save(){
         $i = 1;
         /*Recibimos Orden del Menu i vamos guardando*/
         //Un padre (P) se pone como hijo error,
         //Un hijo (S,F) se pone como padre error
         //Dentro de un padre hay otro padre que sea (S,P,F) error
         foreach ($_POST as $key) {;
            foreach($key as $value ){
                 $id_parent = $value['id'];
                 $this->content->saveMenuOrder($id_parent,$i);
                 $type=$this->content->getMenuParent($id_parent);
                 if($type->type == 'P'){
                    if(!empty($value['children'])){
                        foreach($value['children'] as $children ){
                            $type=$this->content->getMenuParent($children['id']);
                            if($type->type == "P"){
                               return '1' ;
                            }else{
                                if(empty($children['children'])){
                                    $i++;
                                    $id_children = $children['id'];
                                    $this->content->saveMenuParent($id_parent,$id_children,$i);
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
