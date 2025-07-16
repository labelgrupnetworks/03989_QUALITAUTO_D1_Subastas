<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class ResourceController extends Controller
{
	//Ver todos los Recursos que hay
	public function index(Request $request)
	{
		$type = ['I', 'H'];
		if ($request->input('see') == 'A') {
			$type = ['A'];
		} elseif ($request->input('see') == 'C') {
			$type = ['C'];
		}

		$resouces = DB::table('web_resource')
			->select(
				DB::raw("(select id_web_resource from web_resource_banner where web_resource_banner.id_web_resource = web_resource.id_web_resource group by id_web_resource) as id_web_resource_banner"),
				'id_web_resource',
				'title',
				'content'
			)
			->where('id_emp', config('app.main_emp'))
			->whereIn('type', $type)
			->when($request->input('crs'), function ($query) use ($request) {
				return $query->where('cod_banner_sec', $request->input('crs'));
			})
			->orderBy('title', 'asc')
			->get();

		$data['inf'] = $resouces;

		return View::make('admin::pages.resource', ['data' => $data]);
	}

	//Ver la informacion del bloque si no existe todo vacio
	public function SeeResources($id = NULL)
	{
		$resource = DB::table('web_resource')
			->where('id_web_resource', $id)
			->where('id_emp', config('app.main_emp'))
			->first();

		return View::make('admin::pages.editResource', ['bloque' => $resource]);
	}

	public function EditResources(Request $request)
	{
		$type = $request->input('type');

		$html = match ($type) {
			'H' => $request->input('html'),
			'A' => $request->input('html'),
			'C' => $request->input('fecha'),
			default => $request->input('text_html')
		};

		$params = [
			'title' => $request->input('name'),
			'url_resource' => $request->input('file_url'),
			'url_link' => $request->input('url_link', null),
			'new_window' => $request->input('new_windows') == 'on' ? 1 : 0,
			'type' => $type,
			'content' => $html,
			'enabled' => $request->input('enabled') == 'on' ? 1 : 0,
			'time_cache' => $request->input('cache', 0),
			'id_emp' => Config::get('app.main_emp'),
			'creation_date' => date("Y-m-d H:i:s"),
			'update_date' => date("Y-m-d H:i:s")
		];

		$id = $request->input('id', 0);

		if ($id < 1) {
			// Crear nuevo recurso
			$max_id = DB::table('web_resource')->max('id_web_resource');
			$max_id = $max_id + 1;
			$params['id_web_resource'] = $max_id;

			DB::table('web_resource')->insert($params);
			$id = $max_id;
		} else {
			// Actualizar recurso existente
			$params['cod_banner_sec'] = $request->input('cod_sec', null);

			DB::table('web_resource')
				->where('id_web_resource', $id)
				->where('id_emp', Config::get('app.main_emp'))
				->update($params);
		}

		return $id;
	}

	public function DeleteResource(Request $request)
	{
		$id_delete = $request->input('id_resource');
		DB::table('web_resource')
			->where('id_web_resource', $id_delete)
			->where('id_emp', Config::get('app.main_emp'))
			->delete();

		DB::table('web_resource_banner')
			->where('id_web_resource', $id_delete)
			->delete();

		return response()->json(['status' => 'success', 'message' => 'Resource deleted successfully']);
	}
}
