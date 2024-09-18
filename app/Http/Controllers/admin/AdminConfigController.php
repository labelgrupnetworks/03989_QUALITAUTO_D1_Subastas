<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminConfigController extends Controller
{
	public function saveConfigurationSession(Request $request)
	{
		$request->session()->put('admin.navigator_collapse', $request->get('navigatorIsCollapse', false));
		return response(['status' => 'success']);
	}
}
