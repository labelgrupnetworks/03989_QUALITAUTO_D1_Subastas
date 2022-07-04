<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Amedida
 *
 * @author LABEL-RSANCHEZ
 */

use Illuminate\Database\Eloquent\Model;
use DB;

use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;
use Routing;
use App\Models\Content;
class Amedida {
    //put your code here
    public static function indice($cod_sub, $id_aucsession)
    {
         # Parametros a parsear en el SQL con PDO
        $params = array(
                'emp'       =>  Config::get('app.emp'),
                'cod_sub'   =>  $cod_sub,
                'id_aucsession'   =>  $id_aucsession,
                );
        $sql="select * from FGSUBIND  SUBIND
join \"auc_sessions\"  ON \"auc_sessions\".\"company\" = SUBIND.EMP_SUBIND AND \"auc_sessions\".\"auction\" =  SUBIND.SUB_SUBIND
 AND \"auc_sessions\".\"reference\" = SUBIND.SESION_SUBIND

where EMP_SUBIND = :emp AND   \"auc_sessions\".\"id_auc_sessions\"= :id_aucsession AND  SUB_SUBIND = :cod_sub ORDER BY ORDEN_SUBIND";
         //ahora no va por id_auc_session directamente
        // $sql ="select * from FGSUBIND  where EMP_SUBIND = :emp AND   SESION_SUBIND= :id_aucsession AND  SUB_SUBIND = :cod_sub ORDER BY DREF_SUBIND,NIVEL_SUBIND,LIN_SUBIND";

          $sesiones = DB::select($sql, $params);

        return $sesiones;
    }

}
