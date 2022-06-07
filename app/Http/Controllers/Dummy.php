<?php

namespace App\Http\Controllers;

use \App\Models\Receta_model;
use \App\Models\Receta_imagen_model;
use DB;

class Dummy extends Controller
{

    function index() {

        $data = array();

        $a = explode(" ",microtime())[0];
        $data['subastas'] = DB::table("FGSUB")->limit(10)->get();
        $b = explode(" ",microtime())[0];

        $data['tiempo'] = $b - $a;

    	return view('dummy',$data);

    }

    

}
