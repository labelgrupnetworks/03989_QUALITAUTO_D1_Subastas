<?php
namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use stdClass;

class LandingController extends Controller
{
    
    public function landing(){
        
        $data = array();
        
        $data['seo'] = new stdClass();
        $data['seo']->meta_title = 'Numismática Madrid | Monedas antiguas | Tauler & Fau';
        $data['seo']->meta_description = 'Si buscas expertos en Numismática Madrid podemos ayudarte. En Tauler & Fau llevamos más de 50 años trabajando en el sector de la numismática.';
        
        return view('pages.landing.landing', array("data" =>$data));
        
    }
    

}
