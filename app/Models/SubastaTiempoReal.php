<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use App\Models\Subasta;
use App\Models\V5\AucSessions;

class SubastaTiempoReal extends Model
{
    protected $table = 'FGSUB';

    # Codigo subasta (COD_SUB)
    public $cod;
    #referencia de session
    public $session_reference;


    # Identificador de Lote (REF_ASIGL0 y REF_ASIGL1, REF_ORLIC)
    public $lote;

    # Tipo de Subasta (W=Web tiempo real ordenes hasta fin despues pujas en sala hasta que nadie puje / O=Online tipo ebay/S/I/D=Venta Directa botiga online / P=Permanente nunca se cierra)
    public $tipo;

    # Linea de Categoria
    public $cat;

    # Texto variable
    public $texto;

    # Hoja de cesión
    public $hces;

    # Ref hoja de cesión
    public $ref;

    # Num hoja de cesión
    public $num;

    # Linia hoja de cesión
    public $lin;

    # Controles para paginador de resultados
    public $page;
    public $itemsPerPage;

    public static function getOffset($page, $itemsPerPage)
    {
        $result = FALSE;
        if(empty($page) or $page == 1) {
            $start  = 1;
            $offset = 1;
        } else {
            $start  = $itemsPerPage;

            if($page > 2) {
                $start = (($itemsPerPage * $page) + 1) - $itemsPerPage;
            } else {
                $start = ($itemsPerPage) + 1;
            }

            $offset = 1;
        }

        if($page != 'all') {
            $sql = " WHERE rn BETWEEN ".$start." AND ".( ($start) + ($itemsPerPage - $offset));
            $result = $sql;
        }

        return $result;

    }

    public static function getCat($lin = false)
    {
        $result = false;

        if(!empty($lin) and is_numeric($lin)) {
            $result = " AND cat0.LIN_ORTSEC0 = :cat";
        }

        return $result;
    }

    public function getPujas(){

        $params = array(
            'emp'       => Config::get('app.emp'),
            'cod_sub'   => $this->cod,
            'ref'       =>  $this->ref
        );

        $subasta = DB::select("
                SELECT licitadores.*, pujas1.* FROM FGASIGL1 pujas1
                                    JOIN FGLICIT licitadores
                                        ON (licitadores.COD_LICIT = pujas1.LICIT_ASIGL1 AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod_sub)
                                WHERE
                                    pujas1.SUB_ASIGL1 = :cod_sub
                                    AND pujas1.EMP_ASIGL1 = :emp
                                    AND pujas1.REF_ASIGL1 = :ref
                                ORDER BY pujas1.IMP_ASIGL1 DESC
                                    "
                ,$params
            );

        return $subasta;
    }

    # Cerramos todos los lotes de una subasta ya que ha terminado.
    public function cerrarSubasta($init_lot = NULL, $end_lot = NULL)
    {
        $where = "";
        if ( !empty($init_lot) && !empty($end_lot)  ){
            $where = " AND REF_ASIGL0 >=$init_lot AND REF_ASIGL0 <= $end_lot ";
        }

        $sql = "UPDATE FGASIGL0 SET cerrado_asigl0 = 'S' WHERE EMP_ASIGL0 = :emp AND SUB_ASIGL0 = :cod_sub $where";

        $params = array(
            'emp'       => Config::get('app.emp'),
            'cod_sub'   => $this->cod,
        );

        DB::select($sql, $params);
    }

