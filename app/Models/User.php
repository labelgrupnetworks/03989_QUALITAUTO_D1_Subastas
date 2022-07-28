<?php

# Ubicacion del modelo
namespace App\Models;

use App\Models\V5\FgHces1;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;
use \Request;
use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;

class User extends Model
{
    protected $table = 'FXCLIWEB';

    public $user;
    public $password;

    # Nom client
    public $nom;
    public $rsoc;
    public $dir;
    public $dir2;
    public $cp;
    public $pob;
    public $pro;
    public $tel;
    public $pwd;
    public $pwd_encrypt;
    public $email;
    public $pri_emp;
    public $nif;
    public $nombre_pais;
    public $pais;
    public $cod_cli;
    public $language;
    public $page;
    public $itemsPerPage;
    public $via;
    public $iva_cli;
    public $divisa;
	public $cod_sub;
	public $seudo_cli;

    public function login()
    {
        //devolvemos el usuario siempre que no sea baja temporal (BAJA_TMP_CLI = 'N')
          return head(DB::select("SELECT c.baja_tmp_cli,cw.* FROM FXCLIWEB cw
                     JOIN FXCLI c
                                ON (c.COD_CLI = cw.COD_CLIWEB and c.GEMP_CLI = cw.GEMP_CLIWEB)
                            WHERE
                            lower(USRW_CLIWEB) = :usr
                            AND cw.PWDW_CLIWEB= :pass
                             AND cw.EMP_CLIWEB = :emp AND cw.GEMP_CLIWEB = :gemp and c.BAJA_TMP_CLI = :baja_tmp",
                            array(
                                    'usr'   => strtolower($this->user),
                                    'emp'       =>Config::get('app.emp'),
                                    'gemp'       =>Config::get('app.gemp'),
                                    'pass'       =>$this->password,
                                    'baja_tmp'  => 'N'
                                )
            ));


        /* Bug de yajra OCI (v5.2.1 / v5.2.3) no van las nomenclaturas en los bindings */
       /* return User::whereRaw("(lower(USRW_CLIWEB) = ? OR lower(USRW_CLIWEB) = ?) AND EMP_CLIWEB = ? AND GEMP_CLIWEB = ? AND PWDW_CLIWEB = ?",
                array(
                        strtolower($this->user),
                        strtoupper($this->user),
                        Config::get('app.emp'),
                        Config::get('app.gemp'),
                        $this->password
                    )
            )->first();

        */
    }
     public function login_encrypt()
    {

        //devolvemos el usuario siempre que no sea baja temporal (BAJA_TMP_CLI = 'N')
         return head(DB::select("SELECT c.baja_tmp_cli,c.cod_div_cli,c.nom_cli,cw.* FROM FXCLIWEB cw
                     JOIN FXCLI c
                                ON (c.COD_CLI = cw.COD_CLIWEB and c.GEMP_CLI = cw.GEMP_CLIWEB)
                            WHERE
                            lower(USRW_CLIWEB) = LOWER(:usr)
                            AND trim(cw.PWDWENCRYPT_CLIWEB)= :pass
                             AND cw.EMP_CLIWEB = :emp AND cw.GEMP_CLIWEB = :gemp and c.BAJA_TMP_CLI = :baja_tmp",
                            array(
                                    'usr'   => strtolower($this->user),
                                    'emp'       =>Config::get('app.emp'),
                                    'gemp'       =>Config::get('app.gemp'),
                                    'pass'       =>$this->password,
                                    'baja_tmp'  => 'N'
                                )
            ));
         /*$query = "SELECT c.baja_tmp_cli,cw.* FROM FXCLIWEB cw
                     JOIN FXCLI c
                                ON (c.COD_CLI = cw.COD_CLIWEB and c.GEMP_CLI = cw.GEMP_CLIWEB)
                            WHERE
                            lower(USRW_CLIWEB) = :usr
                            AND trim(cw.PWDWENCRYPT_CLIWEB)= :pass
                             AND cw.EMP_CLIWEB = :emp AND cw.GEMP_CLIWEB = :gemp and c.BAJA_TMP_CLI = :baja_tmp";
         $bindings

         return head(DB::select($query,$bindings));
         */


        /* Bug de yajra OCI (v5.2.1 / v5.2.3) no van las nomenclaturas en los bindings */
       /* return User::whereRaw("(lower(USRW_CLIWEB) = ? OR lower(USRW_CLIWEB) = ?) AND EMP_CLIWEB = ? AND GEMP_CLIWEB = ? AND PWDW_CLIWEB = ?",
                array(
                        strtolower($this->user),
                        strtoupper($this->user),
                        Config::get('app.emp'),
                        Config::get('app.gemp'),
                        $this->password
                    )
            )->first();

        */
    }

    public function getUser()
    {
        return head(DB::select("SELECT * FROM FXCLIWEB cw
                            LEFT JOIN FXCLI c
                                ON (c.COD_CLI = cw.COD_CLIWEB and c.GEMP_CLI = cw.GEMP_CLIWEB)
                            LEFT JOIN FXCLID
                                ON (c.COD_CLI = FXCLID.CLI_CLID and c.GEMP_CLI = FXCLID.GEMP_CLID)
                            WHERE
                            cw.COD_CLIWEB = :cod_cli AND cw.EMP_CLIWEB = :emp AND cw.GEMP_CLIWEB = :gemp",
                            array(
                                    'cod_cli'   => $this->cod_cli,
                                    'emp'       => Config::get('app.emp'),
                                    'gemp'      => Config::get('app.gemp'),
                                )
            ));


    }

    public function getUserByEmail($has_cod_cli = true)
    {
      $filtro_cli="";
      if($has_cod_cli)
      {
        $filtro_cli ="AND cw.COD_CLIWEB > '0'";
	  }

        return DB::select("SELECT * FROM FXCLIWEB cw
                            LEFT JOIN FXCLI c
                                ON (c.COD_CLI = cw.COD_CLIWEB and c.GEMP_CLI = cw.GEMP_CLIWEB)
                            WHERE
                            ( LOWER(cw.EMAIL_CLIWEB) = LOWER(:email_low))
                            AND cw.EMP_CLIWEB = :emp
                            AND cw.GEMP_CLIWEB = :gemp"." ".$filtro_cli,
                            array(
                                    //'email'     => $this->email,
                                    'email_low' => $this->email,
                                    'emp'       => Config::get('app.emp'),
                                    'gemp'      => Config::get('app.gemp')
                                )
            );
	}
	public function getUserByLogin($has_cod_cli = true)
    {
      $filtro_cli="";
      if($has_cod_cli)
      {
        $filtro_cli ="AND cw.COD_CLIWEB > '0'";
	  }

        return DB::select("SELECT * FROM FXCLIWEB cw
                            LEFT JOIN FXCLI c
                                ON (c.COD_CLI = cw.COD_CLIWEB and c.GEMP_CLI = cw.GEMP_CLIWEB)
                            WHERE
                            ( LOWER(cw.USRW_CLIWEB) = LOWER(:email_low))
                            AND cw.EMP_CLIWEB = :emp
                            AND cw.GEMP_CLIWEB = :gemp"." ".$filtro_cli,
                            array(
                                    //'email'     => $this->email,
                                    'email_low' => $this->email,
                                    'emp'       => Config::get('app.emp'),
                                    'gemp'      => Config::get('app.gemp')
                                )
            );
    }


     public function getUserByNif($baja_temp)
    {

        return DB::select("SELECT * FROM FXCLI cl
                            WHERE
                            upper(cl.CIF_CLI) = :nif
                            AND
                            cl.GEMP_CLI = :gemp
                            and
                            cl.BAJA_TMP_CLI = :baja_temp
                            ",
                            array(
                                    'nif'       => mb_strtoupper($this->nif),
                                    'gemp'      =>  Config::get('app.gemp'),
                                    'baja_temp'      =>  $baja_temp
                                )
            );
    }

    public function getUserByCodCli($baja_temp = 'N')
    {

        return DB::select("SELECT * FROM FXCLI cl
                            WHERE
                            cl.cod_cli = :cod_cli
                            AND
                            cl.GEMP_CLI = :gemp
                            and
                            cl.BAJA_TMP_CLI = :baja_temp
                            ",
                            array(
                                    'cod_cli'       => $this->cod_cli,
                                    'gemp'      =>  Config::get('app.gemp'),
                                    'baja_temp'      =>  $baja_temp
                                )
            );
    }

