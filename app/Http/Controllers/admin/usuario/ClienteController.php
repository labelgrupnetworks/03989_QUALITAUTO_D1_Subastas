<?php

namespace App\Http\Controllers\admin\usuario;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use View;
use Session;
use Route;
use Input;

use App\libs\FormLib;
use App\Models\FxCli;   // Clientes
use App\Models\FxCli2;   // Clientes aux
use App\Models\FxCliWeb;  // Clientes web
use App\Models\FsPaises;  // Paises
use App\Models\FgSg;   // Tipos de via
use App\Models\V5\FsParams;  // Parametros de la empresa
use App\Exports\ClientsExport;


class ClienteController extends Controller
{

    public function index()
    {
        $data = array('menu' => 3);

        //Eloy: Añadida condición gemp
        $clientes = DB::table("FXCli")->leftJoin("FxCliWeb",function($q)
                {
                    $q->on('FXCLIWEB.GEMP_CLIWEB','=','FXCLI.GEMP_CLI')
                    ->on('FXCLIWEB.COD_CLIWEB','=','FXCLI.COD_CLI');
                })
                ->where('FXCLI.GEMP_CLI','=',\Config::get('app.gemp'))
                ->get();

        /*Eloy: Al convertir el id a int se pierden los ceros a la izquierda
        foreach($clientes as $cliente) {
            $cliente->cod_cli = (int)$cliente->cod_cli;
        }*/

        $data['clientes'] = $clientes;

        return \View::make('admin::pages.usuario.cliente.index',$data);

    }

    function edit_run() {
        $data = Input::all();

        if (!isset($data['id']) || empty($data['id'])) {

            $clientCode = DB::table("FxCli")->addSelect("NVL(MAX(CAST(COD_CLI AS Int))+1,1) numero")
                        ->whereNull("TRANSLATE(cod_cli, 'T 0123456789', 'T')")
                        ->whereNotNull("cod_cli")
                        ->where("FxCli.GEMP_CLI",\Config::get('app.gemp'))
                        ->first()->numero;

            $longitud = FsParams::select("tcli_params")->MyEmpParams(\Config::get('app.emp'))->first()->tcli_params;
            $clientCode = str_pad($clientCode, $longitud, 0, STR_PAD_LEFT);
        }
        else {
            $clientCode = $data['id'];
        }

		$lang = 'ES';
		$publi_cliweb = 'N';

		if(isset($data['PUBLI_CLIWEB'])){
			$publi_cliweb = 'S';
		}

        $info_cli = [
            'GEMP_CLI' => \Config::get('app.gemp'),
            'COD_CLI' => $clientCode,
            'COD_C_CLI' => 4300,
            'TIPO_CLI' => 'W',
            'RSOC_CLI' => $data['rsoc_cli'],
            'NOM_CLI' => $data['nombre'],
            "BAJA_TMP_CLI" => $data['baja_tmp_cli'],
            'EMAIL_CLI' => mb_strtoupper($data["email"]),
            'F_ALTA_CLI' => date("Y-m-d H:i:s"),
            'FISJUR_CLI' => $data["pri_emp"],
            'IDIOMA_CLI' => $lang,
            'SG_CLI' => $data['codigoVia'],
            'CIF_CLI' => $data['nif'],
            'TEL1_CLI' => $data['telefono'],
            'DIR_CLI' => $data['direccion'],
            'PRO_CLI' => $data['provincia'],
            'POB_CLI' => $data['poblacion'],
            'CP_CLI' => $data['cpostal'],
            'CODPAIS_CLI' => $data['pais'],
            'FECNAC_CLI' => $data['date'],
			'SEXO_CLI' => $data['sexo'],
			'OBS_CLI' => $data['OBS_CLI']

        ];
        if (!empty($data['id'])) {
            DB::table("FxCli")
                ->where('gemp_cli', $info_cli['GEMP_CLI'])
                ->where("cod_cli", $data['id'])
                ->update($info_cli);
        }
        else {
			// Comprobamos que no exista ya un cliente con ese email. -

			$elEmailExiste = DB::table("FXCLIWEB")->where("GEMP_CLIWEB", \Config::get("app.gemp"))->where("UPPER(USRW_CLIWEB)",mb_strtoupper($data["email"]))->first();
			if (!empty($elEmailExiste)) {
				die("Ya existe un usuario con este email");
			}

            DB::table("FxCli")->insert($info_cli);
            DB::table("FxCli2")->insert([
                "GEMP_CLI2" => \Config::get('app.gemp'),
                "COD_CLI2" => $clientCode,
                "ENVCAT_CLI2" => 'N'
            ]);
        }


        $info_cliweb = [
            'GEMP_CLIWEB' => \Config::get('app.gemp'),
            'COD_CLIWEB' => $clientCode,
            'USRW_CLIWEB' => $data['email'],
            'EMP_CLIWEB' => \Config::get('app.emp'),
            'TIPO_CLIWEB' => 'C',
            'NOM_CLIWEB' => $data['nombre'],
            'EMAIL_CLIWEB' => $data['email'],
            'FECALTA_CLIWEB' => date("Y-m-d H:i:s"),
			'IDIOMA_CLIWEB' => $lang,
			'PUBLI_CLIWEB' => $publi_cliweb
        ];

        $password_MD5 = DB::table("Web_Config")->where("key","password_MD5")->first()->value;

        if (!empty($data['new-password'])) {
            $info_cliweb['PWDWENCRYPT_CLIWEB'] = md5($password_MD5.$data['new-password']);
        }


        if (!empty($data['id'])) {
            DB::table("FxCliWeb")
                ->where("gemp_cliweb", $info_cliweb['GEMP_CLIWEB'])
                ->where("cod_cliweb",$data['id'])
                ->update($info_cliweb);
        }
        else {
            DB::table("FxCliWeb")->insert($info_cliweb);
        }

        return redirect("/admin/cliente");

    }

