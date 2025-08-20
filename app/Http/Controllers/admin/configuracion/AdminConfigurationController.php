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
		$configurations = Web_Config::where('category', $section)->get();
		$defaultValues = Config::get("label.$section", []);

		return view('admin::pages.configuracion.configurations.show', [
			'section' => $section,
			'configurations' => $configurations,
			'defaultValues' => $defaultValues
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

	/**
	 * Mostramos un resumen de las configuraciones distintas a los valores por defecto
	 */
	public function resume()
	{
		//necesito recuperar directamente los valores de /config/app/xxx.php sin usar helper config
		$defaultValues = array_merge(
			include config_path('app.php'),
			include config_path('app/admin.php'),
			include config_path('app/behavior.php'),
			include config_path('app/display.php'),
			include config_path('app/features.php'),
			include config_path('app/global.php'),
			include config_path('app/mail.php'),
			include config_path('app/services.php'),
			include config_path('app/user.php')
		);
		$configValues = Config::get('app');

		$configValues = array_filter($configValues, function($value) {
			return !is_array($value);
		});
		$defaultValues = array_filter($defaultValues, function($value) {
			return !is_array($value);
		});

		$differences = array_diff_assoc($configValues, $defaultValues);

		$metas = Config::get('metas', []);
		$metaKeys = [];
		foreach ($metas as $metaSection) {
			$metaFiltered = array_filter($metaSection, function($meta) {
				return in_array($meta['type'], ['integer', 'boolean']);
			});
			$metaKeys = array_merge($metaKeys, array_keys($metaFiltered));
		}

		//filter in difference only metaKeys

		$differences = array_intersect_key($differences, array_flip($metaKeys));

		$configs = Web_Config::whereIn('key', array_keys($differences))
			->get();

		foreach ($configs as $config) {
			$string = "La configuración {$config->key} - {$config->meta['description']}. Tiene el valor {$config->value}.";
			dump($string);
		}

		dd($configs);

	}
}
