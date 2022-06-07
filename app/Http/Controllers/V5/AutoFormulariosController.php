<?php
namespace App\Http\Controllers\V5;

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

use App\Http\Controllers\Controller;

# Cargamos el modelo
use App\Models\Content;
use App\Models\AucIndex;
use App\Models\User;
use App\Models\V5\Web_Page;

use App\libs\FormLib;
use App\libs\EmailLib;
use App\libs\MessageLib;



class AutoFormulariosController extends Controller
{

    /****************************************************************************************************/
    //
    //  ValoracionArticulos - Función general de formulario de valoración de artículos.
    //
    /****************************************************************************************************/

	public function workWidthUs($key) {
		$pagina = new App\Models\Page();
		$curriculumArte  = $pagina->getPagina(\Config::get("app.locale"),$key);
		\Tools::exit404IfEmpty($curriculumArte);

		$autoFormulario = new AutoFormulariosController();
        return $autoFormulario->AutoFormularioEncapsulado("nomApell,  email, telefono, mensaje, file_curriculum",$curriculumArte->name_web_page,$curriculumArte->content_web_page, "top");

    }

    public function Tasaciones() {

        $title = trans(\Config::get('app.theme').'-app.home.free-valuations');
        $content = trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_assessment');
        return $this->AutoFormulario("nombre,email,telefono,descripcion,imagen",$title, $content,"top");

    }



    /****************************************************************************************************/
    //
    //  ComprarCatalogo - Función general de formulario de valoración de artículos.
    //
    /****************************************************************************************************/


    public function ComprarCatalogo() {

        $title = trans(\Config::get('app.theme').'-app.foot.comprar_catalogo');
        $content = "";
        $a = Web_Page::where("key_web_page","contacto")->where("lang_web_page",strtoupper(\Config::get("app.locale")))->where("emp_web_page",\Config::get("app.emp"))->first();
        if (!empty($a)) {
            $content = $a->content_web_page;
        }
        return $this->AutoFormulario("nombre,apellidos,nif,profesion,direccion,poblacion,provincia,pais,cp,email,telefono",$title, $content,"right");

    }
















    //****************************************************************************************************
    //****************************************************************************************************
    //
    //  FUNCIONES GENERALES PARA TODOS LOS FORMULARIOS - Lo que sentido al concepto autoformulario
    //
    /****************************************************************************************************/
    /****************************************************************************************************/


    /****************************************************************************************************/
    //
    //  AutoFormulario - Función general de construcción de formulario a partir de la librería FormLib y
    //  js asociado. Existen varios campos ya predefinidos:
    //
    //  - nombre                   - apellidos               - telefono              - descripcion
    //  - email
    //
    //  @fields  - Lista de campos de los formularios
    //  @title   - Título del formulario
    //  @content - Contenido opcional para mostrar junto con el formulario
    //  @view    - Tipo de vista para mostrar los contenidos (top - left - right)
    //
    /****************************************************************************************************/


    public function AutoFormulario($fields = null, $title = null, $content = null, $view = "left") {


        $SEO_metas = new \stdClass();
        $SEO_metas->noindex_follow = true;

        $data = array(
            'title' =>  $title,
            'content' =>  $content  ,
            'seo'   => $SEO_metas,
        );

        // Contenido de la página
        $data['content'] = $content;

        // Generamos el formulario
        $data['formulario'] = FormLib::getFields($fields);

        // Botoón de submit
        $data['submit'] = FormLib::Submit(trans(\Config::get('app.theme').'-app.global.enviar'),"autoformulario");

        if (Config::get('app.assessment_registered') && !Session::has('user')){
            return \View::make('pages.autoformularios.register', array('data' => $data));
        }
        else {
            return \View::make('pages.autoformularios.'.$view, array('data' => $data));
        }


    }





    /****************************************************************************************************/
    //
    //  Send - Envío de email con los datos del autoformulario
    //
    /****************************************************************************************************/

    public function Send() {




        // Email admin
        $email = new EmailLib('AUTOFORMULARIO');

        if (!empty($email->email)) {
			$email->attachmentsFiles = array();
			if (!empty(Input::file('images'))) {
				$email->attachmentsFiles = Input::file('images');
			}

			if (!empty(Input::file('files'))) {
				$email->attachmentsFiles[] = Input::file('files');
			}


            $html = "";
            $data = Input::all();

            $email->email->subject_email = $data['subject'];

            unset($data['images']);
			unset($data['files']);
            unset($data['subject']);
            unset($data['_token']);
            unset($data['g-recaptcha-response']);
            unset($data['condiciones']);


            foreach($data as $k => $item) {
                $html .= "<b>".trans(\Config::get('app.theme').'-app.global.'.$k)."</b>: ".$item."<br><br>";
            }

            $email->setAtribute('CONTENT',$html);
            $email->setTo(Config::get('app.admin_email'));
            $email->send_email();




            $a = MessageLib::successMessage(trans(\Config::get('app.theme').'-app.global.mensaje_enviado'));
            $a['url'] = "/".\Config::get('app.locale')."/autoformulario-success";
            return $a;
        }
        else {
            return MessageLib::errorMessage(trans(\Config::get('app.theme').'-app.global.mensaje_no_enviado'),"Error");
        }


    }












    /****************************************************************************************************/
    //
    //  Success - Pantalla final una vez enviado el formulario
    //
    /****************************************************************************************************/


    public function Success(){

        $SEO_metas = new \stdClass();
        $SEO_metas->noindex_follow = true;

        $data = array(
            'title' => trans(\Config::get('app.theme').'-app.valoracion_gratuita.success'),
            'seo'   => $SEO_metas,
        );

        return \View::make('pages.autoformularios.success', array('data' => $data));
    }






    public function AutoFormularioEncapsulado($fields = null, $title = null, $content = null, $view = "left") {



        $SEO_metas = new \stdClass();
        $SEO_metas->noindex_follow = true;

        $data = array(
            'title' =>  $title,
            'content' =>  $content  ,
            'seo'   => $SEO_metas,
        );

        // Contenido de la página
        $data['content'] = $content;

        // Generamos el formulario
        $data['formulario'] = FormLib::getFields($fields);

        // Botoón de submit
        $data['submit'] = FormLib::Submit(trans(\Config::get('app.theme').'-app.global.enviar'),"autoformulario");



        return \View::make('pages.autoformularios.'.$view, array('data' => $data));



    }



}
