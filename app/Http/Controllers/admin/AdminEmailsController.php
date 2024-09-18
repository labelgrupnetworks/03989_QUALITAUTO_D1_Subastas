<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Email_Logs;
use Illuminate\Support\Facades\View;

class AdminEmailsController extends Controller
{
	public function showLog()
	{
		$web_emails_logs = Web_Email_Logs::query()
			->select('id_email_logs', 'codtxt_email_logs', 'email_email_logs', 'type_email_logs', 'sub_email_logs', 'ref_email_logs', 'numhces_email_logs', 'date_email_logs')
			->where('sended_email_logs', 'S')
			->orderBy('date_email_logs', 'desc')
			->paginate(30);

		return View::make('admin::pages.emails_logs', compact('web_emails_logs'));
	}
}
