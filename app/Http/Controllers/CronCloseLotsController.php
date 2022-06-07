<?php

namespace App\Http\Controllers;

use Request;
use Config;
use View;
use Log;

use App\Models\Subasta;
//2017/10/25 revisar por que esto no parece que se use, se hace una llamada desde  Route::get('/exec_cron', 'CronCloseLotsController@index');

class CronCloseLotsController extends Controller
{
	public function index()
	{
    	$sub = new Subasta();
    	$sub->tipo = "'O'";
        # Obtenemos la información de los lotes listos para cerrar.
    	$lots = $sub->getLotesToClose();
        $lots = $sub->getAllLotesInfo($lots);

        if (empty($lots)) 
            exit;

        Log::info('----- ADJUDICACIONES -----');
        
        foreach ($lots as $key => $lote) {

            $sub->lote = $lote->ref_hces1;
            $sub->cod = $lote->cod_sub;

            # Cierra el lote.
            $sub->cerrarLote();

            # Si no tiene pujas, se considera "lote desierto" y no se envia ningún msg de adjudicación. 
            if (empty($lote->max_puja)){
                Log::info('Mail not sent, lote sin pujas');
                Log::info('Lote: '.$lote->ref_hces1.', Subasta: '.$lote->cod_sub);
                continue;
            }

            # Envia msg de lote adjudicado.
            $this->sendAdjudicationMail($lote);

    	}

    }

    # Envia msg de lote adjudicado.
    private function sendAdjudicationMail($info)
    {
        $emailOptions = array(
            'subject'    => trans(\Config::get('app.theme').'-app.emails.subject-adj').' '.Config::get('app.name'),
            'to'         => $info->max_puja->usrw_cliweb,
            'user'       => $info->max_puja->nom_cliweb,
            'ref_asigl1' => $info->max_puja->ref_asigl1,
            'sub_name'   => $info->des_sub,
            'importe'    => $info->max_puja->formatted_imp_asigl1,
            'lot_name'   => $info->titulo_hces1,
            'img'        => $info->imagen,
            'fecha'      => $info->dfec_sub
        );

        if (\Tools::sendMail('adjudicacion', $emailOptions)) {
            Log::info('Mail sent:');
        }else{
            Log::emergency('Error mail sent:');
        }

        Log::info('Lote: '.$info->max_puja->ref_asigl1.', Subasta: '.$info->max_puja->sub_licit.' ---- Cli:'.$info->max_puja->cli_licit.', Nombre: '.$info->max_puja->nom_cliweb.', Precio: '.$info->max_puja->formatted_imp_asigl1);
        $opt = print_r($emailOptions, true);
        Log::info('Options:'.$opt);

    }
}