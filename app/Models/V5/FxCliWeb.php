<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;

class FxCliWeb extends Authenticatable implements AuthenticatableContract
{

	use HasApiTokens;

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FxCliWeb';
	protected $primaryKey = 'cod_cliweb';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva



	public function __construct(array $vars = []){
        $this->attributes=[
			'gemp_cliweb' => Config::get("app.gemp"),
            'emp_cliweb' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_cliweb', Config::get("app.emp"))
					->where('gemp_cliweb', Config::get("app.gemp"));
        });
	}


	public function scopeWhereUpdateApi($query, $item){
			return $query->where('cod2_cliweb', $item["cod2_cliweb"]);

    }



    # JOINS
    public function scopeJoinCliCliweb($query){
        return  $query->join('FXCLI', 'FXCLIWEB.COD_CLIWEB = FXCLI.COD_CLI  AND FXCLIWEB.GEMP_CLIWEB', '=', 'FXCLI.GEMP_CLI');

    }

    public function scopeJoinLicitCliweb($query){
        return  $query->join('FGLICIT', 'FGLICIT.CLI_LICIT = FXCLIWEB.COD_CLIWEB  AND FGLICIT.EMP_LICIT = FXCLIWEB.EMP_CLIWEB');
    }




    //
    //   EMAILEXIST - Función encargada de comprobar si hay un usuario con esa PK (email) dado de alta
    //
    //   @email - Primary Key de CLIWEB (USRW_CLIWEB)
    //
    //   Devuelve el user encontrado
    //

	static function emailExistCliweb($email) {

        $useraux =  FxCliWeb::select("cod_cliweb")
        ->where("lower(USRW_CLIWEB)",strtolower($email))
        ->first();

        return $useraux;

    }






    //
    //   CHECKUSER - Función encargada de comprobar login y password en la web y devolver el usuario encontrado
    //
    //   @email - Primary Key de CLIWEB (USRW_CLIWEB)
    //   @password - Password del usuario
    //   @emp - Empresa
    //   @gemp - Grupo de empresas
    //
    //   Devuelve el user encontrado. Se pasa la empresa porque esta función se utiliza para validar en casas de subastas
    //


    public function checkUserCliweb($email , $password, $emp , $gemp ) {

        if (!$email || !$password || !$emp || !$gemp) {
            return false;
        }

        $user = FxCliWeb::select("cod_cli","nom_cli","tipacceso_cliweb","trim(pwdwencrypt_cliweb)","baja_tmp_cli","cif_cli")
                    ->JoinCliCliweb()
                    ->where("EMP_CLIWEB",$emp)
                    ->where("GEMP_CLIWEB", $gemp)
                    ->where("lower(USRW_CLIWEB)",strtolower($email))
                    ->where("trim(pwdwencrypt_cliweb)",Web_Config::password_encrypt($password,$emp))
                    ->where("tipacceso_cliweb","!=","X")
                    ->first();
        return $user;

    }

    //
    //   LOGINUSER - Función encargada de realizar propiamente la accion de login
    //
    //   @user - usuario a loguear
    //
    //   Devuelve true si todo esta bien
    //

    static function loginUserCliweb($cod_cli) {

        if (!$cod_cli)
            return false;

        $user = FxCli::JoinCliWebCli()->JoinLicitCli()->select("nom_cli","usrw_cliweb","cod_cli","emp_cliweb","gemp_cliweb","tipacceso_cliweb","baja_tmp_cli","trim(pwdwencrypt_cliweb)","tk_cliweb","tel1_cli","cif_cli","rsoc_cli","cod_licit")
                ->where("EMP_CLIWEB",Config::get('app.emp'))
                ->where("GEMP_CLIWEB", Config::get('app.gemp'))
                ->where("COD_CLI",$cod_cli)
                ->where("baja_tmp_cli","N")
                ->where("tipacceso_cliweb","!=","X")
                ->first();

        if (empty($user)) {
            return false;
        }
        else {
            $user = $user->toArray();
        }

        # Seteamos la sesión con los parametros necesarios

        $user['clicli'] = array();
        $clicli = FxCli::where("GEMP_CLICLI",Config::get("app.gemp"))
            ->where("CLI_SUBALIA_CLICLI",$cod_cli)->get();
        foreach($clicli as $item) {
            $user['clicli'][$item->name_auchouse_clicli] = $item;
        }


        foreach($user as $k => $v) {
            Session::put('user.'.$k, $v);
        }

        if($user['tipacceso_cliweb'] == 'S') {
            Session::push('user.admin', true);
            Session::push('user.adminconfig', false);
        }
        elseif($user['tipacceso_cliweb'] == 'A') {
            Session::push('user.admin', true);
            Session::push('user.adminconfig', true);
        }else{
            Session::push('user.admin', false);
            Session::push('user.adminconfig', false);
        }


        //Miramos si hemos de crear/actualizar el token para las pujas online

        FxCliWeb::updateTokenCliweb($user);

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }


        // Guardamos registro en el log
        try {
            DB::table('WEB_LOGIN_LOG')
                ->insert(['CODCLI_WEB_LOGIN_LOG' => $user['cod_cliweb'], 'DATE_WEB_LOGIN_LOG' => date("Y-m-d G:i"),'EMP_WEB_LOGIN_LOG' => Config::get('app.emp'), 'IP_WEB_LOGIN_LOG'=>$ip]);
        } catch (\Exception $e) {
            Log::emergency('Insert WEB_LOGIN_LOG');
        }

        return true;
    }

















    //
    //   UPDATETOKEN - Función encargada de generar / actualizar el token de usuario
    //
    //   @user - usuario propiertario del token
    //
    //   Devuelve el token válido.
    //

    static function updateTokenCliweb($user) {

        if (empty($user))
            return false;

        if( !empty(Config::get('app.token_security')) && Config::get('app.token_security') == 'hard' ) {
            $control_date = time();
        }
        else {
            $control_date = date('Y-m-d');
        }

        $hash_string = $user['cod_cli'] . "-". Config::get('app.emp') . "-" . $control_date . Config::get('app.gemp');

        if(!empty(Config::get('app.password_MD5'))) {
            $hash_string = Config::get('app.password_MD5').$hash_string;
        }

        $token = hash("sha256",$hash_string);

        if ($user['tk_cliweb'] != $token) {

            FxCliWeb::where("GEMP_CLIWEB",Config::get('app.gemp'))
                ->where("EMP_CLIWEB",Config::get('app.emp'))
                ->where("COD_CLIWEB",$user['cod_cli'])
                ->update(['TK_CLIWEB' => $token]);
        }
        Session::put('user.tk_cliweb', $token);

        return $token;

    }

	public function scopelog($query){
					 #saco todos los campos a mano para poder ocultar password encriptado y token ya que lo ha pedido servihabitat
		return $query->select(" GEMP_CLIWEB,COD_CLIWEB,USRW_CLIWEB,EMP_CLIWEB,TIPACCESO_CLIWEB,TIPO_CLIWEB,DIRM_CLIWEB,NOM_CLIWEB,EMAIL_CLIWEB,PER_CLIWEB,FECALTA_CLIWEB,USRALTA_CLIWEB,FECMODI_CLIWEB,USRMODI_CLIWEB,FECMODIPWD_CLIWEB,USRMODIPWD_CLIWEB,NLLIST1_CLIWEB,NLLIST2_CLIWEB,NLLIST3_CLIWEB,NLLIST4_CLIWEB,NLLIST5_CLIWEB,NLLIST6_CLIWEB,NLLIST7_CLIWEB,NLLIST8_CLIWEB,NLLIST9_CLIWEB,NLLIST10_CLIWEB,IDIOMA_CLIWEB,NLLIST11_CLIWEB,NLLIST12_CLIWEB,NLLIST13_CLIWEB,NLLIST14_CLIWEB,NLLIST15_CLIWEB,NLLIST16_CLIWEB,NLLIST17_CLIWEB,NLLIST18_CLIWEB,NLLIST19_CLIWEB,NLLIST20_CLIWEB,PUBLI_CLIWEB,COD2_CLIWEB,TIENDA_CLIWEB,TYPE_UPDATE_CLIWEB,DATE_UPDATE_CLIWEB,USR_UPDATE_CLIWEB,PERMISSION_ID_CLIWEB");

	}

	public function getAuthIdentifierName()
	{
		return 'usrw_cliweb';
	}

	public function getAuthIdentifier()
	{
		return $this->attributes['usrw_cliweb'];
	}

	public function getAuthPasswordName()
	{
		return 'pwdwencrypt_cliweb';
	}

	public function getAuthPassword()
	{
		return $this->attributes['pwdwencrypt_cliweb'];
	}
}

