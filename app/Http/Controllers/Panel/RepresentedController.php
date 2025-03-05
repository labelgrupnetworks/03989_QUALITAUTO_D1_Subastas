<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\V5\FgRepresentados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;


class RepresentedController extends Controller
{
	public function showList()
	{
		$codCli = Session::get('user.cod');
		$representedCollection = FgRepresentados::getRepresentedCollectionByClient($codCli);

		return view('pages.panel.represented', ['representedCollection' => $representedCollection]);
	}

	public function create(Request $request)
	{
		$request->validate([
			'nom_repre' => 'required|max:100',
			'cif_repre' => 'required|max:20',
			'alias_repre' => 'required|max:100',
		]);

		$newRepresented = [
			'cli_representados' => Session::get('user.cod'),
			'nom_representados' => $request->input('nom_repre'),
			'cif_representados' => $request->input('cif_repre'),
			'alias_representados' => $request->input('alias_repre'),
			'activo_representados' => 'S',
		];

		FgRepresentados::create($newRepresented);

		return redirect()->route('panel.represented.list', ['lang' => Config::get('app.locale')]);
	}

	public function update(Request $request)
	{
		$request->validate([
			'id_repre' => 'required|integer',
			'nom_repre' => 'required|max:100',
			'cif_repre' => 'required|max:20',
			'alias_repre' => 'required|max:100',
		]);

		$represented = FgRepresentados::where([
			'id' => $request->input('id_repre'),
			'cli_representados' => Session::get('user.cod'),
		])->first();

		if (!$represented) {
			abort(404);
		}

		$represented->nom_representados = $request->input('nom_repre');
		$represented->cif_representados = $request->input('cif_repre');
		$represented->alias_representados = $request->input('alias_repre');

		$represented->save();

		return redirect()->route('panel.represented.list', ['lang' => Config::get('app.locale')]);
	}

	public function toggleStatus(Request $request)
	{
		$request->validate([
			'id_repre' => 'required|integer',
		]);

		$represented = FgRepresentados::where([
			'id' => $request->input('id_repre'),
			'cli_representados' => Session::get('user.cod'),
		])->first();

		if (!$represented) {
			return response()->json(['status' => 'error', 'message' => ''], 404);
		}

		$represented->activo_representados = !$represented->activo_representados;
		$represented->save();

		return response()->json(['status' => 'success']);
	}

	public function delete(Request $request)
	{
		$request->validate([
			'id_repre' => 'required|integer',
		]);

		$represented = FgRepresentados::where([
			'id' => $request->input('id_repre'),
			'cli_representados' => Session::get('user.cod'),
		])->first();

		if (!$represented) {
			return response()->json(['status' => 'error', 'message' => ''], 404);
		}

		$represented->eliminado_representados = true;
		$represented->save();

		return redirect()->route('panel.represented.list', ['lang' => Config::get('app.locale')]);
	}
}
