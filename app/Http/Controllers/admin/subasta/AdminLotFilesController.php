<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\libs\FormLib;
use App\Models\V5\FgHces1Files;
use Illuminate\Support\Facades\Config;

class AdminLotFilesController extends Controller
{
	private $emp;

	public function __construct()
	{
		$this->emp = Config::get('app.emp');
	}

	function create($num_hces1, $lin_hces1)
	{
		//formulario para añadir archivos
		//lo mostraremos sobre un modal
	}

	function show($id)
	{
		//mostrar archivo
	}

	function edit($id)
	{
		//formulario para editar archivo
		//lo mostraremos sobre un modal
/* 		tabla
		ID_HCES1_FILES	NUMBER(19,0)	No		1	Identificador de la tabla
		EMP_HCES1_FILES	VARCHAR2(3 BYTE)	No		2	Codigo de la empresa
		NUMHCES_HCES1_FILES	NUMBER(8,0)	No		3	Numero de HCES
		LINHCES_HCES1_FILES	NUMBER(38,0)	No		4	Numero de linea de HCES
		LANG_HCES1_FILES	VARCHAR2(8 CHAR)	Yes		5	Idioma
		PATH_HCES1_FILES	VARCHAR2(600 CHAR)	Yes		6	Ruta del archivo
		EXTERNAL_URL_HCES1_FILES	VARCHAR2(600 CHAR)	Yes		7	Url externa
		NAME_HCES1_FILES	VARCHAR2(2000 CHAR)	Yes		8	Nombre del archivo
		DESCRIPTION_HCES1_FILES	VARCHAR2(4000 CHAR)	Yes		9	Descripcion del archivo
		ORDER_HCES1_FILES	NUMBER(11,0)	Yes	0	10	Orden
		IMAGE_HCES1_FILES	VARCHAR2(600 CHAR)	Yes		11	Imagen
		IS_ACTIVE_HCES1_FILES	VARCHAR2(1 CHAR)	Yes	'S'	12	Activo
		PERMISSION_HCES1_FILES	VARCHAR2(1 CHAR)	Yes	'N'	13	Permisos: N-Sin permisos, U-Solo usuarios, A-Solo adminidinatores
		TYPE_UPDATE_HCES1_FILES	VARCHAR2(20 CHAR)	Yes		14
		DATE_UPDATE_HCES1_FILES	DATE	Yes		15
		USR_UPDATE_HCES1_FILES	VARCHAR2(100 CHAR)	Yes		16	 */
	}

	function update(Request $request, $id)
	{
		//actualizar archivo
	}

	function store(Request $request, $num_hces1, $lin_hces1)
	{
		//guardar archivo
	}

	function destroy($id)
	{

	}
}
