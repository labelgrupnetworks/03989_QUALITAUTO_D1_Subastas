<?php

namespace App\Http\Controllers\admin\usuario;

use App\Http\Controllers\Controller;
use App\Models\V5\FgRepresentados;
use Illuminate\Http\Request;

class AdminRepresentadosController extends Controller
{
	public function index(Request $request, $cod_cli)
	{
		$representados = FgRepresentados::getRepresentedCollectionByClient($cod_cli);
		return response()->json(['representados' => $representados]);
	}

}