    # Seteamos el estado de la subasta en modo tiempo real, en caso de que esté pausada y cuando se va a reanudar.
    public function setStatus()
    {
        $sql = "select max(ID_WEB_SUBASTAS) as id from WEB_SUBASTAS";
        $ids = DB::select($sql);
        if (count($ids)>0){
            $id = head($ids)->id + 1;
        }else{
            $id=1;
        }

        $sql = "MERGE INTO WEB_SUBASTAS dest
                USING ( SELECT :cod_sub sub, :emp emp FROM dual) src
                    ON (dest.ID_SUB = :cod_sub and dest.ID_EMP = :emp and dest.session_reference = :session_reference)
                WHEN MATCHED THEN
                    UPDATE SET ESTADO = :estado, REANUDACION = :reanudacion
                WHEN NOT MATCHED THEN
                    INSERT
                    (ID_WEB_SUBASTAS, ID_SUB, ESTADO, REANUDACION, ID_EMP, SESSION_REFERENCE)
                        VALUES
                    (:id, :cod_sub, :estado, :reanudacion, :emp,  :session_reference)
                ";
        $bindings = array(
                    'emp'               => Config::get('app.emp'),
                    'cod_sub'           => $this->cod,
                    'session_reference' => $this->session_reference,
                    'estado'            => $this->estado,
                    'reanudacion'       => $this->reanudacion,
                    'id'                => $id,
                    );

        DB::select($sql, $bindings);

        $result = $this->getStatus();
        /*
        $sql = "SELECT ESTADO, REANUDACION FROM WEB_SUBASTAS WHERE ID_EMP = :emp AND ID_SUB = :cod_sub";
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $this->cod,
                    );

        $result = DB::select($sql, $bindings);
        */
        return $result;

    }

    # Status de una subasta en tiempo real
    public function getStatus()
    {
        $sql = "SELECT ESTADO, ESTADO AS STATUS, REANUDACION,ID_SUB FROM WEB_SUBASTAS WHERE ID_EMP = :emp AND ID_SUB = :cod_sub AND session_reference = :session_reference ";
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $this->cod,
                    'session_reference'    => $this->session_reference,
                    );

