<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use Config;

use App\Models\SeoFamiliasSessiones;

class SeoFamiliasSessionesController extends Controller
{
    public $emp ;
    public $gemp;
    public $content;

    public function __construct()
    {
        $this->emp = Config::get('app.emp');
        $this->gemp = Config::get('app.gemp');
        $this->language = Config::get('app.locales');
        $this->lang = Config::get('app.locale');
        $this->content = new SeoFamiliasSessiones();
    }

    //Mostramos Todas las sessiones y familias
    public function index()
    {
        $data = $this->content->getSeoFamilySession($this->emp);
        return \View::make('admin::pages.seo_family_sessions', array('data' => $data));
    }

    //Mostramos informacion de la informacion que tendra session y la familia
    public function SeeFamilySessionsSeo($id){
        $data['lang'] = $this->lang;
        $data['idiomes'] = $this->language;
        $data['content']=$this->content->SeeFamilySessionsSeo($id,$this->emp);
        $data['auc_index'] = $this->content->NameSessionsSeo($id,$this->emp);

        return \View::make('admin::pages.editseo_family_session', array('data' => $data));
    }

    #updateamos o hacemos un insert
  public function SavedFamilySessionsSeo(){

      $content = $this->content;
      //Vamos mirando por idioma los imputs
      foreach($this->language as $lang => $idiom){
            $lang = strtoupper($lang);
            $content->id = Request::input('id_'.$lang);
            $content->id_auc = Request::input('id_auc');
            $content->emp = $this->emp;
            $content->lang = $lang;
            $content->webname=Request::input('webname_'.$lang);
            $content->webfriend=Request::input('webfriend_'.$lang);
            $content->webmetad=Request::input('webmetad_'.$lang);
            $content->webmetat=Request::input('webmetat_'.$lang);
            $content->webcont=Request::input('webcont_'.$lang);

            if(!empty($content->id)){
                $this->content->updateSeo($content);
            }else{
                $id_temp = $this->content->maxSeo();
                $content->id = $id_temp+1;
                $this->content->insertSeo($content);

            }
             $id[''.strtoupper($lang).''] = $content->id;


         }

         return($id);
  }

}
