<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use View;
use Session;
use Redirect;
use Input;
use File;
use Config;

use App\Models\SeoCateg;
use App\Models\AucIndex;

class SeoCategoriesController extends Controller
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
        $this->content = new SeoCateg();
        $this->model_aucindex = new AucIndex();
    }

    //Mostramos Categories
    public function index()
    {
        $data = $this->model_aucindex->getAucFXSEC($this->gemp);
        return \View::make('admin::pages.seo_categories', array('data' => $data));
    }

    //Mostramos informacion de la informacion que tendra esta categoria en la web
    public function InfCategSeo($cod_sec){
        $data['lang'] = $this->lang;
        $data['idiomes'] = $this->language;
        $data['content']=$this->content->infCategSeo($cod_sec,$this->gemp);
        $data['cod_sec'] = $this->content->NameCodSeo($cod_sec,$this->gemp);

        return \View::make('admin::pages.editseo_categories', array('data' => $data));
    }

  public function SavedCategSeo(){

      $content = $this->content;
      foreach($this->language as $lang => $idiom){
            $lang = strtoupper($lang);
            $content->id = Request::input('id_'.$lang);
            $content->codsec = Request::input('codsec');
            $content->gemp = $this->gemp;
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
