<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminLogginController extends Controller
{
	public function index(Request $request)
	{
		$userEmail = Session::get('user.usrw');
		if($userEmail !== 'subastas@labelgrup.com') {
			abort(404);
		}

		$query = $request->query('date');
		$today = $query ?? today()->format('Y-m-d');
		$file = storage_path("logs/laravel-$today.log");
		$filePath = str_replace("\\", "/", $file);

		if(!file_exists($filePath)){
			abort(404);
		}

		return response()->download($filePath);
	}
}
