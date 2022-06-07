<?php

namespace App\Http\Controllers\admin\configuracion;

use Request;
use App\Http\Controllers\Controller;
use Session;
use Config;
use Illuminate\Support\Facades\DB;
use App\Models\Translate;
use App\libs\TradLib;
use App\libs\FormLib;
use Input;

class ImportarController extends Controller {

    public $emp;
    private $mimes = array("text/comma-separated-values", "text/csv", "application/csv", "application/excel", "application/vnd.ms-excel", "application/vnd.msexcel", "text/anytext");

    public function __construct() {
        $this->emp = Config::get('app.emp');
    }

    public function index() {

        $data = array('menu' => 1);

        $data['formulario'] = array();
        $data['formulario']['Archivo'] = FormLib::File("archivo", 1, '');
        //print_r(Config::get('app.locales'));
        //die;

        $data['formulario']['Idioma'] = FormLib::Select("idioma", 1, Config::get('app.locales'), Config::get('app.locales'));
        $data['formulario']['Submit'] = FormLib::Submit("Enviar", "edit");
        return \View::make('admin::pages.configuracion.traducciones.importar', $data);
    }

    public function leerCsv(Request $request) {

        if ($_FILES['archivo']['error'] > 0) {
            return array(
                'succes' => 'error',
                'msg' => 'errores en el archivo'
            );
        }

        $extension = explode(".", $_FILES['archivo']['name']);
        if ($extension[1] != "csv") {
            return array(
                'succes' => 'error',
                'msg' => 'formato de archivo incorrecto',
                'formato' => $extension
            );
        }

        if (!in_array($_FILES['archivo']['type'], $this->mimes)) {
            return array(
                'succes' => 'error',
                'msg' => 'tipo de archivo incorrecto'
            );
        }

        $idioma = Input::get('idioma');

        $this->cargarArchivo($_FILES['archivo']['tmp_name'], strtoupper($idioma));

        return 'datos creados correctamente';
    }

    private function cargarArchivo($file, $idioma) {

        $translate = new Translate();
        $translateControler = new TraduccionesController();

        $emp = Config::get('app.emp');
        $linea = 0;
        //Abrimos nuestro archivo

        $archivo = fopen($file, "r");
        //Lo recorremos

        while (($datos = fgetcsv($archivo, 0, ";")) == true) {
            $num = count($datos);
            $linea++;
            //Recorremos las columnas de esa linea


            $key_header = $datos[0];

            if ($key_header != "﻿translate_header") {

                $id_header_result = $translate->idHeaders($key_header, $emp);

                //crear header o obtener su id
                if (empty($id_header_result)) {
                    $id_header = $translate->maxIdHeader($emp) + 1;
                    $translate->insertHeader($id_header, $emp, $key_header);
                } else {
                    $id_header = $id_header_result->id_headers;
                }


                $key_translate = $datos[1];
                $id_key_translate = $translate->idKeyTranslateHeader($emp, $key_translate, $id_header);

                //puede existir la key_translate pero no en el idioma actual, exist elimina esa posibilidad
                $exist = $translate->idKey($emp, $id_key_translate, $idioma);

                $web_translation = $datos[2];
                //si no existe
                if ((empty($id_key_translate) || empty($exist))) {
                    //si el texto del input no esta vacio
                    if (!empty($web_translation)) {
                        $translateControler->nuevaTranslate($id_key_translate, date("d m y H:i:s"), $key_header, $web_translation, $key_translate, $idioma);
                    }
                }
                //Si existe
                else {
                    //si el input no esta vacio actualiza
                    if (!empty($web_translation)) {
                        $translate->updateTrans($id_key_translate, $web_translation, $emp, Session::get('user.name'), date("d m y H:i:s"), $idioma);
                    }
                    //si esta vacio, borra
                    else {
                        //comprobar esto ya que si añado un nuevo archivo elejir si debe contener todas las traducciones
                        //o solo añadir las nuevas para determinar si eliminar o no
                        //$translate->deleteTrans($id_key_translate, $this->emp, $lang);
                    }
                }
            }
        }

        //Cerramos el archivo
        fclose($archivo);
    }

}
