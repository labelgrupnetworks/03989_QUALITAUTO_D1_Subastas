<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Config;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
		$defaultValues = Config::get("label.$section", []);
		$metas = Config::get("metas.$section", []);

		$configurations = [];
		foreach ($defaultValues as $key => $value) {
			$configurations[$key] = [
				'default' => $value,
				'current' => Config::get("app.$key"),
				'meta' => $metas[$key] ?? null
			];
		}

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

			$config = Web_Config::where('key', $key)->first();
			if (!$config) {
				Web_Config::create([
					'key' => $key,
					'value' => $value,
					'updated_by' => Session::get('user.cod'),
				]);
				continue;
			}

			Web_Config::where('id_web_config', $config->id_web_config)->update([
				'value' => $value,
				'updated_by' => Session::get('user.cod')
			]);
		}

		return response()->json(['message' => 'Configuraciones actualizadas correctamente.']);
	}

	/**
	 * Mostramos un resumen de las configuraciones distintas a los valores por defecto
	 */
	public function resume()
	{
		$configs = Web_Config::all();
		$sections = $configs->pluck('category')->unique()->sort()->values();

		return view('admin::pages.configuracion.configurations.resume', [
			'configs' => $configs,
			'sections' => $sections,
		]);
	}
}
