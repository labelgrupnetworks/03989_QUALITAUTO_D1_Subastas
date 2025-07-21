<?php

namespace App\Http\Controllers;

use App\Services\Content\CookieService;
use Illuminate\Http\Request;

class CookiesController extends Controller
{
	private $cookieService;

	public function __construct()
	{
		$this->cookieService = new CookieService();
	}

	public function acceptAllCookies()
	{
		$this->cookieService->setAllPermissions();
		return response()->json([
			'success' => true
		]);
	}

	public function rejectAllCookies()
	{
		$this->cookieService->removeAllPermissions();
		return response()->json([
			'success' => true
		]);
	}

	public function setPreferencesCookies(Request $request)
	{
		$preferences = $request->get('preferences');
		$this->cookieService->setPermissions($preferences);
		return response()->json([
			'success' => true
		]);
	}

	public function addConfigurationsCookies(Request $request)
	{
		$configurations = $request->get('configurations');
		$this->cookieService->addConfigurations($configurations);
		return response()->json([
			'success' => true
		]);
	}
}
