<?php

namespace App\Http\Controllers;

use App\Models\Cookies;
use Illuminate\Http\Request;

class CookiesController extends Controller
{
	public function acceptAllCookies()
	{
		(new Cookies)->setAllPermissions();
		return response()->json([
			'success' => true
		]);
	}

	public function rejectAllCookies()
	{
		(new Cookies)->removeAllPermissions();
		return response()->json([
			'success' => true
		]);
	}

	public function setPreferencesCookies(Request $request)
	{
		$preferences = $request->get('preferences');
		(new Cookies)->setPermissions($preferences);
		return response()->json([
			'success' => true
		]);
	}

	public function addConfigurationsCookies(Request $request)
	{
		$configurations = $request->get('configurations');
		(new Cookies)->addConfigurations($configurations);
		return response()->json([
			'success' => true
		]);
	}
}
