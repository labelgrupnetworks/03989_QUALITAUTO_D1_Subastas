<?php

namespace App\libs;
use Config;

/*

    USO:

    Controller:
        En el controller a placer:  MessageLib::errorMessage(...);
    
    Frontend javascript:
        Así mostrariamos un mensaje:
        showMessage(data);     --  donde data es el objeto que devolvemos

*/


class MessageLib {
    
   
    #
    #   Función para mostrar mensajes de error
    #

   static function errorMessage($message = 'Ha ocurrido un error') {

       return MessageLib::Message($message,"error");

    }

    #
    #   Función para mostrar mensajes de éxito
    #

    static function successMessage($message = '') {

       return MessageLib::Message($message,"success");

    }

    #
    #   Función para mostrar mensajes de información
    #

    static function neutralMessage($message = '') {

       return MessageLib::Message($message,"neutral");

    }


    #
    #   Función para mostrar los mensajes, sean del tipo que sean
    #

    static function Message($message = '', $status = 'success') {


        $info = array(
            'status' => $status,
            'message' => $message
        );

        return $info;

    }
    
    
}