    function edit($id = 0) {

        $data = array("id" => $id,'menu' => 3);
        $countries = array();
        $vias = array();

        $countries_aux = DB::table("FsPaises")->orderby("des_paises")->get();
        $country_selected = 'ES';
        $via_aux = DB::table("FgSg")->get();
        foreach ($countries_aux as $item) {
            $countries[$item->cod_paises] = $item->des_paises;
        }
        foreach ($via_aux as $item) {
            $vias[$item->cod_sg] = $item->des_sg;
        }


        $info = DB::table("FXCLI")->where("COD_CLI", $id)->where("GEMP_CLI", \Config::get("app.gemp"))->first();
        if (!empty($id)) {
            $info2 = DB::table("FXCLIWEB")->where("COD_CLIWEB", $id)->where("GEMP_CLIWEB", \Config::get("app.gemp"))->first();
        }
        else {
            $info2 = array();
        }

        $options = [
            'N' => 'Activo',
            'S' => 'Bloqueado',
            'A' => 'Pendiente',
            'B' => 'Rechazado'
        ];

        // Construimos los campos de formulario
		$data['formulario'] = array();

        $data['formulario']['id'] = FormLib::Hidden("id",1,$id);
		$data['formulario']['id origen'] = FormLib::TextReadOnly("cod2_cli",0,isset($info->cod2_cli)?$info->cod2_cli:'');
        $data['formulario']['usuario'] = FormLib::Text("nombre", 1, isset($info->nom_cli)?$info->nom_cli:'');
        $data['formulario']['estado'] = FormLib::Select("baja_tmp_cli", 1, isset($info->baja_tmp_cli)?$info->baja_tmp_cli:'', $options);
        $data['formulario']['razón social']  = FormLib::Text("rsoc_cli", 0, isset($info->rsoc_cli)?$info->rsoc_cli:'');
        $data['formulario']['forma jurídica'] = FormLib::Select("pri_emp", 1, isset($info->sexo_cli)?$info->fisjur_cli:'F', array("F" => "Particular", "J" => "Empresa"));

        $data['formulario']['email'] = FormLib::Email("email", 1, isset($info2->usrw_cliweb)?$info2->usrw_cliweb:'');
        $data['formulario']['contraseña'] = FormLib::Password("new-password", 0, "",0,"Mín. 6 caracteres. Dejar en blanco para no cambiarlo");

        $data['formulario']['sexo'] = FormLib::Select("sexo", 0, isset($info->sexo_cli)?$info->sexo_cli:'H', array("H" => "hombre", "M" => "mujer"));
        $data['formulario']['telefono'] = FormLib::Text("telefono", 1, isset($info->tel1_cli)?$info->tel1_cli:'');
        $data['formulario']['nif/cif'] = FormLib::Text("nif", 1, isset($info->cif_cli)?$info->cif_cli:'');
        $data['formulario']['fecha de nacimiento'] = FormLib::Date("date", 0, isset($info->fecnac_cli)?$info->fecnac_cli:'');


        $data['formulario']['via'] = FormLib::Select("codigoVia", 1, isset($info->sg_cli)?$info->sg_cli:'', $vias);
        $data['formulario']['pais'] = FormLib::Select("pais", 1, isset($info->codpais_cli)?$info->codpais_cli:$country_selected, $countries);
        $data['formulario']['dirección'] = FormLib::Text("direccion", 1, isset($info->dir_cli)?$info->dir_cli:'');
        $data['formulario']['código postal'] = FormLib::Text("cpostal", 1, isset($info->cp_cli)?$info->cp_cli:'');
        $data['formulario']['población'] = FormLib::Text("poblacion", 1, isset($info->pob_cli)?$info->pob_cli:'');
		$data['formulario']['provincia'] = FormLib::Text("provincia", 1, isset($info->pro_cli)?$info->pro_cli:'');
		$data['formulario']['condiciones2'] = FormLib::Bool("PUBLI_CLIWEB", 0, isset($info2->publi_cliweb) && $info2->publi_cliweb == 'S'? $info2->publi_cliweb:0);
		$data['formulario']['referencias'] = FormLib::Textarea("OBS_CLI", 0, isset($info->obs_cli) ? $info->obs_cli:'');

        $data['formulario']['SUBMIT'] = FormLib::Submit("Guardar", "edit");



        return \View::make('admin::pages.usuario.cliente.edit',$data);

    }

    function bajaCliente() {

        $data = Input::all();

        if (isset($data['cliente']) && !empty($data['cliente'])) {

            DB::table("FXCLI")->where("COD_CLI",$data['cliente'])->update([
                "BAJA_TMP_CLI" => "S"
            ]);
            echo "OK";
        }

    }

    function reactivarCliente() {

        $data = Input::all();

        if (isset($data['cliente']) && !empty($data['cliente'])) {

            DB::table("FXCLI")->where("COD_CLI",$data['cliente'])->update([
                "BAJA_TMP_CLI" => "N"
            ]);
            echo "OK";
		}

	}


	function export(){
		return (new ClientsExport())->download("clientes" . "_" . date("Ymd") . ".xlsx");
	}


}
