<?php

namespace App\Http\Controllers\admin\configuracion;

use Illuminate\Support\Facades\DB;
use View;
use Session;
use Route;
use Input;
use App\libs\MessageLib;
use App\libs\FormLib;
use App\Models\V5\FsEmail;

use App\Http\Controllers\Controller;

use App\Models\WebNewbannerModel;
use App\Models\WebNewbannerItemModel;
use App\Models\WebNewbannerTipoModel;


class EmailController extends Controller
{
	public function index() {

		$data = array('menu' => 1);

		$data['emails'] = DB::table("FSEMAIL")->where("EMP_EMAIL",\Config::get("app.emp"))->get();

		return \View::make('admin::pages.configuracion.email.index',$data);

	}

    public function plantilla() {

        $aux = DB::table("FSEMAIL_TEMPLATE")->where("EMP_TEMPLATE",\Config::get("app.emp"))->first();

        $data['template'] = FormLib::TextArea("plantilla",1,$aux->design_template);

        return \View::make('admin::pages.configuracion.email.plantilla',$data);

    }

    public function guardarPlantilla() {

        $data = Input::all();

        DB::table("FSEMAIL_TEMPLATE")->where("EMP_TEMPLATE",\Config::get("app.emp"))->update([
            "DESIGN_TEMPLATE" => $data['html']
        ]);

        echo "OK";

    }

    public function edit($cod) {

        $data = array('menu' => 1);

        $data['email'] = DB::table("FSEMAIL")->where("COD_EMAIL",$cod)->where("EMP_EMAIL",\Config::get("app.emp"))->first();
		//$emailLang = DB::table("FSEMAIL_LANG")->where("CODEMAIL_LANG",$cod)->where("EMP_LANG",\Config::get("app.emp"))->get();
		$emailLang = FSEMAIL::JoinFsEmailLang()->where("COD_EMAIL",$cod)->where("EMP_EMAIL",\Config::get("app.emp"))->get();

        foreach(\Config::get("app.language_complete") as $lang => $textLang) {
            $locales[$textLang] = $lang;
        }


        foreach($emailLang as $item) {
			if(!empty($item->lang_lang)){
				$data['email'.$locales[$item->lang_lang]] = $item;
			}
		}


        $data['formulario'] = array();

        $data['formulario']['cod_email'] = FormLib::ReadOnly("cod_email",1,$data['email']->cod_email);
        $data['formulario']['cod_template_email'] = 1;
        $data['formulario']['des_email'] = FormLib::Text("des_email",1,$data['email']->des_email);
        $data['formulario']['type_email'] = FormLib::Select("type_email",1,$data['email']->type_email,array("A" => "Administración", "L" => "Usuario"));
        $data['formulario']['enabled_email'] = FormLib::Bool("enabled_email",0,$data['email']->enabled_email);

        $data['formulario']['subject_email'] = array();
        $data['formulario']['body_email'] = array();

        foreach(\Config::get("app.locales") as $lang => $textLang) {

			//$data['formulario']['subject_email'][$lang] = FormLib::Text("subject_email_".$lang,0,isset($data['email'.$lang]->subject_lang)?$data['email'.$lang]->subject_lang:'');
			if(!empty($data['email'.$lang]->subject_lang)){
				$data['formulario']['subject_email'][$lang] = FormLib::Text("subject_email_".$lang,0,$data['email'.$lang]->subject_lang);
			}
			else{
				$data['formulario']['subject_email'][$lang] = FormLib::Text("subject_email_".$lang,0,isset($data['email'.$lang]->subject_email)?$data['email'.$lang]->subject_email:'');
			}

			//$data['formulario']['body_email'][$lang] = FormLib::TextArea("body_email_".$lang,0,isset($data['email'.$lang]->body_lang)?$data['email'.$lang]->body_lang:'');
			if(!empty($data['email'.$lang]->body_lang)){
				$data['formulario']['body_email'][$lang] = FormLib::TextArea("body_email_".$lang,0,$data['email'.$lang]->body_lang);
			}
			else{
				$data['formulario']['body_email'][$lang] = FormLib::TextArea("body_email_".$lang,0,isset($data['email'.$lang]->body_email)?$data['email'.$lang]->body_email:'');
			}
        }

        return \View::make('admin::pages.configuracion.email.edit',$data);

    }


    public function guardarEmail() {

        $data = Input::all();

        $lang = \Config::get("app.language_complete")[$data['idioma']];

        $existeItem = DB::table("FSEMAIL_LANG")->where("EMP_LANG",\Config::get("app.emp"))->where("CODEMAIL_LANG",$data['key'])->where("LANG_LANG",$lang)->first();

        if ($existeItem) {

            DB::table("FSEMAIL_LANG")->where("EMP_LANG",\Config::get("app.emp"))->where("CODEMAIL_LANG",$data['key'])->where("LANG_LANG",$lang)->update([
                "SUBJECT_LANG" => $data['asunto'],
                "BODY_LANG" => $data['cuerpo']
            ]);

        }
        else {

            DB::table("FSEMAIL_LANG")->insert([
                "LANG_LANG" => $lang,
                "CODEMAIL_LANG" => $data['key'],
                "EMP_LANG" => \Config::get("app.emp"),
                "SUBJECT_LANG" => $data['asunto'],
                "BODY_LANG" => $data['cuerpo']

            ]);
        }

        $plantilla = DB::table("FSEMAIL_TEMPLATE")->where("EMP_TEMPLATE",\Config::get("app.emp"))->first();

        $html = "";
        $html .= "<h3>ASUNTO: ".$data['asunto']."</h3><br><br>";
        $a = $plantilla->design_template;
        $tags = "|\[#[a-zA-Z0-9_-áéíóúÁÉÍÓÚ@/(/),.-¿/%]*(\s*[a-zA-Z0-9_-ÁÉÍÓÚáéíóú/(/)@,.-/%]*)*[a-zA-Z0-9_-áéíóúÁÉÍÓÚ?/(/)@/%,.-]+\#]|";
        preg_match_all($tags, $a, $matches);

        foreach($matches[0] as $item) {
            $key = str_replace(array("[#","#]"),"",$item);
            $a = str_replace($item,trans(\Config::get('app.theme').'-app.emails.'.$key),$a);
        }

        $a = str_replace("[*CONTENT*]",$data['cuerpo'],$a);

        $html .= $a;

        echo $html;

    }


    public function guardar() {

        $data = Input::all();

        if ($data['tipo'] == "-")
            $data['tipo'] = "L";

        if (($data['activo'] == "false")) {
            $data['activo'] = "0";
        }
        else {
            $data['activo'] = "1";
        }

        DB::table("FSEMAIL")->where("EMP_EMAIL",\Config::get("app.emp"))->where("COD_EMAIL",$data['key'])->update([
            "DES_EMAIL" => $data['descripcion'],
            "SUBJECT_EMAIL" => $data['asunto'],
            "BODY_EMAIL" => $data['cuerpo'],
            "ENABLED_EMAIL" => $data['activo'],
            "TYPE_EMAIL" => $data['tipo']
        ]);

        echo "OK";

    }

}
