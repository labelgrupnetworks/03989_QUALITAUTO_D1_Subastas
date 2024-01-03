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
			'clear_cache' => 'Limpiar cache',
			'clear_config' => 'Limpiar configuración',
			'clear_route' => 'Limpiar rutas',
			'clear_view' => 'Limpiar vistas',
			'clear_all' => 'Limpiar todo',
			'clear_optimize' => 'Limpiar optimización',
			'optimize' => 'Optimizar',
			'cache_status' => 'Estado de la cache',
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
			'default' => response()->json(['status' => 'error', 'message' => 'No se ha encontrado la acción']),
		};
	}

	public function clearCache()
	{
		Artisan::call('cache:clear');
		return response()->json(['status' => 'success', 'message' => 'La cache se ha limpiado correctamente']);
	}

	public function clearConfig()
	{
		Artisan::call('config:clear');
		return response()->json(['status' => 'success', 'message' => 'La configuración se ha limpiado correctamente']);
	}

	public function clearRoute()
	{
		Artisan::call('route:clear');
		return response()->json(['status' => 'success', 'message' => 'Las rutas se han limpiado correctamente']);
	}

	public function clearView()
	{
		Artisan::call('view:clear');
		return response()->json(['status' => 'success', 'message' => 'Las vistas se han limpiado correctamente']);
	}

	public function clearAll()
	{
		Artisan::call('cache:clear');
		Artisan::call('config:clear');
		Artisan::call('route:clear');
		Artisan::call('view:clear');
		return response()->json(['status' => 'success', 'message' => 'Se ha limpiado todo correctamente']);
	}

	public function clearOptimize()
	{
		Artisan::call('optimize:clear');
		return response()->json(['status' => 'success', 'message' => 'Se ha limpiado la optimización correctamente']);
	}

	public function optimize()
	{
		Artisan::call('optimize');
		return response()->json(['status' => 'success', 'message' => 'Se ha optimizado correctamente']);
	}
}