    public function setUserPassword($password_encrypt){
        $bindings = array(
                            'emp'           => Config::get('app.emp'),
                            'gemp'          => Config::get('app.gemp'),
                            'password_encrypt'      => $password_encrypt,
                            //'email'         => $this->email,
                            'email_low' => strtolower($this->email),
                            'email_upp' => strtoupper($this->email)
                            );

            $sql = "UPDATE FXCLIWEB SET PWDWENCRYPT_CLIWEB = :password_encrypt WHERE EMP_CLIWEB = :emp AND GEMP_CLIWEB = :gemp AND (EMAIL_CLIWEB = :email_upp OR EMAIL_CLIWEB = :email_low)";

            DB::select($sql, $bindings);
    }

    # Todas las pujas de un usuario
    public function getPujas()
    {


        /*
        |--------------------------------------------------------------------------
        | Pujas de una subasta
        |--------------------------------------------------------------------------
        */
        # Consulta mediante identificador de Subasta
        if($this->cod and !$this->lote) {
        	return DB::select("SELECT DISTINCT(licitadores.COD_LICIT), licitadores.*, pujas1.*, pujas0.*, usuarios.* FROM FGASIGL0 pujas0
                                    LEFT JOIN FGASIGL1 pujas1
                                        ON (pujas1.SUB_ASIGL1 = pujas0.SUB_ASIGL0 AND pujas1.EMP_ASIGL1 = :emp AND pujas1.SUB_ASIGL1 = :cod AND pujas1.REF_ASIGL1 = pujas0.REF_ASIGL0)
                                    LEFT JOIN FGLICIT licitadores
                                        ON (licitadores.COD_LICIT = pujas1.LICIT_ASIGL1 AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod)
                                    LEFT JOIN FXCLIWEB usuarios
                                        ON (usuarios.COD_CLIWEB = licitadores.CLI_LICIT AND usuarios.EMP_CLIWEB = :emp)
                                WHERE
                                    pujas0.SUB_ASIGL0 = :cod
                                    AND pujas0.EMP_ASIGL0 = :emp
                                    AND usuarios.COD_CLIWEB = :cod_cliweb
                                    ",
                                    array(
                                        'cod'           => strtoupper($this->cod),
                                        'cod_cliweb'    => $this->cod_cli,
                                        'emp'           => Config::get('app.emp')
                                        )
                            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pujas de un lote
        |--------------------------------------------------------------------------
        */
        # Consulta mediante identificador de Subasta y Lote
        if($this->lote) {
            return DB::select("SELECT licitadores.*, pujas1.*, pujas0.*, usuarios.* FROM FGASIGL0 pujas0
                                    LEFT JOIN FGASIGL1 pujas1
                                        ON (pujas1.SUB_ASIGL1 = pujas0.SUB_ASIGL0 AND pujas1.EMP_ASIGL1 = :emp AND pujas1.SUB_ASIGL1 = :cod AND pujas1.REF_ASIGL1 = :lote)
                                    LEFT JOIN FGLICIT licitadores
                                        ON (licitadores.COD_LICIT = pujas1.LICIT_ASIGL1 AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod)
                                    LEFT JOIN FXCLIWEB usuarios
                                        ON (usuarios.COD_CLIWEB = licitadores.CLI_LICIT AND usuarios.EMP_CLIWEB = :emp)
                                WHERE
                                    pujas0.SUB_ASIGL0 = :cod
                                    AND pujas0.EMP_ASIGL0 = :emp
                                    AND pujas0.REF_ASIGL0 = :lote
                                    AND usuarios.COD_CLIWEB = :cod_cliweb
                                    ",
                                    array(
                                        'cod'           => strtoupper($this->cod),
                                        'lote'          => $this->lote,
                                        'cod_cliweb'    => $this->cod_cli,
                                        'emp'           => Config::get('app.emp')
                                        )
                            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pujas de un Usuario sin filtros
        |--------------------------------------------------------------------------
        */
        # Consulta mediante identificador de Subasta
        if(!$this->cod && !$this->lote) {
            return DB::select("SELECT  licitadores.*, pujas1.*, pujas0.*, usuarios.* FROM FGASIGL0 pujas0
                                    LEFT JOIN FGASIGL1 pujas1
                                        ON (pujas1.SUB_ASIGL1 = pujas0.SUB_ASIGL0 AND pujas1.EMP_ASIGL1 = :emp AND pujas1.REF_ASIGL1 = pujas0.REF_ASIGL0)
                                    LEFT JOIN FGLICIT licitadores
                                        ON (licitadores.COD_LICIT = pujas1.LICIT_ASIGL1 AND licitadores.EMP_LICIT = :emp)
                                    LEFT JOIN FXCLIWEB usuarios
                                        ON (usuarios.COD_CLIWEB = licitadores.CLI_LICIT AND usuarios.EMP_CLIWEB = :emp)
                                WHERE pujas0.EMP_ASIGL0 = :emp
                                AND usuarios.COD_CLIWEB = :cod_cliweb

                                    ",
                                    array(
                                        'cod_cliweb'    => $this->cod_cli,
                                        'emp'           => Config::get('app.emp')
                                        )
                            );
        }

    }

    public function getLicitCli(){
        return DB::select(" Select * from FGLICIT
                            where EMP_LICIT = :emp
                            and SUB_LICIT = :cod
                            and CLI_LICIT = :cod_cliweb",
                            array(
                                'cod' => strtoupper($this->cod),
                                'emp' => Config::get('app.emp'),
                                'cod_cliweb' => $this->cod_cli
                                )
                            );
    }

    # Ordenes de Licitación de un Lote en concreto
    public function getOrdenes()
    {
        /*
        |--------------------------------------------------------------------------
        | Ordenes de licitacion una subasta
        |--------------------------------------------------------------------------
        */
        # Consulta mediante identificador de Subasta
        if($this->cod and !$this->lote) {
            return DB::select("SELECT * FROM FGORLIC ordenesLicitacion
                                  LEFT JOIN FGLICIT licitadores
                                    ON (licitadores.COD_LICIT = ordenesLicitacion.LICIT_ORLIC AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod AND licitadores.CLI_LICIT = :cod_cliweb)
                                WHERE
                                    ordenesLicitacion.EMP_ORLIC = :emp
                                    AND ordenesLicitacion.SUB_ORLIC = :cod",
                            array(
                                'cod' => strtoupper($this->cod),
                                'emp' => Config::get('app.emp'),
                                'cod_cliweb' => $this->cod_cli
                                )
                            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ordenes de licitacion de un lote enconcreto
        |--------------------------------------------------------------------------
        */
        # Consulta mediante identificador de Subasta
        if($this->lote) {
            return DB::select("SELECT * FROM FGORLIC ordenesLicitacion
                                  LEFT JOIN FGLICIT licitadores
                                    ON (licitadores.COD_LICIT = ordenesLicitacion.LICIT_ORLIC AND licitadores.EMP_LICIT = :emp AND licitadores.SUB_LICIT = :cod AND licitadores.CLI_LICIT = :cod_cliweb)
                                WHERE
                                    ordenesLicitacion.EMP_ORLIC = :emp
                                    AND ordenesLicitacion.SUB_ORLIC = :cod
                                    AND ordenesLicitacion.REF_ORLIC = :lote",
                            array(
                                'cod' => strtoupper($this->cod),
                                'lote' => intval($this->lote),
                                'emp' => Config::get('app.emp'),
                                'cod_cliweb' => $this->cod_cli
                                )
                            );
        }


        /*
        |--------------------------------------------------------------------------
        | Ordenes de Licitación en general sin filtros, solo el usuario
        |--------------------------------------------------------------------------
        */
        # Consulta mediante identificador de usuario unicamente
        if(!$this->cod && !$this->lote) {
            return DB::select("SELECT DISTINCT(ordenesLicitacion.LIN_ORLIC), ordenesLicitacion.* FROM FGORLIC ordenesLicitacion
                                  LEFT JOIN FGLICIT licitadores
                                    ON (
                                        licitadores.COD_LICIT = ordenesLicitacion.LICIT_ORLIC
                                        AND licitadores.EMP_LICIT = :emp
                                        AND licitadores.CLI_LICIT = :cod_cliweb
                                        )
                                WHERE
                                    ordenesLicitacion.EMP_ORLIC = :emp
                                    AND licitadores.CLI_LICIT = :cod_cliweb",
                            array(
                                'emp' => Config::get('app.emp'),
                                'cod_cliweb' => $this->cod_cli
                                )
                            );
        }

    }

    # Recogemos datos del usuario mediante el licitador y subasta,
    public function getUserByLicit($comprobar_baja = true)
    {
        $where_baja = '';

        if($comprobar_baja){
            $where_baja= "AND u.BAJA_TMP_CLI = 'N'";
        }

        $sql = "SELECT w.nom_cliweb, w.email_cliweb, w.tk_cliweb,w.tipacceso_cliweb, l.cli_licit,u.email_cli, u.nom_cli,u.CODPAIS_CLI,w.idioma_cliweb idioma_cli, u.ries_cli max_adj, u.baja_tmp_cli,u.cif_cli,u.tel1_cli,u.blockpuj_cli  FROM FGLICIT l
                JOIN FXCLI u  ON (l.CLI_LICIT = u.COD_CLI)
                JOIN FXCLIWEB w ON (l.CLI_LICIT = w.COD_CLIWEB and w.EMP_CLIWEB = l.EMP_LICIT and u.gemp_cli = w.gemp_cliweb)
                WHERE EMP_LICIT = :emp AND SUB_LICIT = :cod_sub AND COD_LICIT = :licit  $where_baja";

        $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'licit'         => $this->licit,
                        );
        $user = DB::select($sql, $bindings);

        if(!empty(Config::get('app.subalia_min_licit')) && !empty($user) && $this->licit >= Config::get('app.subalia_min_licit')){

             $sql = "SELECT  w.tk_cliweb  FROM FGLICIT l
                JOIN FXCLI u  ON (l.CLI_LICIT = u.COD_CLI)
                JOIN FXCLIWEB w ON (l.CLI_LICIT = w.COD_CLIWEB and w.EMP_CLIWEB = l.EMP_LICIT and u.gemp_cli = w.gemp_cliweb)
                WHERE EMP_LICIT = :emp AND SUB_LICIT = :cod_sub AND COD_LICIT = :licit  $where_baja";

            $bindings = array(
                        'emp'           => '001',//empresa de subalia
                        'cod_sub'       => '0',
                        'licit'         => $this->licit,
                        );
           $tk_cliweb =  DB::connection("subalia")->select($sql, $bindings);
           \Log::info(print_r($tk_cliweb,true));
           if(!empty($tk_cliweb)){
               $user[0]->tk_cliweb = $tk_cliweb[0]->tk_cliweb;
           }
        }


        return $user;
    }



     # Recogemos datos del usuario mediante el licitador y subasta,
    public function getFXCLIByLicit($comprobar_baja = true)
    {
        $where_baja = '';

        if($comprobar_baja){
            $where_baja= "AND u.BAJA_TMP_CLI = 'N'";
        }

        $sql = "SELECT u.sexo_cli, u.cod_cli,  l.cod_licit,l.cli_licit,nvl(cliweb.email_cliweb,u.email_cli) email_cli,l.rsoc_licit, u.nom_cli,  u.baja_tmp_cli,u.cif_cli,u.tel1_cli,nvl(cliweb.idioma_cliweb,u.idioma_cli) idioma_cli, u.pob_cli, u.cp_cli, u.pais_cli, u.rsoc_cli, u.cod2_cli

                FROM FGLICIT l
                LEFT JOIN FXCLIWEB cliweb on cliweb.emp_cliweb =  L.EMP_LICIT and  cliweb.cod_cliweb =  l.CLI_LICIT
				JOIN FXCLI u  ON (l.CLI_LICIT = u.COD_CLI and u.GEMP_CLI = cliweb.GEMP_CLIWEB)
				WHERE EMP_LICIT = :emp AND SUB_LICIT = :cod_sub AND COD_LICIT = :licit   $where_baja";

        $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'cod_sub'       => $this->cod,
                        'licit'         => $this->licit,
                        );
        return DB::select($sql, $bindings);
    }
    //NUEVA FUNCION QUE COJE LSO DATOS DE CLI Y SI HAY DE CLIWEB
    public function getCliInfo($check_baja = true){

        $db = DB::table("FXCLI CLI")
                ->select("CLI.SEXO_CLI, CLI.COD_CLI, CLI.COD2_CLI, CLI.NOM_CLI, CLI.BAJA_TMP_CLI, CLI.CIF_CLI, CLI.TEL1_CLI, CLI.POB_CLI,CLI.CP_CLI, CLI.PAIS_CLI, NVL(WEB.EMAIL_CLIWEB,CLI.EMAIL_CLI) EMAIL_CLI, NVL(WEB.IDIOMA_CLIWEB,CLI.IDIOMA_CLI) IDIOMA_CLI, CLI.RSOC_CLI")
                ->leftjoin("FXCLIWEB WEB", function($join){
                  $join->on("WEB.GEMP_CLIWEB", "=", "CLI.GEMP_CLI")
                       ->on("WEB.COD_CLIWEB", "=", "CLI.COD_CLI")
                       ->on("WEB.EMP_CLIWEB", "=", Config::get('app.emp') );
                })
                ->where("CLI.COD_CLI",  $this->cod_cli)
                ->where("CLI.GEMP_CLI",Config::get('app.gemp'))  ;

        if($check_baja){
             $db->where("CLI.BAJA_TMP_CLI",  'N')  ;
        }

        return $db->first();
    }

     public function getClientInfo($cod_cli){
         $data=DB::table('FXCLIWEB')
                 ->where('COD_CLIWEB',$cod_cli)
                 ->where('EMP_CLIWEB',Config::get('app.emp'))
                 ->first();
         return $data;
     }

	 public function getClientNllist($cod_cli)
	 {
		$select = array_map(function($position) {
			return "nllist{$position}_cliweb";
		}, range(1, 20));

		return FxCliWeb::select($select)->where('cod_cliweb',$cod_cli)->first();
	 }

    # Actualizamos la información del perfil del usuario
    public function updateClientInfo()
    {

       // try {
            $bindings = array(
                            'emp'           => Config::get('app.emp'),
                            'gemp'          => Config::get('app.gemp'),
                            'nom'           => $this->nom,
                            'cod_cli'       => $this->cod_cli,
                            /*'email'       => $this->email,*/
                            'lang'  => $this->language
                            );

            // 31-05-2019 - Quitamos el email del update para evitar las petadas por PK

            $sql = "UPDATE FXCLIWEB SET NOM_CLIWEB = :nom, /*USRW_CLIWEB = :email, EMAIL_CLIWEB = :email,*/ IDIOMA_CLIWEB = :lang  WHERE EMP_CLIWEB = :emp AND GEMP_CLIWEB = :gemp AND COD_CLIWEB = :cod_cli";

            DB::select($sql, $bindings);

            if(empty($this->dir2)){
                    $this->dir2 = NULL;
			}
			$updateData = array(
								'RSOC_CLI' => $this->rsoc,
								'NOM_CLI'=> $this->nom,
								'DIR_CLI'=> $this->dir,
								'DIR2_CLI'=> $this->dir2,
								'CP_CLI'=>$this->cp,
								'IVA_CLI'=>$this->iva_cli,
								'POB_CLI'=>$this->pob,
								'PRO_CLI'=>$this->pro,
								'TEL1_CLI'=>$this->tel,
								'EMAIL_CLI'=>$this->email,
								'PAIS_CLI'=>$this->email,
								'PAIS_CLI'=> !empty($this->nombre_pais) ? $this->nombre_pais[0]->des_paises : '',
								'CODPAIS_CLI'=>$this->pais,
								'IDIOMA_CLI'=>$this->language,
								'SG_CLI'=>$this->via,
								'COD_DIV_CLI'=>$this->divisa,
								'PREFTEL_CLI' => $this->preftel_cli
							);
			if(!empty($this->nacimiento)){
				$updateData['FECNAC_CLI'] = $this->nacimiento;
			}
			if(!empty($this->genero)){
				$updateData['SEXO_CLI'] = $this->genero;
			}
			if(!empty($this->tel2)){
				$updateData['TEL2_CLI'] = $this->tel2;
			}
			if(!empty($this->fisjur_cli)){
				$updateData['FISJUR_CLI'] = $this->fisjur_cli;
			}
			if(!empty($this->seudo_cli)){
				$updateData['SEUDO_CLI'] = $this->seudo_cli;
			}

            DB::table('FXCLI')
                    ->where('GEMP_CLI',Config::get('app.gemp'))
                    ->where('COD_CLI',$this->cod_cli)
                    ->update(
						$updateData
					);

           return true;

        /*} catch (\Exception $e) {
            return false;
        }*/

    }

    # Actualizamos la información del perfil del usuario
    public function updatePassword()
    {

        try {
            $bindings = array(
                            'emp'           => Config::get('app.emp'),
                            'gemp'          => Config::get('app.gemp'),
                            'cod_cli'       => $this->cod_cli,
                            'pwd_encrypt'   => $this->pwd_encrypt,
                            );

            $sql = "UPDATE FXCLIWEB SET PWDWENCRYPT_CLIWEB = :pwd_encrypt  WHERE EMP_CLIWEB = :emp AND GEMP_CLIWEB = :gemp AND COD_CLIWEB = :cod_cli";


            DB::select($sql, $bindings);

            return true;

        } catch (\Exception $e) {
            return false;
        }

    }
    public function getAllAdjudicacionesSession($cod_sub, $reference, $licit)
    {
        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cod'           => $cod_sub,
            'reference'     => $reference,
            'licit'         => $licit
            );

        $sql="SELECT REF_CSUB, HIMP_CSUB FROM FGCSUB csub
            JOIN \"auc_sessions\" auc on (auc.\"company\" = csub.EMP_CSUB AND auc.\"auction\" = csub.SUB_CSUB )
            WHERE
            csub.SUB_CSUB = :cod AND
            auc.\"reference\" = :reference AND
            csub.LICIT_CSUB = :licit AND
            csub.REF_CSUB >= auc.\"init_lot\" AND
            csub.REF_CSUB <= auc.\"end_lot\" AND
            csub.EMP_CSUB = :emp";

        return DB::select($sql, $bindings);

	}

	public function getTotalAdjudicado($codSub, $referenceSession, $licit){
		$adjudicaciones = $this->getAllAdjudicacionesSession($codSub, $referenceSession, $licit);
		#sumatotal de adjudicaciones
		$totalAdjudicado=0;
		foreach( $adjudicaciones as $adjudicacion){
			$totalAdjudicado += $adjudicacion->himp_csub;
		}
		return $totalAdjudicado;
	}

    public function getAllAdjudicacionesSessionAllInfo($cod_sub, $id_auc_sessions, $licit)
    {
        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cod'           => $cod_sub,
            'id_auc_sessions'     => $id_auc_sessions,
            'licit'         => $licit,
             'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))
            );

         $sql="SELECT REF_ASIGL0, HIMP_CSUB, NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1) DESC_HCES1 FROM FGCSUB csub
            JOIN \"auc_sessions\" auc on (auc.\"company\" = csub.EMP_CSUB AND auc.\"auction\" = csub.SUB_CSUB )
            JOIN FGASIGL0 ASIGL0 ON   ASIGL0.EMP_ASIGL0 =  csub.EMP_CSUB AND   ASIGL0.SUB_ASIGL0 =  csub.SUB_CSUB AND ASIGL0.REF_ASIGL0 = csub.REF_CSUB
            JOIN FGHCES1 HCES1 ON HCES1.EMP_HCES1 = ASIGL0.EMP_ASIGL0 AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0 AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0
            LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG = HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1 AND HCES1_LANG.LANG_HCES1_LANG = :lang)

            WHERE
            csub.SUB_CSUB = :cod AND
            auc.\"id_auc_sessions\" = :id_auc_sessions AND
            csub.LICIT_CSUB = :licit AND
            csub.REF_CSUB >= auc.\"init_lot\" AND
            csub.REF_CSUB <= auc.\"end_lot\" AND
            csub.EMP_CSUB = :emp
            order by ref_asigl0 desc";

        return DB::select($sql, $bindings);

    }

    # Adjudicaciones de usuario mediante cod_cli ya que un usuario puede tener varios codigos de licitador
    public function getAdjudicaciones()
    {

        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cli_licit'     => $this->cod_cli
            );

        $sql="SELECT T.* FROM (
                SELECT rownum rn,  C.SUB_CSUB, C.HIMP_CSUB,C.FAC_CSUB,C.AFRAL_CSUB,ASIGL0.REF_ASIGL0, LO.TITULO_HCES1,LO.NUM_HCES1 ,  LO.LIN_HCES1, P.FEC_ASIGL1, P.HORA_ASIGL1  FROM  FGLICIT L
                JOIN  FGCSUB C  ON  C.EMP_CSUB = L.EMP_LICIT  AND C.SUB_CSUB =  L.SUB_LICIT  AND C.LICIT_CSUB = L.COD_LICIT
                JOIN FGASIGL0 ASIGL0 ON ASIGL0.EMP_ASIGL0 =  L.EMP_LICIT AND  ASIGL0.SUB_ASIGL0 =  L.SUB_LICIT AND ASIGL0.REF_ASIGL0 = C.REF_CSUB
                JOIN FGHCES1 LO ON (LO.EMP_HCES1 = L.EMP_LICIT AND    LO.SUB_HCES1  =  L.SUB_LICIT  AND LO.LIN_HCES1 =  ASIGL0.LINHCES_ASIGL0  AND LO.NUM_HCES1 =  ASIGL0.NUMHCES_ASIGL0 )
                JOIN FGASIGL1 P ON P.SUB_ASIGL1 =  L.SUB_LICIT AND P.REF_ASIGL1 = C.REF_CSUB AND P.LICIT_ASIGL1 = L.COD_LICIT AND P.IMP_ASIGL1 = C.HIMP_CSUB

                WHERE L.EMP_LICIT =:emp and L.CLI_LICIT =:cli_licit
                )T". \Tools::getOffset($this->page, $this->itemsPerPage)."";


