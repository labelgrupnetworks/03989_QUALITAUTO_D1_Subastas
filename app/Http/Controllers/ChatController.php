<?php
namespace App\Http\Controllers;

use Redirect;

//opcional
use App;
use DB;
use Request;
use Validator;
use Illuminate\Support\Facades\Request as Input;
use Session;
use View;
use Routing;
use Config;
use Route;
use Log;
# Cargamos el modelo
use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{
    public $lang;
    public $cod;

    # Devolvemos todos los mensajes de X subasta sergún su idioma
    public function getChat()
    {
    	$chat 		= new Chat();
    	$chat->lang = Route::current()->parameter('lang');
    	$chat->cod  = Route::current()->parameter('cod');

    	return json_encode($chat->getChat());
    }

    # Guardamos un mensaje de chat
    public function setChatArray()
    {
        $cod_sub = Input::get('cod_sub');
        $mensaje = Input::get('mensaje');
        $cod_licit = Input::get('cod_licit');
        $hash_user      = Input::get('hash');


        $gestor = new User();
        $gestor->cod = $cod_sub;
        $gestor->licit = $cod_licit;
        $g = $gestor->getUserByLicit();
         //si no se encuentra el licitador o el licitador no es gestor
        if(count($g) == 0 || $g[0]->tipacceso_cliweb != 'S'){
            \Log::error("error chat $cod_sub  $cod_licit");
            return array(
                'status'            => 'error',
                'msg'               => trans(\Config::get('app.theme').'-app.msg_error.generic')
            );

        }
        $hash = hash_hmac("sha256",$mensaje['ES']['msg']." ".$cod_sub." ". $cod_licit, $g[0]->tk_cliweb);

        if ($hash != $hash_user)
        {
            \Log::error("$hash == $hash_user" );
            return array(
                'status'            => 'error',
                'msg'               => trans(\Config::get('app.theme').'-app.msg_error.generic')
            );
        }


        $primer_item = head($mensaje);

        # Insertamos la cabecera de linea
        $chat                 = new Chat();
        $chat->cod            = $cod_sub;
        $chat->predefinido    = $primer_item['predefinido'];
        $chat->array_mensajes = $mensaje;
        $chat->setChat();

        $data = $chat->getChat();

        # Mensaje al gestor
        return array(
            'status'            => 'success',
            'msg'               => trans(\Config::get('app.theme').'-app.sheet_tr.chat-msg_sent'),
            'mensaje'           => (object) $data['data']
        );

    }

    public function deleteChat()
    {
        $cod_sub = Input::get('cod_sub');
        $id_mensaje = Input::get('id_mensaje');
        $cod_licit = Input::get('cod_licit');
        $hash_user      = Input::get('hash');


        $gestor = new User();
        $gestor->cod = $cod_sub;
        $gestor->licit = $cod_licit;
        $g = $gestor->getUserByLicit();
         //si no se encuentra el licitador o el licitador no es gestor
        if(count($g) == 0 || $g[0]->tipacceso_cliweb != 'S'){
            return array(
                'status'            => 'error',
                'msg'               => trans(\Config::get('app.theme').'-app.msg_error.generic')
            );

        }
        $hash = hash_hmac("sha256",$id_mensaje." ".$cod_sub." ". $cod_licit, $g[0]->tk_cliweb);

        if ($hash != $hash_user)
        {
            \Log::info("$hash == $hash_user" );
            return array(
                'status'            => 'error',
                'msg'               => trans(\Config::get('app.theme').'-app.msg_error.generic')
            );
        }







        $cod_sub = $cod_sub;
        $predefinido = Input::get('predefinido');
        $chat                 = new Chat();
        $chat->cod            = $cod_sub;
        $chat->deleteChat($id_mensaje);

        return array(
            'status'            => 'success',
            'msg'               => trans(\Config::get('app.theme').'-app.sheet_tr.chat-msg_sent'),
            'id_mensaje'              => $id_mensaje,
            'cod_sub'              => $cod_sub,
            'predefinido'              => $predefinido
        );
    }
}
