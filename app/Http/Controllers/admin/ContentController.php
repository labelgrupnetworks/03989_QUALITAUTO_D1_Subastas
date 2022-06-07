<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;

use App\Models\Page;
use App\Models\Content;
class ContentController extends Controller
{
    public function index()
    {

       $page = new Page();

       $data = $page->allPage($page->emp);

       return \View::make('admin::pages.page_content', array('data' => $data));
    }

    public function getPage($id){
        $page = new Page();

       $content = $page->getPage($page->emp,$id);

       return \View::make('admin::pages.editPage_content', array('content' => $content));
    }

    public function savedPage(){
         $page = new Page();
         $page->id=Request::input('id');
         $page->name=Request::input('name_web_page');
         $page->content=Request::input('html');
         $page->webmetat=Request::input('webmetat_web_page');
         $page->webmetad=Request::input('webmetad_web_page');
         $page->webnoindex=Request::input('webnoindex_web_page');
         if(empty($page->webnoindex)){
             $page->webnoindex = 0;
         }else{
             $page->webnoindex = 1;
         }
         $page->updatePage();

    }

    public function save(Request $request){

        #recojemos todos los valores
        $request_conf = Request::all();
        $content = new Content();

        $emp = $content->NumEmp();
        $max = $content->MaxConfWeb();
        $max = $max  + 1;
        $config = $content->WebConf($emp);

        foreach($config as  $obj){
            $web_config_array[] = $obj->key;
        }


        foreach($request_conf as $name_conf => $conf){
            #Comprovamos si la key existe en base de datos, si existe hace un update si no insert
            if(in_array($name_conf,$web_config_array)){
                $content->configWebUpdate($name_conf,$conf,$web_config_array,$max,$emp);
            }else{
                $description = trans('admin-app.config.'.$name_conf.'_desc');
                $content->configWebInsert($name_conf,$conf,$web_config_array,$max,$emp,$description);
                $max = $max  + 1;
            }
        }

    }

}
