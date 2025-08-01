<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Models\V5\FsOperadores;
use Illuminate\Http\Request;

class AdminOperadoresController extends Controller
{
	public function index()
	{
		$operadores = FsOperadores::all();
		return view('admin::pages.subasta.operadores._operadores_list', [
			'operadores' => $operadores,
		]);
	}

	public function toSelect()
	{
		$operadores = FsOperadores::toSelect();
		return view('admin::pages.subasta.operadores._operadores_options', [
			'operadores' => $operadores,
		]);
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'nom_operadores' => 'required|string|max:255',
		]);

		FsOperadores::where('cod_operadores', $id)
			->update(['nom_operadores' => $request->input('nom_operadores')]);

		return response()->json([
			'success' => true,
			'message' => 'Operador actualizado correctamente.'
		]);
	}

	public function store(Request $request)
	{
		// Validar los datos del formulario
		$request->validate([
			'nom_operadores' => 'required|string|max:255',
		]);

		// Crear un nuevo operador
		FsOperadores::create([
			'cod_operadores' => FsOperadores::withoutGlobalScopes(['emp'])->max('cod_operadores') + 1, // Generar un nuevo código
			'nom_operadores' => $request->input('nom_operadores')
		]);

		return response()->json([
			'success' => true,
			'message' => 'Operador creado correctamente.'
		]);
	}

	public function destroy($id)
	{
		FsOperadores::where('cod_operadores', $id)->delete();
		return response()->json([
			'success' => true,
			'message' => 'Operador eliminado correctamente.'
		]);
	}
}
