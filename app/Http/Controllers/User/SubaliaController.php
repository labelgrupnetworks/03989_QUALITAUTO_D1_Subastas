<?php

namespace App\Http\Controllers\User;

# Controladores

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request as Input;


# Modelos
use App\Models\V5\FxCliWeb;  // Clientes web
use App\Models\V5\Sub_Ambassador;
use App\libs\FormLib;
use Illuminate\Support\Facades\Session;
use App\Models\V5\SubAuchouse;

/* * ************************************************************************************************* */

//
//  Subalia -
//
//
//
//
//
/* * ************************************************************************************************* */
class SubaliaController extends Controller {

    /**
     * Mostrar landing de subalia
     * @return type
     */
    public function index() {

        $data = array();
        $data['formulario'] = new \stdClass();

        //form no login
        if (!Session::has('user')) {
            $data['formulario']->email = FormLib::Email("email", 1, "", 0, trans(\Config::get('app.theme') . '-app.login_register.room_user'));
            $data['formulario']->password = FormLib::Password("password", 1, "", 0, trans(\Config::get('app.theme') . '-app.login_register.room_password'));
            $data['formulario']->submit = FormLib::Submit(trans(\Config::get('app.theme') . '-app.login_register.send'), "formSubalia");
        } else {
            $data['formulario']->email = FormLib::Hidden("emailSubalia", 0, Session::get('user.usrw'));
            $data['formulario']->submit = FormLib::Submit(trans(\Config::get('app.theme') . '-app.login_register.send'), "formUserLogin");
        }

		#poner no index
		$seo=new \Stdclass();
		$seo->noindex_follow=true;
		$data['data']['seo']= $seo ;
        return view('pages.user.subalia', $data);
    }

    public function construirDatos(FxCliWeb $cliWeb) {

        //Array con nombre y apellido
        $ApellidoNombre = explode(",", $cliWeb->nom_cliweb);

        //Si nombre esta vacio los campos de apellido los movemos a nombre
        if (empty($ApellidoNombre[1])) {
            $ApellidoNombre[1] = $ApellidoNombre[0];
            $ApellidoNombre[0] = "";
        }

        //si fijura juridica es F utilizar nombre/appellido
        if ($cliWeb->fisjur_cli == "F") {
            $nombre1 = !empty($ApellidoNombre[1]) ? $ApellidoNombre[1] : "";
            $nombre2 = !empty($ApellidoNombre[0]) ? $ApellidoNombre[0] : "";
        }
        //sino razonSocial y nombre
        else {
            $nombre1 = !empty($cliWeb->rsoc_cli) ? $cliWeb->rsoc_cli : "";
            $nombre2 = !empty($cliWeb->nom_cli) ? $cliWeb->nom_cli : "";
        }

        //guardar array con los datos necesarios
        $data = array(
            'email_cli' => $cliWeb->usrw_cliweb,
            'nombre1' => $nombre1,
            'nombre2' => $nombre2,
            'tel1_cli' => !empty($cliWeb->tel1_cli) ? $cliWeb->tel1_cli : "",
            'cif_cli' => !empty($cliWeb->cif_cli) ? $cliWeb->cif_cli : "",
            'codpais_cli' => !empty($cliWeb->idioma_cli) ? $cliWeb->idioma_cli : "",
            'cp_cli' => !empty($cliWeb->cp_cli) ? $cliWeb->cp_cli : "",
            'pob_cli' => !empty($cliWeb->pob_cli) ? $cliWeb->pob_cli : "",
            'pro_cli' => !empty($cliWeb->pro_cli) ? $cliWeb->pro_cli : "",
            'dir_cli' => !empty($cliWeb->dir_cli) ? $cliWeb->dir_cli : "",
            'sg_cli' => !empty($cliWeb->sg_cli) ? $cliWeb->sg_cli : "",
            'fecnac_cli' => !empty($cliWeb->fecnac_cli) ? date('Y-m-d', strtotime($cliWeb->fecnac_cli)) : date('Y-m-d'),
            'sexo_cli' => !empty($cliWeb->sexo_cli) ? $cliWeb->sexo_cli : "",
            'cod_cli' => $cliWeb->cod_cliweb,
            'fisjur_cli' => !empty($cliWeb->fisjur_cli) ? $cliWeb->fisjur_cli : "",
        );

        return $data;
    }

    public function clienteLogeado($mail) {

        if (empty($mail)) {
            return $this->enviar(false, trans(\Config::get('app.theme') . '-app.msg_success.cli_licit_dont_exist'), "", 400);
        }

        $cliWeb = FxCliWeb::JoinCliCliweb()
                ->where("EMP_CLIWEB", Config::get("app.emp"))
                ->where("GEMP_CLIWEB", Config::get("app.gemp"))
                ->where("lower(USRW_CLIWEB)", strtolower($mail))
                ->where("tipacceso_cliweb", "!=", "X")
                ->first();

        return $cliWeb;
    }