        $result = DB::select($sql, $bindings);
        return $result;
    }

    # Update de lotes pausados y establecer el orden de aparición.
    public function setLotStatus($status, $orden = false)
    {
        try {

            $sql = "UPDATE FGASIGL0 SET CERRADO_ASIGL0 = :status WHERE EMP_ASIGL0 = :emp AND SUB_ASIGL0 = :cod_sub AND REF_ASIGL0 = :ref";
            $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        'status'        => $status,
                        );

            DB::select($sql, $bindings);

            $data = array(
                        'ref'            => $this->ref,
                        'cerrado_asigl0' => $status
                        );

            if($orden) {
                $data['orden'] = $orden;
            }

            # Controlamos el mensaje de retorno al usuario
            if($status == 'P') {
                $message = trans(\Config::get('app.theme').'-app.msg_success.pause_lot');
            } else {
                $message = trans(\Config::get('app.theme').'-app.msg_success.resume_lot');
            }

            $result = array(
                    'status'     => 'success',
                    'msg'        => $message,
                    'data'       => $data,
                    );

        } catch (\Exception $e) {
            \Log::error(__FILE__.' ::'. $e);

            $result = array(
                        'status'    => 'error',
                        'msg'       => trans(\Config::get('app.theme').'-app.msg_errors.pause_lot')
                        );
        }

        return $result;
    }

    public function getOrderFromRef($ref)
    {
        # Primero debemos consultar el destino para saber si existe.
        $sql = "SELECT * FROM FGHCES1 WHERE EMP_HCES1 = :emp AND SUB_HCES1 = :cod_sub AND REF_HCES1 = :ref";
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $this->cod,
                    'ref'           => $ref,
                    );

        return head(DB::select($sql, $bindings));
    }

    # Establecemos el orden del lote, en caso de cambiar el orden de aparición de un lote
    # Debemos pausar el lote primero
    # Para establecer un orden es siempre al darle a reanudar que pedirá en que posición lo volverá a mostrar
    public function setLotOrder($ref)
    {
        # Primero debemos consultar el destino para saber si existe.
        $sql = "SELECT * FROM FGHCES1 WHERE EMP_HCES1 = :emp AND SUB_HCES1 = :cod_sub AND REF_HCES1 = :ref";
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $this->cod,
                    'ref'           => $ref,
                    );

        $destino = head(DB::select($sql, $bindings));

        # Si encontramos destino podemos empezar a mover el lote
        if($destino) {

            # Consultamos el orden actual del lote que queremos mover
            $sql = "SELECT * FROM FGHCES1 WHERE EMP_HCES1 = :emp AND SUB_HCES1 = :cod_sub AND REF_HCES1 = :ref";
            $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        );
            $origen = head(DB::select($sql, $bindings));

        }

        if($destino->orden_hces1 > $origen->orden_hces1) {
            $calc       = ($destino->orden_hces1 - $origen->orden_hces1);
            $operador1  = '+';
            $operador2  = '- 1';
        }

        if($destino->orden_hces1 < $origen->orden_hces1) {
            $calc       = ($origen->orden_hces1 - $destino->orden_hces1);
            $operador1  = '-';
            $operador2  = '+ 1';
        }

        //echo "Origen: ".$origen->orden_hces1."<br/>";
        //echo "Destino: ".$destino->orden_hces1."<br/>";
        //echo "vueltas: ".$calc."<br/>";

        if($origen->orden_hces1 != $destino->orden_hces1) {
            # Lotes que se veran afectados con el cambio de orden.
            for ($x=0; $x<=$calc; $x++) {

                if($x > 0) {

                    $valOrigen  = eval("return $origen->orden_hces1 $operador1 $x;");
                    $valDestino = eval("return ($origen->orden_hces1 $operador1 $x) $operador2;");

                    $sql  = "UPDATE FGHCES1 SET ORDEN_HCES1 = $valDestino WHERE EMP_HCES1 = '".Config::get('app.emp')."' AND SUB_HCES1 = '".$origen->sub_hces1."' AND ORDEN_HCES1 = $valOrigen";
                    DB::select($sql);

                    /*
                    echo "Update: ".$valOrigen. " to ".$valDestino." ";
                    echo $sql;
                    echo "<br />";
                    */

               }

            }
        }



        # Update de Orden
        $sql = "UPDATE FGHCES1 SET ORDEN_HCES1 = :orden WHERE EMP_HCES1 = :emp AND SUB_HCES1 = :cod_sub AND REF_HCES1 = :ref";
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $this->cod,
                    'ref'           => $this->ref,
                    'orden'         => $destino->orden_hces1,
                    );

        DB::select($sql, $bindings);

        return $destino->orden_hces1;
    }



    public function setLicitLot()
    {
        $sql = "UPDATE FGASIGL1 SET LICIT_ASIGL1 = :licit
        WHERE SUB_ASIGL1    = :cod_sub
        AND REF_ASIGL1      = :ref
        AND LICIT_ASIGL1    = :dummy_bidder
        AND IMP_ASIGL1      = (SELECT MAX(IMP_ASIGL1) FROM FGASIGL1 WHERE  EMP_ASIGL1=:emp AND  SUB_ASIGL1 = :cod_sub AND REF_ASIGL1 = :ref AND LICIT_ASIGL1 = :dummy_bidder)
        AND EMP_ASIGL1      = :emp";
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $this->cod,
                    'ref'           => $this->ref,
                    'dummy_bidder'  => Config::get('app.dummy_bidder'),
                    'licit'         => $this->licit,
                    );
        DB::select($sql, $bindings);
    }

     public function openLot($fromJump = false)
    {

         if(!empty($this->cod) && !empty($this->ref)) {
            $sql="select ABRIRLOTE(:emp,:cod_sub ,:ref)  as abrir_lote from dual";

            $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        );

            $abrirlote=  DB::select($sql, $bindings);
            //si devuelve 0 es que lo ha`podido abrir
            if(count($abrirlote) > 0 && $abrirlote[0]->abrir_lote == '0'){
                return true;
            }
			// si el resultado es 3 es que el lote ya estaba abierto, si estamos saltando en el tiempo real debemos seguir con el proceso
			elseif($fromJump && count($abrirlote) > 0 && $abrirlote[0]->abrir_lote == '3'){
				return true;
			}
			else{
                if(count($abrirlote) > 0){
                    \Log::info("No se ha podido abrir el lote ".$this->cod. " " . $this->ref ." respuesta: ". $abrirlote[0]->abrir_lote);
                }
                return false;
            }

         }

    }

     public function moveLots($origen,$destino){

         if($origen > $destino){
             $sql = "UPDATE FGHCES1 SET ORDEN_HCES1 = ORDEN_HCES1 +1 WHERE EMP_HCES1 = :emp AND SUB_HCES1 = :cod_sub and ORDEN_HCES1 >= :destino and ORDEN_HCES1 < :origen";
         }elseif($destino>$origen){

            $sql = "UPDATE FGHCES1 SET ORDEN_HCES1 = ORDEN_HCES1 -1 WHERE EMP_HCES1 = :emp AND SUB_HCES1 = :cod_sub and ORDEN_HCES1 > :origen and ORDEN_HCES1 <= :destino";
         }else{
             return true;
         }


        $bindings = array(
					'destino' => $destino,
					'origen' => $origen,
                    'cod_sub'       => $this->cod,
                    'emp'           => Config::get('app.emp'),

                    );

        DB::select($sql, $bindings);

        $sql = "UPDATE FGHCES1 SET ORDEN_HCES1 = $destino WHERE EMP_HCES1 = :emp AND SUB_HCES1 = :cod_sub AND REF_HCES1 = :ref AND NUM_HCES1 = :num AND LIN_HCES1 = :lin";
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $this->cod,
                    'ref'           => $this->ref,
                    'num'       => $this->num,
                    'lin'           => $this->lin,
                    );

        DB::select($sql, $bindings);

        return true;
    }

    public function deletBids(){

        try {
            //Guardamos en Log las pujas eliminadas por seguridad
            $sql = "Select * FROM fgasigl1 where emp_asigl1 =:emp and  sub_asigl1 = :cod_sub and ref_asigl1= :ref ";
            $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,

                        );
            $pujas=  DB::select($sql, $bindings);
            \Log::emergency('Delete pujas '.$this->cod.'-'.$this->ref);
            \Log::emergency( print_r($pujas, TRUE) );

            //Eliminamos pujas
            $sql = "DELETE FROM fgasigl1 where emp_asigl1 =:emp and  sub_asigl1 = :cod_sub and ref_asigl1= :ref ";
            $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,

                        );
            DB::select($sql, $bindings);

            return true;
       } catch (\Exception $e) {
            \Log::emergency('Error delete pujas '.$this->cod.'-'.$this->ref);
            return false;
        }
    }

    //Los lotes entre dos posicones de orden queremos ponerlos a J
    public function saltarLotes($first,$last){

       try {
            $sql1 ="
            SELECT REF_HCES1
            FROM FGHCES1
            INNER JOIN FGASIGL0 ASIGL0 ON (EMP_HCES1 = :emp AND NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0  AND LIN_HCES1 = ASIGL0.LINHCES_ASIGL0 AND ASIGL0.SUB_ASIGL0 = SUB_HCES1)
            INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = SUB_HCES1 AND AUC.\"company\" = ASIGL0.EMP_ASIGL0
            WHERE EMP_HCES1 = :emp
            AND SUB_HCES1 = :cod_sub
            AND ORDEN_HCES1  BETWEEN :first AND  :last
            AND
                    ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\"
            AND
                    ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\" ";

        $sql = "UPDATE FGASIGL0
                SET CERRADO_ASIGL0 = :change_cerrado
                WHERE EMP_ASIGL0 = :emp
                AND SUB_ASIGL0 = :cod_sub
                AND CERRADO_ASIGL0 = :cerr_asigl0
                AND REF_ASIGL0 IN ( $sql1 )";



            $bindings = array(
                'emp'           => Config::get('app.emp'),
                'cod_sub'       => $this->cod,
                'first'           => $first,
                'last'           => $last,
                'cerr_asigl0'    => 'N',
                'change_cerrado' => 'J'

                );


          DB::select($sql, $bindings);
          return true;
         } catch (\Exception $e) {
            \Log::emergency('Error saltar lotes'.$this->cod.'- FIRST: '.$first.'- LAST: '.$last);
            return false;
        }
    }

    //Los lotes que empiezan desde el lote que mandamos hasta el final de la session y su ORDEN_HCES1 = J los pondremos a N
    public function reloadSaltarLotes($first){
        try {
            $sql1 ="
            SELECT REF_HCES1
            FROM FGHCES1
            INNER JOIN FGASIGL0 ASIGL0 ON (EMP_HCES1 = :emp AND NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0  AND LIN_HCES1 = ASIGL0.LINHCES_ASIGL0 AND ASIGL0.SUB_ASIGL0 = SUB_HCES1)
            INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = SUB_HCES1 AND AUC.\"company\" = ASIGL0.EMP_ASIGL0
            WHERE EMP_HCES1 = :emp
            AND SUB_HCES1 = :cod_sub
            AND ORDEN_HCES1  >= :first
            AND
                    ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\"
            AND
                    ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\" ";

        $sql = "UPDATE FGASIGL0
                SET CERRADO_ASIGL0 = :change_cerrado
                WHERE EMP_ASIGL0 = :emp
                AND SUB_ASIGL0 = :cod_sub
                AND CERRADO_ASIGL0 = :cerr_asigl0
                AND REF_ASIGL0 IN ( $sql1 )";



            $bindings = array(
                'emp'           => Config::get('app.emp'),
                'cod_sub'       => $this->cod,
                'first'           => $first,
                'cerr_asigl0'    => 'J',
                'change_cerrado' => 'N'

                );

           DB::select($sql, $bindings);
           return true;
         } catch (\Exception $e) {
            \Log::emergency('Error saltar lotes'.$this->cod.'- FIRST: '.$first);
            return false;
        }
    }

    public function changeStatusLot($status, $orden = false){
		\Log::info('Change status lot. codSub:'.$this->cod."  Ref:".$this->ref."  newStatus:". $status );

        try {
            $sql = "UPDATE FGASIGL0 SET CERRADO_ASIGL0 = :status WHERE EMP_ASIGL0 = :emp AND SUB_ASIGL0 = :cod_sub AND REF_ASIGL0 = :ref";
            $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'ref'           => $this->ref,
                        'status'        => $status,
                        );

            DB::select($sql, $bindings);

            # Controlamos el mensaje de retorno al usuario
            if($status == 'P') {
                $message = 'pause_lot';
            } else {
                $message = 'resume_lot';
            }

            $data = array(
                    'ref'            => $this->ref,
                    'cerrado_asigl0' => $status
                    );

            if($orden) {
                $data['orden'] = $orden;
            }

            $result = array(
                    'status'     => 'success',
                    'msg'        => $message,
                    'data'       => $data,
                    );


            } catch (\Exception $e) {
                \Log::error(__FILE__.' ::'. $e);

                $result = array(
                            'status'    => 'error',
                            'msg'       => 'pause_lot'
                            );
        }

        return $result;
    }

	public function getStatusSessions()
	{
		# Obtiene las sessiones de la subasta
		$sessions = AucSessions::select('ESTADO, REANUDACION, "auction"')
		->leftJoinWebSubastas()
		->where('"auction"', $this->cod)->get();

		# Comprueba que todas las sessiones esten en estado ended
		$ended = $sessions->every(function($session) {
			# Comprueba que la session este en estado ended
			return $session->estado == "ended";
		});

		return $ended;
	}


}

