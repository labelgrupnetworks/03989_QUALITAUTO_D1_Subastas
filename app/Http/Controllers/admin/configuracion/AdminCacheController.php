<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminCacheController extends Controller
{
	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			if (strtoupper(session('user.usrw')) != 'SUBASTAS@LABELGRUP.COM') {
				abort(403, 'No tienes permisos para acceder a esta página');
			}
			return $next($request);
		});

		view()->share(['menu' => 'configuracion_admin']);
	}

	public function index()
	{
		$actions = [
			$this->newAction('clear_cache', 'Limpiar cache', 'Elimina todos los archivos de la cache'),
			$this->newAction('clear_config', 'Limpiar configuración', 'Elimina todos los archivos de configuración cacheados'),
			$this->newAction('clear_route', 'Limpiar rutas', 'Elimina todos los archivos de rutas cacheados'),
			$this->newAction('clear_view', 'Limpiar vistas', 'Elimina todos los archivos de vistas cacheados'),
			$this->newAction('clear_all', 'Limpiar todo', 'Elimina todos los archivos de cache, configuración, rutas y vistas'),
			$this->newAction('clear_optimize', 'Limpiar optimización', 'Elimina todos los archivos de optimización'),
			$this->newAction('optimize', 'Optimizar', 'Optimiza la configuración, las rutas y las clases'),
			$this->newAction('cache_routes', 'Cachear rutas', 'Cachear rutas'),
			$this->newAction('storage_link', 'Crear enlace de almacenamiento', 'Crea un enlace simbólico desde "public/storage" a "storage/app/public"'),
		];

		return view('admin::pages.configuracion.cache.index', compact('actions'));
	}

	public function action(Request $request)
	{
		$action = $request->input('action');
		return match ($action) {
			'clear_cache' => $this->clearCache(),
			'clear_config' => $this->clearConfig(),
			'clear_route' => $this->clearRoute(),
			'clear_view' => $this->clearView(),
			'clear_all' => $this->clearAll(),
			'optimize' => $this->optimize(),
			'clear_optimize' => $this->clearOptimize(),
			'cache_routes' => $this->cacheRoutes(),
			'storage_link' => $this->storageLink(),
			'default' => response()->json(['status' => 'error', 'message' => 'No se ha encontrado la acción']),
		};
	}

	private function newAction($action, $title, $description)
	{
		return (object) [
			'action' => $action,
			'title' => $title,
			'description' => $description
		];
	}

	private function clearCache()
	{
		Artisan::call('cache:clear');
		return response()->json(['status' => 'success', 'message' => 'La cache se ha limpiado correctamente']);
	}

	private function clearConfig()
	{
		Artisan::call('config:clear');
		return response()->json(['status' => 'success', 'message' => 'La configuración se ha limpiado correctamente']);
	}

	private function clearRoute()
	{
		Artisan::call('route:clear');
		return response()->json(['status' => 'success', 'message' => 'Las rutas se han limpiado correctamente']);
	}

	private function clearView()
	{
		Artisan::call('view:clear');
		return response()->json(['status' => 'success', 'message' => 'Las vistas se han limpiado correctamente']);
	}

	private function clearAll()
	{
		Artisan::call('cache:clear');
		Artisan::call('config:clear');
		Artisan::call('route:clear');
		Artisan::call('view:clear');
		return response()->json(['status' => 'success', 'message' => 'Se ha limpiado todo correctamente']);
	}

	private function clearOptimize()
	{
		Artisan::call('optimize:clear');
		return response()->json(['status' => 'success', 'message' => 'Se ha limpiado la optimización correctamente']);
	}

	private function optimize()
	{
		Artisan::call('optimize');
		return response()->json(['status' => 'success', 'message' => 'Se ha optimizado correctamente la configuración, las rutas y las clases']);
	}

	private function cacheRoutes()
	{
		Artisan::call('route:cache');
		return response()->json(['status' => 'success', 'message' => 'Se han cacheado las rutas correctamente']);
	}

	private function storageLink()
	{
		//crear directorio storage en public si no existe
		if (!file_exists(public_path('storage'))) {
			mkdir(public_path('storage'));
		}

		Artisan::call('storage:link');
		return response()->json(['status' => 'success', 'message' => 'Se ha creado el enlace de almacenamiento correctamente']);
	}
}
