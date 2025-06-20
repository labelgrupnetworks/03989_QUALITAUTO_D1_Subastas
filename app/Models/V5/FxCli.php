<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Models\V5\Traits\ScopeFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FxCli extends Model
{
	use ScopeFilter;
	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FxCli';
	protected $primaryKey = 'cod_cli';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	const TIPO_FISJUR_FISICA = 'F';
	const TIPO_FISJUR_JURIDICA = 'J';

	const TIPO_BAJA_TMP_NO = 'N';
	const TIPO_BAJA_TMP_SI = 'S';
	const TIPO_BAJA_TMP_PEN = 'A';
	const TIPO_BAJA_TMP_REF = 'B';

	const TIPO_CLI_AMBOS = 'A';
	const TIPO_CLI_LICITADOR = 'L';
	const TIPO_CLI_WEB = 'W';
	const TIPO_CLI_CEDENTE = 'C';
	const TIPO_CLI_VENDEDOR = 'V';
	const TIPO_CLI_PROPIETARIO = 'P';

	const TIPO_DOC_DNI ='D';
	const TIPO_DOC_NIE ='E';
	const TIPO_DOC_NIF ='F';
	const TIPO_DOC_PAS ='P';
	const TIPO_REP_ADMIN ='A';
	const TIPO_REP_REPLEG ='R';


	public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_cli' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('gemp_cli', \Config::get("app.gemp"));
        });
	}

	#es obligatorio que llegue uno de los tres, cod2_cli, email_cli,
	public function scopeWhereUpdateApi($query, $item)
	{
		return  $query->where('cod2_cli', $item["cod2_cli"]);
	}

	static function getNextCodCli(){
		$codCli = self::select("cod_cli")->orderby("cast(cod_cli as int)","desc")
			->whereRaw("TRANSLATE(cod_cli, 'T 0123456789', 'T') is NULL")
			->first();
		if(empty($codCli)){
			$next = 1;
		}else{
			$next = intval($codCli->cod_cli) + 1;
		}
		return $next;
	}

    # SELECTS
    #Si es necesario el email  se coge independientemente con addSelect o usando joincliweb
    public function scopeSelectBasicCli($query){

        return  $query->select("FXCLI.COD_CLI","FXCLI.NOM_CLI","FXCLI.CIF_CLI","FXCLI.IDIOMA_CLI","FXCLI.BAJA_TMP_CLI","FXCLI.TEL1_CLI","FXCLI.RSOC_CLI");

    }


    # JOINS
    public function scopeJoinCliWebCli($query){
        $query = $query->addSelect("NVL(FXCLI.EMAIL_CLI,FXCLIWEB.EMAIL_CLIWEB) as EMAIL_CLI","FXCLIWEB.TIPACCESO_CLIWEB");
        return  $query->join('FXCLIWEB', 'FXCLIWEB.COD_CLIWEB = FXCLI.COD_CLI  AND FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI');
    }

	public function scopeLeftJoinCliWebCli($query){
		$emp = Config::get('app.emp');
        return $query->addSelect("NVL(FXCLI.EMAIL_CLI, FXCLIWEB.EMAIL_CLIWEB) as EMAIL_CLI", "FXCLIWEB.TIPACCESO_CLIWEB")
			->leftjoin('FXCLIWEB', function($join) use ($emp){
				$join->on('FXCLIWEB.COD_CLIWEB', 'FXCLI.COD_CLI')
					->on('FXCLIWEB.GEMP_CLIWEB', 'FXCLI.GEMP_CLI')
					->where('FXCLIWEB.EMP_CLIWEB', $emp);
			});
    }

    public function scopeJoinLicitCli($query){
        $query = $query->addSelect("FGLICIT.COD_LICIT");
        return  $query->join('FGLICIT', 'FGLICIT.CLI_LICIT = FXCLI.COD_CLI  AND FGLICIT.EMP_LICIT = FXCLIWEB.EMP_CLIWEB');
    }

	public function scopeLeftJoinClid($query,$codd_clid = ""){
		#debemos poner la condicion en el join ya que si va en un where no sacaria ningun resultado aunque haya un left join
		$condition="";
		if(!empty($codd_clid)){
			$condition="AND CODD_CLID = '$codd_clid'";
		}

        return  $query->leftjoin('FXCLID', "FXCLID.GEMP_CLID = FXCLI.GEMP_CLI AND FXCLID.CLI_CLID = FXCLI.COD_CLI $condition" );
    }


    # WHERE
    public function scopeMyGempCli($query){
        return $query->where("GEMP_CLI", \Config::get('app.gemp'));
    }



	#functions

	public static function getCurrentCredit($codSub,$licit = null ){

		if($licit == Config::get("app.dummy_bidder")){
			return 9999999999; #credito máximos ya que es en sala.
		}
		#USO LAS FUNCIONES DE JOIN PARA EVITAR LA SQL INJECTION
		$emp = Config::get("app.emp");
		$self = self::select(" nvl(max(NUEVO_CREDITOSUB), max(RIES_CLI) ) maxcredito ")
			->leftjoin("FGCREDITOSUB", "EMP_CREDITOSUB = '$emp' and SUB_CREDITOSUB = '$codSub' and CLI_CREDITOSUB = COD_CLI");

		if(!empty($codCli)){
			$self = $self->where("COD_CLI", $codCli);
		}elseif(!empty($licit)){
			$self = $self->join("FGLICIT", function($join) use($codSub){
				$join->on("EMP_LICIT", "=", Config::get("app.emp"))->
				on("SUB_LICIT", "'".$codSub."'")->
				on("CLI_LICIT = COD_CLI");
			})->where("COD_LICIT", $licit);
		}else{
			#deben pasar almenos uno de lso dos o licitador o usuario
			return 0;
		}
		$credito = $self->groupBy("COD_CLI")->first();
		if(!empty($credito)){
			return $credito->maxcredito;
		}else{
			return 0;
		}

	}

	#RELACIÓN
	public function tipoCli()
	{
		return $this->belongsTo(FxTcli::class, 'tipo_cli', 'cod_tcli');
	}

	public function cli2()
	{
		return $this->belongsTo(FxCli2::class, 'cod_cli', 'cod_cli2');
	}

	public function hojasCesionCabecera()
	{
		return $this->hasMany(FgHces0::class, 'prop_hces0', 'cod_cli');
	}

	public function hojasCesion()
	{
		return $this->hasMany(FgHces1::class, 'prop_hces1', 'cod_cli');
	}

	public function origenes()
	{
		return $this->belongsToMany(FsOrigen::class, 'fxcliorigen', 'cli_cliorigen', 'origen_cliorigen')
						->wherePivot('gemp_cliorigen', config('app.gemp'));
	}

	public function invitations()
	{
		return $this->hasMany(FgSubInvites::class, 'invited_codcli_subinvites', 'cod_cli');
	}

	public function invitation()
	{
		$userCod = Session::get('user.cod');
		return $this->belongsTo(FgSubInvites::class, 'cod_cli', 'invited_codcli_subinvites')
			->where('owner_codcli_subinvites', $userCod);
	}



	#ATRIBUTOS
	public function getTipoCliTypes(){
		return [
			self::TIPO_CLI_AMBOS => trans("admin-app.fields.tipo_cli_a"),
			self::TIPO_CLI_LICITADOR => trans("admin-app.fields.tipo_cli_l"),
			self::TIPO_CLI_WEB => trans("admin-app.fields.tipo_cli_w"),
			self::TIPO_CLI_PROPIETARIO => trans("admin-app.fields.tipo_cli_p"),
		];
	}

	public function getTipoCliTypesAttribute(){
		if(array_key_exists($this->tipo_cli, $this->getTipoCliTypes())){
			return $this->getTipoCliTypes() [$this->tipo_cli];
		}
		return $this->tipo_cli;
	}

	public function getTipoFisJurTypes(){
		return[
			self::TIPO_FISJUR_FISICA => trans("admin-app.fields.fisjur_cli_f"),
			self::TIPO_FISJUR_JURIDICA => trans("admin-app.fields.fisjur_cli_j"),
		];
	}

	public function getTipoFisJurTypesAttribute(){
		if(array_key_exists($this->fisjur_cli, $this->getTipoFisJurTypes())){
			return $this->getTipoFisJurTypes() [$this->fisjur_cli];
		}
		return $this->fisjur_cli;
	}


	public function getTipoBajaTmpTypes(){
		return[
			self::TIPO_BAJA_TMP_NO => trans("admin-app.general.not"),
			self::TIPO_BAJA_TMP_SI => trans("admin-app.general.yes"),
			self::TIPO_BAJA_TMP_PEN => trans("admin-app.fields.baja_tmp_a"),
			self::TIPO_BAJA_TMP_REF => trans("admin-app.fields.baja_tmp_b"),
		];
	}

	public function getTipoBajaTmpTypesAttribute(){
		if(array_key_exists($this->baja_tmp_cli, $this->getTipoBajaTmpTypes())){
			return $this->getTipoBajaTmpTypes() [$this->baja_tmp_cli];
		}
		return $this->baja_tmp_cli;
	}

	public function getDirectionAttribute()
	{
		return "{$this->dir_cli}{$this->dir2_cli}";
	}

	public function getCompleteDirectionAttribute()
	{
		return "{$this->sg_cli} {$this->dir_cli}{$this->dir2_cli}";
	}

	public function getTipoDocumento ()
	{
		$document_type = FsAux1::getDocumentTypes();
		if($document_type->isNotEmpty()){
			return $document_type;
		}

		return[
			self::TIPO_DOC_DNI => 'DNI',
			self::TIPO_DOC_NIE => 'NIE',
			self::TIPO_DOC_NIF => 'NIF',
			self::TIPO_DOC_PAS => trans("admin-app.fields.pasaporte"),
		];
	}

	public function getTipoRep (){
		return[
			self::TIPO_REP_ADMIN => trans("admin-app.fields.tipo_rep_a"),
			self::TIPO_REP_REPLEG => trans("admin-app.fields.tipo_rep_r"),
		];
	}

	public static function isCedente($cod_cli)
	{
		$tipo_cli = self::select('tipo_cli')->where('cod_cli', $cod_cli)->first()->tipo_cli;

		return $tipo_cli == self::TIPO_CLI_VENDEDOR || $tipo_cli == self::TIPO_CLI_CEDENTE;
	}

	public static function newCod2Cli($cod_cli = NULL)
	{
		if(!$cod_cli){
			$cod_cli = self::getNextCodCli();
		}

		$tcli_params = FsParams::select("tcli_params")->first();

		$numdigits = 6;
		if(!empty($tcli_params) && !empty($tcli_params->tcli_params)){
			$numdigits = $tcli_params->tcli_params;
		}

		$formatCodCli = sprintf("%'.0".$numdigits ."d", $cod_cli);

		return str_replace("0", "W", $formatCodCli);
	}

}
