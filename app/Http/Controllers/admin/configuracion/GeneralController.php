<?php

namespace App\Http\Controllers\admin\configuracion;
use Illuminate\Support\Facades\DB;
use Controller;
use View;
use Session;
use Route;
use Input;
use App\libs\MessageLib;

use App\libs\FormLib;


class GeneralController extends Controller
{

    public function index()
    {   
        
        $data = array();
        $data['registration_disabled'] = empty(\Config::get('app.registration_disabled'));
        return \View::make('admin::pages.configuracion.general.general',array('data' => $data));
        
    }
    
    public function save(){
        
        $status = $_POST['status'];
        
        DB::table('web_config')
                ->where([
                    'key' => 'registration_disabled',
                    'emp' => \Config::get('app.emp')
                ])
                ->update(['value' => $status]);

        return MessageLib::successMessage("Cambio almacenado");
                
    }
    
    
}
