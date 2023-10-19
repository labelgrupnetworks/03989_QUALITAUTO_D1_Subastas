<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AdminJobsController extends Controller
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
		$pendigJobs = DB::table('jobs')->get()->each(function ($item) {
			$item->job_name= json_decode($item->payload)->displayName;
			$item->available_at_format = Carbon::createFromTimestamp($item->available_at)->format('d-m-Y H:i:s');
			$item->created_at_format = Carbon::createFromTimestamp($item->created_at)->format('d-m-Y H:i:s');
		});


		$failedJobs = DB::table('failed_jobs')->get()->each(function ($item) {
			$item->job_name= json_decode($item->payload)->displayName;
			$item->failed_at_format = Carbon::createFromFormat('Y-m-d H:i:s', $item->failed_at)->format('d-m-Y H:i:s');
			$item->route_to_reesend = route('admin.jobs.failed_retry', $item->id);
		});

		return view('admin::pages.configuracion.jobs.index', compact('pendigJobs', 'failedJobs'));
	}

	public function showPendingJob($id)
	{
		$pendingJob = DB::table('jobs')->where('id', $id)->first();
		$pendingJob->payload = json_decode($pendingJob->payload);
		return response()->json(['status' => 'success', 'data' => $pendingJob]);
	}

	public function showFailedJob($id)
	{
		$failedJob = DB::table('failed_jobs')->where('id', $id)->first();
		$failedJob->payload = json_decode($failedJob->payload);
		return response()->json(['status' => 'success', 'data' => $failedJob]);
	}

	public function reesendFailedJob($id)
	{
		Artisan::call('queue:retry', ['id' => $id]);
		return response()->json(['status' => 'success', 'message' => 'El trabajo se ha reenviado correctamente']);
	}
}
