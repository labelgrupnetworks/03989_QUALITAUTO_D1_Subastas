<?php
namespace App\Http\Controllers;

use Redirect;
use Config;
use Response;
use View;
use Route;
use Input;
use Request;
use App\Models\Content;
use App\Models\AucIndex;
use App\Models\User;
use App\Http\Controllers\V5\AutoFormulariosController;

class ServicesController extends Controller
{
    public function index(Request $request){

        return View::make('pages.servicios.servicios');

    }

    public function encapsulacion(Request $request){

        return View::make('pages.servicios.encapsulacion');

    }
    
    public function nuevaEncapsulacion(){
        
        $key = "encapsulacion_" . strtoupper(Config::get('app.locale'));
        $html = "{html}";
        $content = \Tools::slider($key, $html);
        $title = trans(\Config::get('app.theme').'-app.services.encapsulacion');
        
        $autoFormulario = new AutoFormulariosController();
        return $autoFormulario->AutoFormularioEncapsulado("nomApell, direccion, cp, poblacion, provincia, pais, telefono, email, servicio, descripcion, precio", $title, $content, "top");
        
    }

    public function valoracionFotografia(Request $request, $lang){

        try {


            $false = true;

            \App::setLocale($lang);


            $htmlFields = false;
            $prohibidos = array('_token');

            foreach ($_POST as $key => $value) {

                // Inputs prohibidos de mostrar
                if (!in_array($key, $prohibidos)) {
                    if (!is_array($key) && !is_array($value)) {
                        $htmlFields .= '<strong>' . ucfirst(htmlspecialchars($key)) . ':</strong> ' . htmlspecialchars($value) . ' <br />';
                    }
                }
            }


                $emailOptions['texto'] = trans(\Config::get('app.theme') . '-app.services.text_consult_photos');
                $emailOptions['user'] = Request::input('nombre');
                $emailOptions['camposHtml'] = $htmlFields;


            $utm_email = '';
            if (!empty(Config::get('app.utm_email'))) {
                $utm_email = Config::get('app.utm_email') . '&utm_campaign=fotografia';
            }
            $emailOptions['UTM'] = $utm_email;
            $send_email = Config::get('app.admin_email');
            $emailOptions['to'] = $send_email;
            $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.services.calculate_photos');


            if (\Tools::sendMail('mailer', $emailOptions)) {
                $emailOptions['to'] = $_POST['email'];
                $temp = $emailOptions['camposHtml'];
                $emailOptions['camposHtml'] = trans(\Config::get('app.theme') . '-app.services.msg_client') . '<br><br>' . $temp;
                if(\Tools::sendMail('mailer', $emailOptions)){
                    return $result = array(
                        'status' => 'success',
                        'msg'=> trans(\Config::get('app.theme') . '-app.services.form_success')
                    );
                }else{
                    return $result = array(
                        'status' => 'error',
                    );
                }


            } else {

                return $result = array(
                    'status' => 'error',
                );
            }
        } catch (\Exception $e) {

            \Log::error("Error en Valoración" . print_r($_POST, true));
            \Log::error($e);
            return $e;
        }

    }

    public function valoracionEncapsulacion(Request $request, $lang){

        try {

            \App::setLocale($lang);

            $htmlFields = false;
            $prohibidos = array('_token');

            foreach ($_POST as $key => $value) {

                // Inputs prohibidos de mostrar
                if (!in_array($key, $prohibidos)) {
                    if (!is_array($key) && !is_array($value)) {
                        $htmlFields .= '<strong>' . ucfirst(htmlspecialchars($key)) . ':</strong> ' . htmlspecialchars($value) . ' <br />';
                    }
                }
            }
            $valores = Request::input('valor');
            $descripciones = Request::input('descripcion');

            $htmlFields .= '<br><br><div>Listado de artículos para encapsular</di><br><br>';
            foreach ($descripciones as $key => $value){
                if($key != 0){
                    $htmlFields .='<br /><strong>Descripción: </strong> <span>'.$value.'</span><br><strong>Valor: </strong> <span>'.$valores[$key].'</span><br />';
                }

            }


                $emailOptions['texto'] = trans(\Config::get('app.theme') . '-app.services.service_encap_suject_email') . ' ' . Config::get('app.name');
                $emailOptions['user'] = Request::input('nombre');
                $emailOptions['camposHtml'] = $htmlFields;


            $utm_email = '';
            if (!empty(Config::get('app.utm_email'))) {
                $utm_email = Config::get('app.utm_email') . '&utm_campaign=encapsulacion';
            }
            $emailOptions['UTM'] = $utm_email;
            $send_email = Config::get('app.admin_email');
            $emailOptions['to'] = $send_email;
            $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.services.encapsulacion_subject') . ' ' . Config::get('app.name');


            if (\Tools::sendMail('mailer', $emailOptions)) {


                $emailOptions['to'] = $_POST['email'];
                $temp = $emailOptions['camposHtml'];
                $emailOptions['camposHtml'] = trans(\Config::get('app.theme') . '-app.services.msg_client') . '<br><br> '. $temp;
                if(\Tools::sendMail('mailer', $emailOptions)){
                    return $result = array(
                        'status' => 'success',
                        'msg'=> trans(\Config::get('app.theme') . '-app.services.form_success')
                    );
                }else{
                    return $result = array(
                        'status' => 'error',
                    );
                }
            } else {

                return $result = array(
                    'status' => 'error',
                );
            }
        } catch (\Exception $e) {

            \Log::error("Error en Valoración" . print_r($_POST, true));
            \Log::error($e);
            return $e;
        }

    }
    
}
