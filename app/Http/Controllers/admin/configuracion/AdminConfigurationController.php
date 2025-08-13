<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Config;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminConfigurationController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.superadmin');
	}

	public function index()
	{
		$sections = Web_Config::getSections();
		return view('admin::pages.configuracion.configurations.index', [
			'sections' => $sections
		]);
	}

	public function show($section)
	{
		$configurations = Web_Config::where('category', $section)->get();
		return view('admin::pages.configuracion.configurations.show', [
			'section' => $section,
			'configurations' => $configurations,
		]);
	}

	public function update(Request $request, $section)
	{
		$data = $request->validate([
			'configurations' => 'required|array',
		]);

		foreach ($data['configurations'] as $key => $value) {
			Web_Config::where('id_web_config', $key)->update([
				'value' => $value,
				'updated_by' => Session::get('user.cod')
			]);
		}

		return response()->json(['message' => 'Configuraciones actualizadas correctamente.']);
	}
}