    /**
     * Buscar si existe cliente, y enviar los datos necesarios
     *
     * @return type
     */
    public function buscarCliente() {

        $emailSubalia = Input::get('emailSubalia');

        if (Session::has('user')) {
            $cliWeb = $this->clienteLogeado($emailSubalia);
        }

        //Si no existe el usuario, debolver el error
        if (empty($cliWeb)) {
            return $this->enviar(false, trans(\Config::get('app.theme') . '-app.msg_success.cli_licit_dont_exist'), "", 400);
        }

        //construir array a enviar
        $data = $this->construirDatos($cliWeb);

        //recuperar con de subalia
        $cli_ambassador = Config::get('app.subalia_cli');

        //buscar key para subalia
        $keys = Sub_Ambassador::select('KEY_AMBASSADOR', 'COD_AMBASSADOR')->where('CLI_AMBASSADOR', $cli_ambassador)->get();

        //encriptar datos
        $dataEn = $this->encrypt(json_encode($data), $keys[0]->key_ambassador);

        //enviar junto a el cod_ambassador
        return $this->enviar(true, "Usuario correcto", $dataEn, 200, $keys[0]->cod_ambassador);
    }

    /**
     * Metodo generico para enviar respuestas Json
     *
     * @param Boolean $succes
     * @param String $mensaje de la respuesta
     * @param Object $datos a enviar
     * @param Integer $codigo de resupuesta
     * @return type
     */
    public function enviar($succes, $mensaje, $datos, $codigo, $member = "") {

        return response()->json([
                    'succes' => $succes,
                    'message' => $mensaje,
                    'data' => $datos,
                    'member' => $member
                        ], $codigo);
    }

    //Metodo de encriptación
    public function encrypt($data, $key) {
        return base64_encode(openssl_encrypt($data, "AES-256-ECB", $key, OPENSSL_RAW_DATA));
    }

    /*     * ************************************************************************************************* */
//
//
//  Subalia - Recibir datos de subalia para validar o crear usuario en subastas
//
//
    /*     * ************************************************************************************************* */

    /**
     * Mostrar login o checks para validar usuario
     * @return type
     */
    public function validarSubaliaIndex() {

        $data = array();
        $data['formulario'] = new \stdClass();

        $method = 'AES-256-ECB';

        $urlSubalia = Config::get("app.subalia_URL", "https://subalia.es");

        $info = request('info');
        $cliAuchouse = Config::get('app.subalia_cli');

        if (!empty($cliAuchouse) && !empty($info)) {

            $key = SubAuchouse::select('COD_AUCHOUSE', 'HASH')
                    ->where('CLI_AUCHOUSE', '=', $cliAuchouse)
                    ->where('EMP_ORIGIN_AUCHOUSE', '=', Config::get('app.emp'))
                    ->get();

            if (!empty($key)) {
                $info_decript = openssl_decrypt(base64_decode($info), $method, $key[0]->hash, OPENSSL_RAW_DATA);
                $info_json = json_decode($info_decript);
            }
        } else {
            return "error no info";
        }

        $cod_cli = $info_json->cod_cli;
        $redirect = $urlSubalia . $info_json->redirect;
        $data['redirect'] = $redirect;

        //form no login
        if (!Session::has('user')) {
            $data['formulario']->email = FormLib::Email("email", 1, "", 0, trans(\Config::get('app.theme') . '-app.login_register.room_user'));
            $data['formulario']->password = FormLib::Password("password", 1, "", 0, trans(\Config::get('app.theme') . '-app.login_register.room_password'));
            $data['formulario']->submit = FormLib::Submit(trans(\Config::get('app.theme') . '-app.login_register.send'), "formUserNoLogin");
        } else {
            //$data['formulario']->email = FormLib::Hidden("emailSubalia", 0, Session::get('user.usrw'));
            $data['formulario']->submit = FormLib::Submit(trans(\Config::get('app.theme') . '-app.login_register.send'), "formUserLogin");
        }

        //cuando este en pre-produccion
        //$data['formulario']->cod_cli = FormLib::Hidden("cod_cli", 0, Input::get('cod_cli'));
        $data['formulario']->cod_cli = FormLib::Hidden("cod_cli", 0, $cod_cli);
        $data['formulario']->redirect = FormLib::Hidden("redirect", 0, $redirect);
        $data['formulario']->check = FormLib::Bool("terminos", 1, 0);
        return view('pages.user.validarSubalia', $data);
    }

    /**
     * Buscar si existe cliente, y enviar los datos necesarios
     *
     * @return type
     */
    public function validarSubalia() {

        if (!Session::has('user')) {
            return $this->enviar(false, trans(\Config::get('app.theme') . '-app.msg_success.cli_licit_dont_exist'), "", 400);
        }

        $redirect = Input::get('redirect');

        //construir array a enviar
        $data = array();

        $data['info'] = array(
            'email_auchouse' => Session::get('user.usrw'),
            'codcli_subalia' => Input::get('cod_cli'),
            'new_user' => 'N'
        );

        $data['redirect'] = $redirect;

        $cliAuchouse = Config::get('app.subalia_cli');



        $key = SubAuchouse::select('COD_AUCHOUSE, HASH')
                ->where('CLI_AUCHOUSE', '=', $cliAuchouse)
                ->where('EMP_ORIGIN_AUCHOUSE', '=', Config::get('app.emp'))
                ->get();


        $data['info'] = $this->encrypt(json_encode($data['info']), $key[0]->hash);
        $data['cod_auchouse'] = $key[0]->cod_auchouse;


        return $this->enviar(true, "Usuario correcto", $data, 200);
    }

}
