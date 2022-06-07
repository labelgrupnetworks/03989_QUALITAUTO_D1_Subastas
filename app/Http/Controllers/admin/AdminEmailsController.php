<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use View;
use Session;
use Redirect;
use Input;
use File;
use Config;

use App\Models\MailQueries;
use App\Models\V5\Web_Email_Logs;

class AdminEmailsController extends Controller
{
    public function index()
    {

        $date = Request::input('date');
        if(empty($date)){
            $date = date('Y/m/j');
        }else{
            $date = date('Y/m/j',strtotime($date));
        }
        $emails = new MailQueries();
        $num_days = 7;
        $logs_emails = $emails->getEmailsLogs($date, $num_days);
        $txtcod = $emails->getTxtcod();
        $emails = array();
        $codigos = array();

        foreach($txtcod as $cod){
           /* if(empty($codigos[$cod->cod_txtcod])){
                $codigos[$cod->cod_txtcod] = array();
            }*/
            $codigos[$cod->cod_txtcod] = $cod;
            $emails[$cod->cod_txtcod] = array();
            for($x=0;$x<7;$x++){
                 $emails[$cod->cod_txtcod][date('d/n/Y',strtotime ( '-'.$x.' day' , strtotime ( $date ) )) ]=0;
            }

        }

        foreach($logs_emails as $logs){
            /*
            if(empty($emails[$logs->codtxt_email_logs])){
                $emails[$logs->codtxt_email_logs] = array();

            }

            if(empty($emails[$logs->codtxt_email_logs][$logs->date_emails])){
                $emails[$logs->codtxt_email_logs][$logs->date_emails] = $logs;
            }
            */
            $emails[$logs->codtxt_email_logs][date('d/n/Y',strtotime ($logs->date_emails))] = $logs->count_emails;
        }


        $data = array(
            'emails' => $emails,
            'codigos'=>$codigos

		);

        return \View::make('admin::pages.emails', array('data' => $data));
	}

	public function showLog(){

		$web_emails_logs = Web_Email_Logs::select('id_email_logs', 'codtxt_email_logs', 'email_email_logs', 'type_email_logs', 'sub_email_logs', 'ref_email_logs', 'numhces_email_logs', 'date_email_logs')
											->where('sended_email_logs', 'S')
											->orderBy('date_email_logs', 'desc')
											->paginate(30);

		return \View::make('admin::pages.emails_logs', compact('web_emails_logs'));

	}


}
