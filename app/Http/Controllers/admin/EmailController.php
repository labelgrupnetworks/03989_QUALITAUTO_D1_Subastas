<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\libs\EmailLib;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
	public function index()
	{
		$data = DB::table('FSEMAIL')
			->where([
				['emp_email', Config::get('app.main_emp')],
				['enabled_email', '1'],
			])->get();


		return view('admin::pages.email', array('data' => $data));
	}

	public function getEmail($cod_email)
	{
		$url = url('/');
		$email = new EmailLib($cod_email);
		$email->test_design($url, $cod_email);
	}
}
