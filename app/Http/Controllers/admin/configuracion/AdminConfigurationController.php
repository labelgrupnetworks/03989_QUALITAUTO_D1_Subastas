<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Config;

class AdminConfigurationController extends Controller
{
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
		return view('admin::pages.configuracion.configurations.show', [
			'section' => $section,
			'configurations' => $configurations,
		]);
	}
}
