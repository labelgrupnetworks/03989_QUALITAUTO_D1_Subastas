<?php
namespace App\Http\Controllers;

use Redirect;

//opcional
use App;
use DB;
use Request;
use Validator;
use Input;
use Session;
use View;
use Routing;
use Config;
use Route;
use File;

# Cargamos el modelo
use App\Models\Content;
use App\Models\AucIndex;
use App\Models\Subasta;

class FormsController extends Controller
{   

    public $emp ;
    public $gemp;
    
    public function __construct()
    {
        $this->emp = Config::get('app.emp');
        $this->gemp = Config::get('app.gemp');
    } 

   public function index($cod_sub,$ref){
        $subasta = new Subasta();
        $name = trans(\Config::get('app.theme').'-app.foot.consult_lot') ;
        $subasta->cod = $cod_sub;
        $subasta->lote = $ref;
       
        $inf_lot = head($subasta->getLote());
        if(!empty($inf_lot)){
            $inf_lot->imagen = $subasta->getLoteImg($inf_lot);
        }else{
             exit (\View::make('front::errors.404'));
        }
        $data = array(
            'lot' => $inf_lot,
            'name' => $name
                );
        return \View::make('front::pages.consult_lot', array('data' => $data));
   }
   

  
}