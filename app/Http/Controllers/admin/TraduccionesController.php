<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use Session;
use Config;
use Illuminate\Support\Facades\DB;
use App\Models\Translate;
use App\libs\TradLib;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TraduccionesController extends Controller
{
    public $emp ;
    public $gemp;
    public $content;
    public $archiveLang;

    public function __construct()
    {
        $this->emp = Config::get('app.main_emp');
        $this->content = new Translate();
		view()->share(['menu' => 'translates']);
    }

    /**
     * carga archivo y realiza merge con los datos de la base de datos
     * @param string $idioma
     *
     * @pendiente:
     *
     *  - dejar metodo aqui o mever al modelo??
     *
     */

    /*
     *Incluido en libreria, se deberia poder borrar
     * COMPROBAR QUE RETORNAN LOS MISMOS DATOS!
     */
    public function cargarArchivo($idioma){

        require lang_path(strtolower($idioma) . DIRECTORY_SEPARATOR . 'app.php');
        $this->archiveLang = $lang;

        $sql = "SELECT WEB_TRANSLATE_HEADERS.KEY_HEADER,WEB_TRANSLATE_KEY.KEY_TRANSLATE,WEB_TRANSLATE.WEB_TRANSLATION "
            . "FROM WEB_TRANSLATE_HEADERS "
            . "JOIN WEB_TRANSLATE_KEY ON (WEB_TRANSLATE_HEADERS.ID_HEADERS = WEB_TRANSLATE_KEY.ID_HEADERS_TRANSLATE AND WEB_TRANSLATE_KEY.ID_EMP = :emp) "
            . "JOIN WEB_TRANSLATE ON (WEB_TRANSLATE_KEY.ID_KEY = WEB_TRANSLATE.ID_KEY_TRANSLATE AND WEB_TRANSLATE.ID_EMP = :emp) "
            . "WHERE WEB_TRANSLATE.LANG = :idioma order by key_header, key_translate";

             $params = array(
               'emp' => Config::get('app.main_emp'),
               'idioma' => $idioma
            );
        $data = DB::select($sql, $params);

        $translate = array();

        foreach ($data as $key => $value){
            if(empty($translate[$value->key_header])){
                $translate[$value->key_header] = array();
            }
            $translate[$value->key_header][$value->key_translate] = $value->web_translation;
        }

        //primer merge para obtener todas las key_headers
        $headers = array_merge($lang, $translate);

        $result = array();

        //segundo merge en cada key_header para obtener todas las translate_keys
        foreach ($lang as $keyLang => $valueLang) {
            $result[$keyLang] = array_merge($lang[$keyLang], $headers[$keyLang]);
        }

        //añadir contenido de los headers existentes solo en base de datos
        foreach ($data as $indice => $value) {

            if(empty($result[$value->key_header])){
                $result[$value->key_header][$value->key_translate] = $value->web_translation;
            }
        }


        foreach ($this->content->headersTrans() as $headers){
            if(empty($result[$headers->key_header])){
                $result[$headers->key_header]['null'] = null;
            }
        }

        return $result;

    }

    /**
     *
     * @param type $head
     * @param type $lang
     * @return type
     *
     */
    public function index($head,$lang)
    {

        $data[$lang] = $this->content->getTranslate($this->emp,$head,$lang);


        $this->archiveLang = TradLib::getArchiveTranslations($lang);
        $trad = TradLib::getTranslations($lang);

        $traduccion = array();
        if(!empty($this->archiveLang[$head])){
            $traduccion = $this->archiveLang[$head];
        }

        foreach ($traduccion as $key => $value) {
            $traduccion[$key] = (object) [
                'key_header' => $head,
                'key_translate' => $key,
                'web_translation' => $value
            ];
        }

        // para las web_translate en español en caso de que existan
        foreach ($this->cargarArchivo('ES')[$head] as $key => $value) {

            $data['original'][$key] = (object) [
                'key_header' => $head,
                'key_translate' => $key,
                'web_translation' => $value
            ];
        }


        $data[$lang] = array_merge($traduccion, $this->content->getTranslate($this->emp,$head,$lang));
        $data['key'] = $head;
        $data['lang'] = $lang;
        $data['trans'] = true;

        return \View::make('admin::pages.traducciones', array('data' => $data));
    }


    /**
     * Guardar traducción
     * @pendiente
     *
     *  - En caso de borrar web_translate desde panel, esta no se borrara de la db.
     *      (con esto controlo no guardar campos null a la db, pero por contra no puedo dejar un campo vacio una vez creado)
     */
    public function SavedTrans() {
        $fechaactual = date("d m y H:i:s");
        $lang = Request::input('lang');
        $data = array();

        //creamos array con los diferentes key_header, key_translate y web_translation
        foreach ($_POST as $input_name => $value_old) {
            if ((string) $input_name != 'lang' && (string) $input_name != 'key_header' && (string) $input_name != '_token') {

                //extraemos del name el key_header y el key_tranlate
                $valores = explode("**", $input_name);
                $key_header = $valores[0];
                $key_translate = $valores[1];

                if (empty($data[$key_header])) {
                    $data[$key_header] = array();
                }

                //reemplazamos lascomillassimples para que no den error en el js
                $web_translation = str_replace("'", "´", $_POST[$input_name]);

                $data[$key_header][$key_translate] = $web_translation;
            }
        }

        //recorreos array constuido para crear o actualizar sus valores en db
        foreach ($data as $key_header => $key_translate) {

            //Si el header no existe en base de datos, se crea

            $id_header_result = $this->content->idHeaders($key_header, $this->emp);

            if(empty($id_header_result)){
                $id_header = $this->content->maxIdHeader($this->emp) + 1;
                $this->content->insertHeader($id_header, $this->emp, $key_header);
            }
            else{
                $id_header = $id_header_result->id_headers;
            }

            foreach ($key_translate as $key => $web_translation) {
                    $id_key_translate = $this->content->idKeyTranslateHeader($this->emp, $key, $id_header);

                    //puede existir la key_translate pero no en el idioma actual, exist elimina esa posibilidad
                    $exist = $this->content->idKey($this->emp,$id_key_translate,$lang);

                    //si no existe
                    if ((empty($id_key_translate) || empty($exist)) ){
                        //si el texto del input no esta vacio
                        if( !empty($web_translation)){
                            $this->nuevaTranslate($id_key_translate, $fechaactual, $key_header, $web_translation, $key, $lang);
                        }
                    }
                    //Si existe
                    else{
                        //si el input no esta vacio actualiza
                        if( !empty($web_translation)){
                            $this->content->updateTrans($id_key_translate, $web_translation, $this->emp, Session::get('user.name'), $fechaactual, $lang);
                        }
                        //si esta vacio, borra
                        else{
                            $this->content->deleteTrans ($id_key_translate, $this->emp, $lang);
                        }
                    }
            }
        }

        \Artisan::call('cache:clear');

		try {
			Artisan::call('generate:jstranslates');
		} catch (\Exception $e) {
			Log::info("Artisan generate translate, not found.".$e);
		}
    }

    /**
     * Guardar traducción
     * @pendiente
     *
     *  - En caso de borrar web_translate desde panel, esta no se borrara de la db.
     *      (con esto controlo no guardar campos null a la db, pero por contra no puedo dejar un campo vacio una vez creado)
     */
    public function SavedTrans_copiaOriginal(){
        $fechaactual = date("d m y H:i:s");
        $lang = Request::input('lang');
        $key_header = Request::input('key_header');


        foreach($_POST as $key_translate =>$value_old){
            if((string)$key_translate != 'lang' &&  (string)$key_translate != 'key_header' &&  (string)$key_translate != '_token' && $value_old != ''){

                $id_key_translate = $this->content->idKeyTranslate($this->emp, $key_translate);
                $exist = $this->content->idKey($this->emp,$id_key_translate,$lang);

               //reemplazamos lascomillassimples para que no den error en el js
               $web_translation = str_replace("'","´",$_POST[$key_translate]);

               if(!empty($exist)){
                   $this->content->updateTrans($id_key_translate,$web_translation,$this->emp, Session::get('user.name'),$fechaactual,$lang);
               }
               else{
                   $this->nuevaTranslate($id_key_translate, $fechaactual, $key_header, $web_translation, $key_translate, $lang);
               }
            }
        }
      \Artisan::call('cache:clear');
    }


    /**
     * Si web_translation no existe lo crea
     * en caso de no tener id_key_translate también lo crea
     */
    public function nuevaTranslate($id_key_translate, $fechaactual, $key_headers, $web_translation, $key_translate, $lang){

        $id_headers = $this->content->idHeaders($key_headers, $this->emp);

        //en caso de no existir key, la crea. (Es posible que no existra translate en el idiomoa acutal pero si en otro)
        if($id_key_translate == null) {
            $id_key_translate = $this->content->MaxHeaders($this->emp);
            $id_key_translate++;
            $this->content->insertKey($id_key_translate, $id_headers->id_headers, $this->emp, $key_translate);
        }

        $id_translate = $this->content->maxIdTranslate($this->emp);
        $id_translate++;
        $this->content->insertTrans($id_key_translate, $id_translate, $web_translation, $this->emp, Session::get('user.name'), $fechaactual, $lang);
    }


    public function NewTrans(){
        $fechaactual = date("d m y H:i:s");
        $key_headers = Request::input('key_headers');
        $lang = Request::input('lang');
        $web_translation = Request::input('web_translation');
         $key_translate = Request::input('key_translate');
         $id_headers=$this->content->idHeaders($key_headers,$this->emp);
         $id = $this->content->MaxHeaders($this->emp);
         $id++;
         $this->content->insertKey($id,$id_headers->id_headers,$this->emp,$key_translate);
         $id_key=$id;
         $id = $this->content->maxIdTranslate($this->emp);
         $id++;
         $this->content->insertTrans($id_key,$id,$web_translation,$this->emp, Session::get('user.name'),$fechaactual,$lang);
		 try {
			Artisan::call('generate:jstranslates');
		} catch (\Exception $e) {
			Log::info("Artisan generate translate, not found.".$e);
		}
    }

    /**
     *
     * @return type
     * @pendiente
     * Errores detectados:
     *
     * No mostrar texto en input si este solo esta en el otro idioma
     */
    public function search(){
        $data = array();

        if(!empty($_GET["lang"]) && !empty($_GET["web_translation"])){

            $data = array(
                'lang' => $_GET["lang"],
                'trad' => array(),
            );

            //busca y carga archivo y db
            //$data['trad'] = $this->cargarArchivo($_GET["lang"]);
            $data['trad'] = TradLib::getTranslations($_GET["lang"]);
            $this->archiveLang = TradLib::getArchiveTranslations($_GET["lang"]);
            ToolsServiceProvider::linguisticSearch();

            //busca resutados que contengan la cadena
            $data['trad'] = $this->searchTranslate($_GET["web_translation"], $data['trad'], $_GET["lang"]);

            ToolsServiceProvider::normalSearch();

        }
        return \View::make('admin::pages.traducciones_search', array('data' => $data));
    }

    public function getTraducciones_old(){
        $traducciones = new \App\Models\Translate;
        $trans = $traducciones->headersTrans();

        return $trans;
        return \View::make('admin::pages.traduccion', array('data' => $trans));
    }


    /**
     * Metodo de headers a mostrar en index de traducciones
     * @return type
     */
    public function getTraducciones(){


        $traducciones = $this->cargarArchivo('ES');
        $trans = array();

        //return $traducciones;
        foreach ($traducciones as $key => $value){
            array_push($trans, ['key_header' => $key]);
        }

        return \View::make('admin::pages.traduccion', array('data' => $trans));
    }


    /**
     * Busca la cadena sin tener en cuenta acentos, minusculas y/o mayusculas
     * @param type $data cadena a buscar
     * @param type $lang idimoa
     * @param type $traducciones array con todas las traducciones cargadas
     * @return type array elementos encontrados
     *
     * @pendiente
     *  - Comprobar si son necesarios todos los atributos que se incluien en data
     *  - El original debe mostrar por defecto el guardado en base de datos (hecho)
     */
    public function searchTranslate($data, $traducciones, $lang){

        $result = array();
        //recorre las traducciones
        foreach ($traducciones as $key_header => $array) {
            foreach ($array as $key_translate => $web_translation) {


                //busca en el web_translation original y el guardado en db
                $equal = strrpos($this->replacepreg($web_translation), $this->replacepreg($data));

                //guarda los datos necesarios
                if ($equal !== false){

                    $id_headers = "";
                    $id_key_translate = "";
                    $id_key_web = "";

                    $id_headers = $this->content->idHeaders($key_header, $this->emp);

                    if(!empty($id_headers)){
                        $id_key_translate = $this->content->idKeyTranslateHeader($this->emp, $key_translate, $id_headers->id_headers);
                        $id_key_web = $this->content->idKey($this->emp, $id_key_translate, $lang);
                    }

                    //Si no encuentro $id_headers guardo la key_header para que no produzca error
                    if(!empty($id_headers) || !is_object($id_headers)){
                        $id_headers = (object) array("id_headers" => $key_header);
                    }


                    $datos = (object) array(
                      "id_emp" => $this->emp,
                      "lang" => $lang,
                      "web_translation" => $web_translation,
                      "id_headers" => $id_headers->id_headers,
                      "key_header" => $key_header,
                      "id_key" => $id_key_web,
                      "id_key_translate" => $id_key_translate,
                      "id_headers_translate" => $id_headers->id_headers,
                      "key_translate" => $key_translate,
                    );

                    $result[$id_headers->id_headers][$key_translate] = $datos;

                }
            }
        }
        return $result;
    }

    /**
     * Retira los acentos y sustuye a minusculas
     * @param string $value cadena a modificar
     * @return string String cadena modificada
     */
    public function replacepreg($value){


        $patrones = array('/á|Á/', '/é|É/', '/í|Í/', '/ó|Ó/', '/ú|Ú/');
        $sustituciones = array('a', 'e', 'i', 'o', 'u');

        $value = preg_replace($patrones, $sustituciones, $value);


        $value = mb_strtolower($value);


        return $value;
    }



}