        $adj = DB::select($sql, $bindings);

        $sub = new Subasta();
        foreach ($adj as $key => $value) {

                $adj[$key]->formatted_imp_asigl1 = \Tools::moneyFormat($value->himp_csub);
                $adj[$key]->imagen = $sub->getLoteImg($value);
                $adj[$key]->date = \Tools::euroDate($value->fec_asigl1, $value->hora_asigl1 );
                $adj[$key]->imp_asigl1 = $value->himp_csub;
            }

        return $adj;
    }

     # Adjudicaciones de usuario mediante cod_cli ya que un usuario puede tener varios codigos de licitador
    public function getAdjudicacionesPagar($value = 'N', $cod_sub = '')
    {

        $lang = Config::get("app.language_complete")[Config::get("app.locale")];
        $sql =  DB::table('FGCSUB C')
                ->select('C.SUB_CSUB,C.REF_CSUB, C.HIMP_CSUB,C.BASE_CSUB,C.FAC_CSUB,C.AFRAL_CSUB,C.NFRAL_CSUB,C.fecfra_csub,P.REF_ASIGL1')
                ->addSelect('NVL(lotes_lang.titulo_hces1_lang, LO.titulo_hces1) titulo_hces1,LO.NUM_HCES1 ,  LO.LIN_HCES1, P.FEC_ASIGL1, P.HORA_ASIGL1, LO.COB_HCES1')
                ->addSelect('C.apre_csub, C.npre_csub,LO.ALM_HCES1,ALM.OBS_ALM, LO.TRANSPORT_HCES1')
                ->addSelect('SUB.cod_sub,sub.tipo_sub,auc."name" name, auc."id_auc_sessions",ASIGL0.ref_asigl0,NVL(lotes_lang.desc_hces1_lang, LO.desc_hces1) desc_hces1, NVL(lotes_lang.descweb_hces1_lang, LO.descweb_hces1) descweb_hces1, asigl0.COMLHCES_ASIGL0')
                ->addSelect('FGC0.estado_csub0,C.fecha_csub, FGC0.exp_csub0,FGC0.impgas_csub0,FGC0.tax_csub0 ')
                ->Join('FGASIGL0 ASIGL0',function($join){
                    $join->on('ASIGL0.EMP_ASIGL0','=','C.EMP_CSUB')
                    ->on('ASIGL0.SUB_ASIGL0','=','C.SUB_CSUB')
                    ->on('ASIGL0.REF_ASIGL0','=','C.REF_CSUB ');
                })
                ->Join('FGHCES1 LO',function($join){
                    $join->on('LO.EMP_HCES1','=','C.EMP_CSUB')
                    ->on('LO.SUB_HCES1','=','C.SUB_CSUB')
                    ->on('LO.LIN_HCES1','=','ASIGL0.LINHCES_ASIGL0')
                    ->on('LO.NUM_HCES1','=','ASIGL0.NUMHCES_ASIGL0');
                })
                ->Join('FGSUB SUB',function($join){
                    $join->on('SUB.EMP_SUB','=','C.EMP_CSUB')
                    ->on('SUB.COD_SUB','=','C.SUB_CSUB');
                })
                ->Join('FGASIGL1 P',function($join){
                    $join->on('P.SUB_ASIGL1','=','C.SUB_CSUB')
                    ->on('P.REF_ASIGL1','=','C.REF_CSUB')
                    ->on('P.LICIT_ASIGL1','=','C.LICIT_CSUB ')
                    ->on('P.IMP_ASIGL1','=','C.HIMP_CSUB ')
                    ->on('P.EMP_ASIGL1','=','C.EMP_CSUB');
                })
                ->Join('"auc_sessions" auc',function($join){
                    $join->on('auc."company"','=','SUB.EMP_SUB')
                    ->on('auc."auction"','=','SUB.COD_SUB');
                })
                ->leftJoin('FGHCES1_LANG lotes_lang',function($join) use($lang){
                    $join->on('lotes_lang.EMP_HCES1_LANG','=','LO.EMP_HCES1')
                    ->on('lotes_lang.NUM_HCES1_LANG','=','ASIGL0.NUMHCES_ASIGL0')
                    ->on('lotes_lang.LIN_HCES1_LANG','=','ASIGL0.LINHCES_ASIGL0')
                    ->where('lotes_lang.LANG_HCES1_LANG','=',$lang);
                })
                ->leftJoin('FXALM ALM',function($join){
                    $join->on('ALM.COD_ALM','=','LO.ALM_HCES1')
                    ->on('LO.EMP_HCES1','=','ALM.EMP_ALM');
                })
                ->leftJoin('FGCSUB0 FGC0',function($join){
                    $join->on('FGC0.EMP_CSUB0','=','C.EMP_CSUB')
                    ->on('FGC0.APRE_CSUB0','=','C.APRE_CSUB')
                    ->on('FGC0.NPRE_CSUB0','=','C.NPRE_CSUB');
				})
				/* ->leftJoin('FXCOBRO1',function($join){
					$join->on('FXCOBRO1.EMP_COBRO1','=','C.EMP_CSUB')
					->on('FXCOBRO1.AFRA_COBRO1','=','C.AFRAL_CSUB')
					->on('FXCOBRO1.NFRA_COBRO1','=','C.NFRAL_CSUB');
				}) */

                ->where('C.EMP_CSUB',Config::get('app.emp'))
                ->where('C.CLIFAC_CSUB',$this->cod_cli)
                ->whereRaw('ASIGL0.REF_ASIGL0 >= auc."init_lot"')
                ->whereRaw('ASIGL0.REF_ASIGL0 <= auc."end_lot"');
                if($value == 'S'){
					//Modificado 15/07/2022: Se modifica para que los lotes facturados no aparezcan como pendientes de pago
					//Ojo, los facturados pero no pagados apareceren como pagados en la web.
					#si existe en cobro es que está pagado, cogemos solo la linea 1 para que no de duplicados, tambien puede ser una factura manual AFRAL_CSUB = 'L00'
					/* $sql->whereRaw(" (FXCOBRO1.LIN_COBRO1 =1 or FGC0.estado_csub0 = 'C' or C.AFRAL_CSUB = 'L00'  )"); */
					$sql->whereRaw("(C.AFRAL_CSUB IS NOT NULL)");

                }else{
					$sql->whereRaw("(C.AFRAL_CSUB  IS NULL AND (C.NFRAL_CSUB IS NULL OR C.NFRAL_CSUB = 0 ))");
					$sql->whereRaw("(FGC0.estado_csub0 != 'C' or FGC0.estado_csub0 is null)");
					/* $sql->WhereRaw('FXCOBRO1.LIN_COBRO1 is null');
					#Si han creado facturas manuales (AFRAL_CSUB = 'L00' no deben salir como pendiente)
					#eliminamos de la web todos los lotes por pagar anteriores al 2020 para no saturar
					$sql->WhereRaw("( (C.AFRAL_CSUB != 'L00' and CAST(SUBSTR(C.AFRAL_CSUB, 2, 2) AS INT) > 19 or C.AFRAL_CSUB is null) OR (C.AFRAL_CSUB is not null AND C.NFRAL_CSUB != 0 and CAST(SUBSTR(C.AFRAL_CSUB, 2, 2) AS INT) > 19) )");
					$sql->WhereRaw('FXCOBRO1.LIN_COBRO1 is null');
                    $sql->where(function ($query) {
                        $query->orWhere('FGC0.estado_csub0','!=','C')
                              ->orWhereNull('FGC0.estado_csub0');
                    }); */
                }
                if(Request::input('order') == 'lasted'){
                    $sql->orderBy('C.fecha_csub','asc');
                }else{
                    $sql->orderBy('C.fecha_csub','desc'); //$sql->orderBy('C.fecfra_csub','desc nulls first');
				}

				#si tiene carrito, no deben aparecer los lotes de subasta V (se puede forzar que aparezcan si tienen webconfig viewVAuctionAward)
				if(Config::get("app.shoppingCart") && empty(Config::get("app.viewVAuctionAward"))){
					$sql->where("SUB.TIPO_SUB","!=","V");
				}
				if(!empty($cod_sub)){
					$sql->where("C.SUB_CSUB", $cod_sub);
				}

                $sql->orderBy('auc."start"','asc')
                    ->orderBy('SUB.dfec_sub','asc')
                    ->orderBy('ASIGL0.REF_ASIGL0');

                if(empty($this->itemsPerPage) || $this->itemsPerPage == 'all'){
                    $adj = $sql->get();
                }else if($this->itemsPerPage == 'count'){
                    $adj = $sql->count();
                }else{
                    $adj = $sql->paginate($this->itemsPerPage);

                }

               /* select fxpcob.emp_pcob, fgcsub.*  from fgcsub
Left Join  fxpcob on fgcsub.AFRAL_CSUB = ANUM_PCOB and NUM_PCOB = fgcsub.NFRAL_CSUB and fgcsub.EMP_CSUB = emp_pcob
where emp_csub = '001' and clifac_csub='015629' and emp_pcob is null
;*/
        return $adj;
        /*print_r($result);
        die();
        $pagado = '';
        if($value == 'S'){
            //COB_HCES1 = 'S' quiere decier que esta cobrado, puede estar pagado pero no cobrado
            //entonces miramos estado_cusb0 esta pagado
            $pagado="and (C.FAC_CSUB = 'S' OR FGC0.estado_csub0 = 'C')";

        }elseif($value == 'N'){
            //COB_HCES1 = 'N' quiere decier que no esta cobrado
            $pagado="and C.FAC_CSUB != 'S' and (FGC0.estado_csub0 != 'C' or FGC0.estado_csub0 is null)";
        }

        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cli_licit'     => $this->cod_cli,
            'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))

            );
        if(!$count){
            $sql_pre = "SELECT rownum rn, C.SUB_CSUB,C.REF_CSUB, C.HIMP_CSUB,C.BASE_CSUB,C.FAC_CSUB,C.AFRAL_CSUB,C.NFRAL_CSUB,C.fecfra_csub,P.REF_ASIGL1, NVL(lotes_lang.titulo_hces1_lang, LO.titulo_hces1) titulo_hces1,LO.NUM_HCES1 ,  LO.LIN_HCES1, P.FEC_ASIGL1, P.HORA_ASIGL1, LO.COB_HCES1,C.apre_csub, C.npre_csub,LO.ALM_HCES1,ALM.OBS_ALM, LO.TRANSPORT_HCES1,
                FGC0.*,SUB.*,auc.\"name\" as name, auc.\"id_auc_sessions\",ASIGL0.ref_asigl0,NVL(lotes_lang.desc_hces1_lang, LO.desc_hces1) desc_hces1, asigl0.COMLHCES_ASIGL0
                ";
        }else{
            $sql_pre = "Select count(rownum) num";
        }


        $sql="SELECT T.* FROM (".$sql_pre."
                FROM  FGCSUB C
                JOIN FGASIGL0 ASIGL0 ON ASIGL0.EMP_ASIGL0 =  C.EMP_CSUB AND  ASIGL0.SUB_ASIGL0 =  C.SUB_CSUB AND ASIGL0.REF_ASIGL0 = C.REF_CSUB
                JOIN FGHCES1 LO ON (LO.EMP_HCES1 = C.EMP_CSUB AND    LO.SUB_HCES1  =   C.SUB_CSUB  AND LO.LIN_HCES1 =  ASIGL0.LINHCES_ASIGL0  AND LO.NUM_HCES1 =  ASIGL0.NUMHCES_ASIGL0 )
                JOIN FGSUB SUB ON  SUB.EMP_SUB = C.EMP_CSUB AND SUB.COD_SUB =  C.SUB_CSUB
                JOIN FGASIGL1 P ON P.SUB_ASIGL1 =  C.SUB_CSUB AND P.REF_ASIGL1 = C.REF_CSUB AND P.LICIT_ASIGL1 = C.LICIT_CSUB AND P.IMP_ASIGL1 = C.HIMP_CSUB
                JOIN  \"auc_sessions\" auc ON  auc.\"company\" = SUB.EMP_SUB and auc.\"auction\" = SUB.COD_SUB
                LEFT JOIN FGHCES1_LANG lotes_lang
                ON (lotes_lang.EMP_HCES1_LANG = LO.EMP_HCES1 AND lotes_lang.NUM_HCES1_LANG = ASIGL0.NUMHCES_ASIGL0  AND lotes_lang.LIN_HCES1_LANG = ASIGL0.LINHCES_ASIGL0 AND lotes_lang.LANG_HCES1_LANG = :lang )
                LEFT JOIN FXALM ALM ON (ALM.COD_ALM = LO.ALM_HCES1 AND LO.EMP_HCES1=ALM.EMP_ALM)
                LEFT JOIN FGCSUB0 FGC0 ON (FGC0.EMP_CSUB0 = C.EMP_CSUB AND FGC0.APRE_CSUB0 = C.APRE_CSUB AND FGC0.NPRE_CSUB0 = C.NPRE_CSUB )
                WHERE C.EMP_CSUB =:emp and C.CLIFAC_CSUB =:cli_licit  ".$pagado."
                AND ASIGL0.REF_ASIGL0 >= auc.\"init_lot\"
                AND ASIGL0.REF_ASIGL0 <= auc.\"end_lot\"
                ORDER BY ".$order." auc.\"start\" desc, SUB.dfec_sub asc, ASIGL0.REF_ASIGL0
                )T";

        $adj = DB::select($sql, $bindings);
        $sub = new Subasta();
        foreach ($adj as $key => $value) {
            $adj[$key]->formatted_imp_asigl1 = \Tools::moneyFormat($value->himp_csub);
            $adj[$key]->imagen = $sub->getLoteImg($value);
            $adj[$key]->date = \Tools::euroDate($value->fec_asigl1, $value->hora_asigl1 );
            $adj[$key]->imp_asigl1 = $value->himp_csub;
        }*/

    }


    public function PrefacGenerated($emp,$user){
         $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cod_cli'     => $this->cod_cli
            );

        $sql="Select * from FGCSUB0 FGC0 where FGC0.EMP_CSUB0 :emp AND FGC0.CLI_CSUB0 = :cod_cli AND ESTADO_CSUB0 = 'N' ";

        $adj = DB::select($sql, $bindings);
    }

	public function hasSales($cod_cli)
	{
		if(!$cod_cli){
			return false;
		}

		$lotWithSale = FgHces1::getOwner()->where('cod_cli', $cod_cli)->first();

		return $lotWithSale ? true : false;
	}

    #Ventas de usuario mediante cod_cli ya que un usuario puede tener varios codigos de licitador
    public function getSales($filters = null)
    {


        $lang = Config::get("app.language_complete")[Config::get("app.locale")];

		$clobParams = "FGHCES1.desc_hces1, FGHCES1.descweb_hces1";

		$query = DB::table('FGHCES1 FGHCES1')

			->selectRaw('FGSUB.cod_sub, FGSUB.des_sub, FGSUB.tipo_sub, FGSUB.subc_sub, auc."name", auc."id_auc_sessions", auc."start", auc."end", auc."reference", auc."orders_start",
				FGHCES1.num_hces1, FGHCES1.lin_hces1, FGHCES1.implic_hces1, FGHCES1.titulo_hces1, FGHCES1.fac_hces1, FGHCES1.webfriend_hces1, FGHCES1.titulo_hces1,
				ASIGL0.ref_asigl0, ASIGL0.impsalhces_asigl0, ASIGL0.cerrado_asigl0, ASIGL0.sub_asigl0, ASIGL0.desadju_asigl0, ASIGL0.comlhces_asigl0, ASIGL0.comphces_asigl0, ASIGL0.retirado_asigl0')
			->addSelect($clobParams)
         	->Join('FGASIGL0 ASIGL0',function($join){
            	$join->on('ASIGL0.EMP_ASIGL0','=','FGHCES1.EMP_HCES1')
            	->on('ASIGL0.SUB_ASIGL0','=','FGHCES1.SUB_HCES1')
            	->on('ASIGL0.REF_ASIGL0','=','FGHCES1.REF_HCES1 ');
			})
			/*pendiente idiomas
        	->leftJoin('FGHCES1_LANG lotes_lang',function($join) use($lang){
            	$join->on('lotes_lang.EMP_HCES1_LANG','=','FGHCES1.EMP_HCES1')
            	->on('lotes_lang.NUM_HCES1_LANG','=','ASIGL0.NUMHCES_ASIGL0')
            	->on('lotes_lang.LIN_HCES1_LANG','=','ASIGL0.LINHCES_ASIGL0')
            	->where('lotes_lang.LANG_HCES1_LANG','=',$lang);
			})
			*/
			->join('FGSUB','FGSUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND FGSUB.COD_SUB = ASIGL0.SUB_ASIGL0')
			/*Pendiente idiomas
			->leftJoin('FGSUB_LANG','FGSUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND FGSUB.COD_SUB = ASIGL0.SUB_ASIGL0')
			*/
        	/*->leftJoin('FGCSUB csub',function($join){
	            $join->on('csub.ref_csub','=','ASIGL0.ref_asigl0')
    	        ->on('csub.sub_csub','=','ASIGL0.sub_asigl0')
        	    ->on('csub.emp_csub','=','FGHCES1.EMP_HCES1');
        	})*/
        	->Join('"auc_sessions" auc',function($join){
            	$join->on('auc."auction"','=','FGHCES1.SUB_HCES1')
            	->on('auc."company"','=','FGHCES1.EMP_HCES1');
        	})
        	->where('FGHCES1.PROP_HCES1',$this->cod_cli)
        	->where('FGHCES1.EMP_HCES1',Config::get("app.emp"))
        	->whereRaw('ASIGL0.REF_ASIGL0 >= auc."init_lot"')
			->whereRaw('ASIGL0.REF_ASIGL0 <= auc."end_lot"')

			->when($filters, function ($query) use ($filters) {

				return $query->when(!empty($filters['from-date']) && !empty($filters['to-date']), function ($query) use ($filters) {
					//Con las dos fechas seleccionamos las sesiones que en algun momento esten entre esas fechas
					return $query->where([
						['auc."start"', '<=', $filters['to-date']],
						['auc."end"', '>=', $filters['from-date']]
					]);
				}, function ($query) use ($filters) {
					//En caso de venir una fecha
					return $query->when(!empty($filters['from-date']), function ($query) use ($filters) {
						return $query->where('auc."start"', '>=', $filters['from-date']);

					})->when(!empty($filters['to-date']), function ($query) use ($filters) {
						return $query->where('auc."end"', '<=', $filters['to-date']);
					});
				});
			});

			if(\Config::get("app.number_bids_lotlist") ){
				$query = $query->selectRaw(" (SELECT COUNT(DISTINCT(LICIT_ASIGL1))  FROM FGASIGL1 WHERE EMP_ASIGL1 = ASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = ASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = ASIGL0.REF_ASIGL0) LICITS")
				->selectRaw(" (SELECT COUNT(LIN_ASIGL1)  FROM FGASIGL1 WHERE EMP_ASIGL1 = ASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = ASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = ASIGL0.REF_ASIGL0) BIDS");
			}

			if(config('app.number_orders_salespanel', false)){
				$query = $query->selectRaw("(SELECT COUNT(DISTINCT(LIN_ORLIC)) FROM FGORLIC WHERE FGORLIC.EMP_ORLIC = ASIGL0.EMP_ASIGL0 AND FGORLIC.SUB_ORLIC = ASIGL0.SUB_ASIGL0 AND FGORLIC.REF_ORLIC = ASIGL0.REF_ASIGL0) LICITS_ORDERS")
				->selectRaw(" (SELECT COUNT(LIN_ORLIC) FROM FGORLIC WHERE ASIGL0.EMP_ASIGL0 = FGORLIC.EMP_ORLIC AND ASIGL0.SUB_ASIGL0 = FGORLIC.SUB_ORLIC AND ASIGL0.REF_ASIGL0 = FGORLIC.REF_ORLIC) ORDERS")
				->selectRaw(" (SELECT MAX(HIMP_ORLIC) FROM FGORLIC WHERE ASIGL0.EMP_ASIGL0 = FGORLIC.EMP_ORLIC AND ASIGL0.SUB_ASIGL0 = FGORLIC.SUB_ORLIC AND ASIGL0.REF_ASIGL0 = FGORLIC.REF_ORLIC) MAX_ORDER");
			}

			return $query->get();
        	//->paginate($this->itemsPerPage);
    }


    # Recogemos todos los codigos de licitador de un usuario CLI en concreto
    public function getLicitCodes()
    {
        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cod_cli' => $this->cod_cli,
            );

        $sql = "SELECT COD_LICIT, SUB_LICIT FROM FGLICIT WHERE CLI_LICIT = :cod_cli AND EMP_LICIT = :emp";
        return DB::select($sql, $bindings);
    }

    # Recogemos codigo de licitador de un usuario en esa subasta
    public function getCodLicit()
    {
        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cod_licit' => $this->licit,
            'cod_sub'   => $this->cod
            );

        $sql = "SELECT * FROM FGLICIT WHERE COD_LICIT = :cod_licit AND EMP_LICIT = :emp AND SUB_LICIT = :cod_sub";
        return DB::select($sql, $bindings);
    }
    # Recogemos licitadores de una subasta
    public function getSubLicits()
    {
        $bindings = array(
            'emp'           => Config::get('app.emp'),
            'cod_sub'   => $this->cod_sub,
            'dummy' => Config::get("app.dummy_bidder")
            );

        $sql = "SELECT * FROM FGLICIT WHERE  EMP_LICIT = :emp AND SUB_LICIT = :cod_sub and cod_licit!= :dummy order by cod_licit asc";
        return DB::select($sql, $bindings);
    }

    //comprueba que un licitador no este de baja temporal
    public function validateUserByLicit()
    {

        $sql = "SELECT u.*,w.tk_CLIWEB FROM FGLICIT licitador
                    JOIN FXCLI u  ON (licitador.CLI_LICIT = u.COD_CLI)
                    JOIN FXCLIWEB w ON w.COD_CLIWEB = u.COD_CLI AND w.EMP_CLIWEB = licitador.EMP_LICIT
                    WHERE licitador.EMP_LICIT = :emp AND licitador.SUB_LICIT = :cod_sub AND licitador.COD_LICIT = :licit
                    AND u.GEMP_CLI = :gemp
                    AND u.BAJA_TMP_CLI = :baja_tmp";
        $bindings = array(
                        'emp'           => Config::get('app.emp'),
                        'gemp'       =>Config::get('app.gemp'),
                        'cod_sub'       => $this->cod,
                        'licit'         => $this->licit,
                        'baja_tmp'         => 'N'
                        );
        $user = DB::select($sql, $bindings);

        if(count($user)>0){
            return true;
        }
        else{
            return false;
        }


    }

    public function updateToken()
    {
        if(!empty(Config::get('app.token_security')) && Config::get('app.token_security') == 'hard' ){
            $control_date = time();
        }else{
            $control_date = date('Y-m-d');
        }
        $hash_string = $this->cod . "-". Config::get('app.emp') . "-" . $control_date . Config::get('app.gemp');
        if(!empty(Config::get('app.password_MD5'))){
                 $hash_string =   Config::get('app.password_MD5').$hash_string;
        }
        $token = hash("sha256", $hash_string);

        FxCliWeb::where('COD_CLIWEB', $this->cod)
            ->update(['TK_CLIWEB' => $token]);

        return $token;
    }

    public function favorites()
    {
              $sql = "select FXTSEC.cod_tsec, NVL(FXTSEC_LANG.DES_TSEC_LANG, FXTSEC.DES_TSEC) DES_TSEC
                    from FXTSEC
                    left join FXTSEC_LANG on (FXTSEC_LANG.COD_TSEC_LANG = FXTSEC.COD_TSEC and FXTSEC_LANG.GEMP_TSEC_LANG = FXTSEC.GEMP_TSEC and FXTSEC_LANG.LANG_TSEC_LANG = :lang)
                    where FXTSEC.WEB_TSEC = 'S'
                    and FXTSEC.GEMP_TSEC = :gemp
                    order by FXTSEC.DES_TSEC asc";

             $data =  DB::select($sql,
                                        array(
                                            'gemp'       => Config::get('app.gemp'),
                                            'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))
                                            )
                                        );
            return $data;
    }



    public function  fav_themes($emp,$cod_cli)
    {

            $data = DB::table('FXCLIWEBTSEC')
                    ->where('CLI_CLIWEBTSEC',$cod_cli)
                    ->where('EMP_CLIWEBTSEC',$emp)
                    ->get();
            return $data;
    }


    public function addfavorites($emp,$num,$cod_tsec){
        DB::table('FXCLIWEBTSEC')->insert(
            ['EMP_CLIWEBTSEC' => $emp, 'CLI_CLIWEBTSEC' => $num,'TSEC_CLIWEBTSEC' => $cod_tsec]
        );
    }

     public function deletefavorites($emp,$cod_cli,$cod_tsec = null){

        $where = '';
        $bindings = array(
            'emp'       => $emp,
            'cod_cli'      => $cod_cli
            );
        if(!empty($cod_tsec)){
             $where = 'AND TSEC_CLIWEBTSEC = :tsec';
             $bindings ['tsec']=$cod_tsec;
        }


        $sql = "DELETE FROM FXCLIWEBTSEC WHERE EMP_CLIWEBTSEC = :emp and CLI_CLIWEBTSEC = :cod_cli $where";

        DB::select($sql,$bindings);
    }


    public function getDireccionEnvio($cod_cli,$gemp){
        return DB::table('FXCLID')
                ->where('CLI_CLID',$cod_cli)
                ->where('GEMP_CLID',$gemp)
                ->first();
    }


    public function getPais($pais){
         return DB::table('FSPAISES')
                ->select('DES_PAISES')
                ->where('COD_PAISES',$pais)
                ->first();
    }

    public function getFactura($factura){
        return DB::table('FXDVC02')
                ->join('fxdvc0', function ($join) {
                    $join->on('fxdvc02.anum_dvc02', '=', 'fxdvc0.anum_dvc0')
                    ->on('fxdvc02.num_dvc02', '=', 'fxdvc0.num_dvc0');
                })
                ->select('fich_dvc02','fecha_dvc0')
                ->where('anum_dvc02',$factura->afral)
                ->where('num_dvc02',$factura->nfral)
                ->where('emp_dvc02',$factura->emp)
                ->where('emp_dvc0',$factura->emp)
                ->where('cod_dvc0',$factura->cod_cli)
                ->first();
    }

    public function getFxdir($emp){
         $data = DB::table('FXDIR')
                 ->select('dir42_dir')
                 ->where('emp_dir',$emp)
                 ->first();
         return $data->dir42_dir;
    }

    public function  EmailExist($email,$emp,$gemp){
       return DB::table('FXCLIWEB')
        ->select('FXCLIWEB.*')
        ->where('GEMP_CLIWEB',$gemp)
        ->where('EMP_CLIWEB',$emp)
        ->where('upper(USRW_CLIWEB)',strtoupper($email))
        ->where('COD_CLIWEB','!=',0)
        ->first();
    }

    public function logLogin($cod_cli,$emp,$date,$ip){

        try {
            DB::table('WEB_LOGIN_LOG')
                ->insert(['CODCLI_WEB_LOGIN_LOG' => $cod_cli, 'DATE_WEB_LOGIN_LOG' =>$date,'EMP_WEB_LOGIN_LOG' => $emp, 'IP_WEB_LOGIN_LOG'=>$ip]
            );
        } catch (\Exception $e) {
            \Log::emergency('Insert WEB_LOGIN_LOG '.$e);
        }
    }

    public function logLoginError($email,$passw,$her_pwd,$emp,$date,$ip){

        try {
            DB::table('WEB_LOGIN_ERROR')
                ->insert(['USRW_WEB_LOGIN_ERROR' => $email, 'PASS_WEB_LOGIN_ERROR' =>$passw,'PASS_USER_WEB_LOGIN_ERROR'=>$her_pwd,'DATE_WEB_LOGIN_ERROR' => $date, 'EMP_WEB_LOGIN_ERROR'=>$emp, 'IP_WEB_LOGIN_ERROR'=>$ip]
            );
        } catch (\Exception $e) {
            \Log::emergency('Insert WEB_LOGIN_ERROR');
        }
    }




    public function getTown($zip_code,$country){
        return DB::table('FSPOB')
        ->where('COD_POB',$zip_code)
        ->where('PAIS_POB',$country)
        ->first();
    }

    public function getProvince($cod_prov){
        return DB::table('FSPRV')
        ->where('COD_PRV',$cod_prov)
        ->first();

    }

     public function BajaTmpCli($cod_cli,$baja_tmp,$date,$usr4){
            DB::table('FXCLI')
                ->where('GEMP_CLI',Config::get('app.gemp'))
                ->where('COD_CLI',$cod_cli)
                ->update(['BAJA_TMP_CLI' => $baja_tmp,'F_UFRA_CLI' => $date, 'USR4_CLI'=> $usr4]
            );

    }

     public function updateDivisa($cod_cli,$divisa){
            DB::table('FXCLI')
                ->where('GEMP_CLI',Config::get('app.gemp'))
                ->where('COD_CLI',$cod_cli)
                ->update(['COD_DIV_CLI' => $divisa]);

    }

    //Devuelve usuarios dados de baja que tengan codigo de licitador en esa subastas
    public function getLicitsCodsub($baja = 'N'){
        $sql = "select LICIT.COD_LICIT, CLI.NOM_CLI, CLI.COD_CLI, CLIWEB.USRW_CLIWEB from FGSUB SUB
                INNER JOIN FGLICIT LICIT ON (LICIT.SUB_LICIT = SUB.COD_SUB AND LICIT.EMP_LICIT = SUB.EMP_SUB)
                INNER JOIN FXCLI CLI ON (CLI.COD_CLI = LICIT.CLI_LICIT AND CLI.GEMP_CLI = :gemp AND CLI.BAJA_TMP_CLI = :baja)
                INNER JOIN FXCLIWEB CLIWEB ON (CLIWEB.COD_CLIWEB = CLI.COD_CLI AND CLIWEB.EMP_CLIWEB = SUB.EMP_SUB AND CLIWEB.GEMP_CLIWEB = CLI.GEMP_CLI)
                WHERE SUB.COD_SUB = :cod
                AND SUB.EMP_SUB = :emp
                ";

             $data =  DB::select($sql,
                        array(
                            'baja'  => $baja,
                            'gemp'       => Config::get('app.gemp'),
                            'emp'       => Config::get('app.emp'),
                            'cod'   => $this->cod_sub
                            )
                        );
            return $data;
    }

    public function getDirEnvio($num){
       return DB::select("Select * FROM FXCLID
        WHERE GEMP_CLID = :gemp and CLI_CLID = :num and CODD_CLID = :codd",
             array(
                    'gemp' => Config::get('app.gemp'),
                    'num' => $num,
                    'codd' => 'W1'
                    )
       );
    }

    public function changeConditionsUser(){
        DB::table('FXCLIWEB')
                ->where('GEMP_CLIWEB',Config::get('app.gemp'))
                ->where('EMP_CLIWEB',Config::get('app.emp'))
                ->where('COD_CLIWEB',$this->cod_cli)
                ->update(['NLLIST2_CLIWEB' => 'S']
            );
        return true;
    }


    public function setVisitRealTime($cod_cli, $session, $ip){


        try{
        //HEMOS DEJADO COMO PRIMARIA EL ID Y LOS USUARIOS POR SI HAY MUCHAS PETICIOENS A LA VEZ QUE NO FALLE EL ID
        $sql ="INSERT INTO WEB_VISIT_RT (ID_VISIT_RT, EMP_VISIT_RT, CODUSER_VISIT_RT, IDSESSION_VISIT_RT, DATE_VISIT_RT, IP_VISIT_RT)
                    VALUES((SELECT NVL(MAX(ID_VISIT_RT),0)+1  FROM WEB_VISIT_RT),:emp, :codcli, :idsession,sysdate,:ip)";
        $bindings = array(
                        'emp'   => \Config::get('app.emp'),
                        'codcli' =>$cod_cli,
                        'idsession'=>$session,
                        'ip'  => $ip,

                    );
               DB::select($sql,$bindings);
        }catch (\Exception $e) {
            \Log::Error("Error al generar visita en tiempo real ".print_r($bindings,true));
            return;
        }

	}

	/**
	 * Modifica el tipo fisjur a R, y alterna nombre y razón social
	 */
	public function changeToRepresentative(FxCli $fxCliOriginal, $newNomCli)
	{

		FxCli::where("GEMP_CLI", \Config::get('app.gemp'))
		->where("COD_CLI", $fxCliOriginal->cod_cli)
		->update([
			'FISJUR_CLI' => 'R',
			'RSOC_CLI' => $fxCliOriginal->fisjur_cli == 'R' ? $fxCliOriginal->rsoc_cli : $fxCliOriginal->nom_cli,
			'NOM_CLI' => $newNomCli
		]);

		FxCliWeb::where([
			['GEMP_CLIWEB', \Config::get('app.gemp')],
			['EMP_CLIWEB', \Config::get('app.emp')],
			['COD_CLIWEB', $fxCliOriginal->cod_cli]
		])
		->update([
			'NOM_CLIWEB' => $newNomCli
		]);

		return FxCli::select('cod_cli', 'nom_cli', 'rsoc_cli', 'fisjur_cli', 'email_cli', 'cif_cli')->where('COD_CLI', $fxCliOriginal->cod_cli)->first();
	}

	/**
	 * Modifica un usuario, que anteriormente fuese fisjur R a F y vuelve a añadirle su nombre original
	 */
	public function changeFromRepresentativeToParticular(FxCli $fxCliOriginal){
		FxCli::where("GEMP_CLI",\Config::get('app.gemp'))
			->where("COD_CLI", $fxCliOriginal->cod_cli)
			->update([
				'FISJUR_CLI' => 'F',
				'RSOC_CLI' => $fxCliOriginal->rsoc_cli,
				'NOM_CLI' => $fxCliOriginal->rsoc_cli
			]);

			FxCliWeb::where([
				['GEMP_CLIWEB', \Config::get('app.gemp')],
				['EMP_CLIWEB', \Config::get('app.emp')],
				['COD_CLIWEB', $fxCliOriginal->cod_cli]
			])
			->update([
				'NOM_CLIWEB' => $fxCliOriginal->rsoc_cli
			]);

			return FxCli::select('cod_cli', 'nom_cli', 'rsoc_cli', 'fisjur_cli', 'email_cli', 'cif_cli')->where('COD_CLI', $fxCliOriginal->cod_cli)->first();
	}

	public function isCliWeb()
	{
		$client = FxCliWeb::select('cod_cliweb')->where('cod_cliweb', $this->cod_cli)->first();

		if(!$client){
			return false;
		}

		return true;
	}

}
