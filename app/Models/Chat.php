<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;
use Exception;
use Log;
use App\libs\StrLib;
class Chat extends Model
{
    protected $table = 'WEB_CHAT';

    public $lang;
    public $cod;
    public $msg;
    public $predefinido;
    public $array_mensajes;
    public $id;

    public function __construct()
    {
        $this->predefinido  = 1;
    }

    public static function predefinido($predefinido)
    {   
        $tail = false;
        if(!empty($predefinido)) {
            $tail = ' or (wc.predefinido = 1)';
        }

        return $tail;
    }

    public function getChat()
    {   
        $bindings = array(
                    'cod'           => strtoupper($this->cod), 
                    'emp'           => Config::get('app.emp')
                    );

        # En caso de no querer filtrar por idioma
        if(!empty($this->lang)) {
            $lang       = array('lang' => strtoupper($this->lang));
            $bindings   = array_merge($lang, $bindings);
            $lang_tail  = " AND wc.ID_LANG = :lang";
        } else {
            $lang_tail = false;
        }

        try {
            $sql = "SELECT wc.ID_WEB_CHAT, wc.predefinido, wcl.msg, wc.fecha, wcl.id_lang as lang_code FROM WEB_CHAT wc
                                    LEFT JOIN WEB_CHAT_LANG wcl
                                        ON (wcl.ID_WEB_CHAT = wc.ID_WEB_CHAT)
                                    WHERE (
                                            wc.ID_SUB  = :cod

                                        $lang_tail
                                            )
                                        ".self::predefinido($this->predefinido)."
                                        AND wc.ID_EMP  = :emp
                                        ORDER BY wc.FECHA DESC
                                        ";

            $res = DB::select($sql, $bindings);
            $strLib = new StrLib();
           
            $items = array();
            foreach ($res as $key => $value) {
                $value->msg =  $strLib->CleanStr( $value->msg );
                $items[$value->id_web_chat][$value->lang_code] = $value;
                
            }
           
            $result = array(
                        'status'    => 'success',
                        'data'      => $items
                        );

        } catch (Exception $e) {

            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                        'msg'       => trans(\Config::get('app.theme').'-app.sheet_tr.chat-select')
                        );
        }

        return $result;
    }

    public function setChat()
    {
        try  {

            $res = DB::select("INSERT INTO WEB_CHAT (ID_SUB, ID_EMP, PREDEFINIDO, FECHA) VALUES (:cod, :emp, :predefinido, to_char(sysdate, 'yyyy/mm/dd hh24:mi:ss'))",
                    array(
                        'cod'           => strtoupper($this->cod), 
                        'emp'           => Config::get('app.emp'),
                        'predefinido'   => $this->predefinido,
                        )
                );
            
            # Retornamos el ultimo id
            $last_insert_id = DB::select("SELECT MAX(ID_WEB_CHAT) as last_id FROM WEB_CHAT WHERE ID_EMP = :emp AND ID_SUB = :cod",
                                            array(
                                                    'cod'           => strtoupper($this->cod), 
                                                    'emp'           => Config::get('app.emp'),
                                                    )
                                        );

            $id = head($last_insert_id)->last_id;

            # Bucle de lineas de idioma de chat
            foreach ($this->array_mensajes as $k => $item) {

                # Solo insertamos los registros que contengan mensaje
                if(!empty($item['msg'])) {
                    self::setChatLang($id, $k, $item['msg']);
                }

            }

            $result = array(
                        'status'    => 'success',
                        'data'      => $res,
                        'last_id'   => $id
                        );

        } catch (Exception $e) {

            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                        'msg'       => trans(\Config::get('app.theme').'-app.sheet_tr.chat-insert_error')
                        );
        }

        return $result;
    }

    public static function setChatLang($id, $lang, $msg)
    {
          $res = DB::select("INSERT INTO WEB_CHAT_LANG (ID_WEB_CHAT, ID_LANG, MSG) VALUES (:id_mensaje, :lang, :msg)",
                    array(
                        'id_mensaje'    => $id, 
                        'lang'          => strtoupper($lang),
                        'msg'           => $msg, 
                        )
                );

          return $res;
    }

    # Borramos un mensaje y todos sus idiomas
    public function deleteChat($id)
    {   
        try {
            
            $bindings = array(
                        'cod'           => strtoupper($this->cod), 
                        'emp'           => Config::get('app.emp'),
                        'id_mensaje'    => $id
                        );

            $sql = "DELETE FROM WEB_CHAT WHERE ID_WEB_CHAT = :id_mensaje AND ID_EMP = :emp AND ID_SUB = :cod ";
            $res = DB::select($sql, $bindings);
            
            $bindings = array(
                        'id_mensaje'    => $id
                        );

            $sql = "DELETE FROM WEB_CHAT_LANG WHERE ID_WEB_CHAT = :id_mensaje";
            $res = DB::select($sql, $bindings);

            $result = array(
                        'status'    => 'success',
                        'msg'       => trans(\Config::get('app.theme').'-app.sheet_tr.chat-success')
                        );

        } catch (Exception $e) {
            Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                        'msg'       => trans(\Config::get('app.theme').'-app.sheet_tr.chat-delete_error')
                        );
        }

        return $result;
    }

}