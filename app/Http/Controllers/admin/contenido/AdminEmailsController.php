<?php

namespace App\Http\Controllers\admin\contenido;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\EmailLib;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FsEmail;
use App\Models\V5\FsEmailTemplate;
use App\Models\V5\FxCli;
use Illuminate\Support\Facades\Config;

class AdminEmailsController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'contenido']);
	}

	public function index(Request $request)
	{
		$emails = FsEmail::where([
			['emp_email', '=', \Config::get("app.main_emp")],
			['enabled_email', '=', 1]
		])->get();

		return view('admin::pages.contenido.emails.index', compact('emails'));
	}

	public function edit($cod_email)
	{
		$cod_email = mb_strtoupper($cod_email);
		$email = FsEmail::where([
			['cod_email', '=', $cod_email],
			['emp_email', '=', \Config::get("app.main_emp")],
		])->first();

		$subject = FormLib::Text('subject', true, $email->subject_email);
		$textarea = FormLib::TextAreaSummer('html', false, $email->body_email, '', '', '100%', 'small');

		//Extraemos los [**] del email para mostrar los disponibles en el editor
		preg_match_all('/\[\*[a-zA-Z0-9_]+\*\]/', $email->body_email, $matches);
		$tags = collect($matches)->flatten()->unique()->implode(', ');

		$emailDesign = new EmailLib($cod_email);
		$emailDesign->test_atributes('');

		$lot = FgAsigl0::select('sub_asigl0', 'ref_asigl0')->orderBy('date_update_asigl0', 'desc')->first();
		$client = FxCli::select('cod_cli')->orderBy('f_alta_cli')->first();

		if($lot){
			$emailDesign->setLot($lot->sub_asigl0, $lot->ref_asigl0);
			$emailDesign->setPropInfo($lot->sub_asigl0, $lot->ref_asigl0);
		}

		$emailDesign->setUserByCod($client->cod_cli);

		$emailDesign->replace();
		$html = $emailDesign->HTML_email;

		return view('admin::pages.contenido.emails.edit', compact('email', 'html', 'subject', 'textarea', 'tags'));
	}

	public function update(Request $request, $cod_email)
	{
		$cod_email = mb_strtoupper($cod_email);
		$email = FsEmail::where([
			['cod_email', '=', $cod_email],
			['emp_email', '=', \Config::get("app.main_emp")],
		])
		->update([
			'body_email' => $request->html,
			'subject_email' => $request->subject,
		]);


		return back()->with('success', 'Email actualizado correctamente');
	}

	private function getDesign($email)
	{
		$template = FsEmailTemplate::where('cod_template', $email->cod_template_email)->first();

		return str_replace('[*CONTENT*]', $email->body_email, $template->design_template);
	}

	public function sendEmail(Request $request)
	{
		$cod_email = mb_strtoupper($request->input('cod_email'));
		$userEmail = $request->input('user_email');

		$emailLib = new EmailLib($cod_email);
		$emailLib->setUserByEmail($userEmail, true);
		$emailLib->send_email();

		return response()->json([
			'status' => 'success',
			'message' => 'Email enviado correctamente'
		]);
	}

}
