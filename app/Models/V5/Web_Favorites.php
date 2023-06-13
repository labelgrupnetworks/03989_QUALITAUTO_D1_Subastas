<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Web_Favorites extends Model
{
    protected $table = 'web_favorites';
    protected $primaryKey = 'ID_WEB_FAVORITES';
    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = []){
		$this->attributes=[
			'ID_EMP' => \Config::get("app.emp")
		];

		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('ID_EMP', \Config::get("app.emp"));
		});
	}



    # JOINS
    #solo los usuarios web tienen favoritos
    public function scopeJoinCliWebFavorites($query){
        $query = $query->addSelect("FXCLIWEB.COD_CLIWEB","FXCLIWEB.NOM_CLIWEB");
        return  $query->join('FXCLIWEB', function($q)
                {
                    $q	->on('FXCLIWEB.EMP_CLIWEB','=','WEB_FAVORITES.ID_EMP')
						->on('FXCLIWEB.COD_CLIWEB','=','WEB_FAVORITES.COD_CLI');
                });

    }



    public function scopeJoinSubFavorites($query){
        $query = $query->addSelect("FGSUB.COD_SUB","FGSUB.DES_SUB");
        return $query->join('FGSUB', function($q)
                {
                    $q	->on('FGSUB.EMP_SUB','=','WEB_FAVORITES.ID_EMP')
						->on('FGSUB.COD_SUB','=','WEB_FAVORITES.ID_SUB');
                });
    }

    public function scopeJoinAsigl0Favorites($query){
        $query->addSelect("FGASIGL0.REF_ASIGL0");
        $query = $query->join("FGASIGL0", function($q)
                {
                    $q	->on('FGASIGL0.EMP_ASIGL0','=','WEB_FAVORITES.ID_EMP')
						->on('FGASIGL0.SUB_ASIGL0','=','WEB_FAVORITES.ID_SUB')
						->on('FGASIGL0.REF_ASIGL0','=','WEB_FAVORITES.ID_REF');
                });
    }

    #Requiere que se haya hecho el join a Asigl0
    public function scopeJoinHces1Favorites($query){
        $query->addSelect("FGHCES1.TITULO_HCES1");
        $query = $query->join("FGHCES1", function($q)
                {
                    $q	->on('FGHCES1.EMP_HCES1','=','FGASIGL0.EMP_ASIGL0')
						->on('FGHCES1.NUM_HCES1','=','FGASIGL0.NUMHCES_ASIGL0')
						->on('FGHCES1.LIN_HCES1','=','FGASIGL0.LINHCES_ASIGL0');
                });
    }

	#Requiere que se haya hecho el join a Asigl0
	public function scopeJoinSubastaFavorites($query){
		$query = $query->join('FGSUB', function($q)
				{
					$q	->on('FGSUB.EMP_SUB','=','FGASIGL0.EMP_ASIGL0')
						->on('FGSUB.COD_SUB','=','FGASIGL0.SUB_ASIGL0');
				});
	}

	#Requiere que se haya hecho el join a Asigl0 y el join a "auc_sessions"
	public function scopeJoinLangHces1Favorites($query){
		$query = $query->leftjoin('FGHCES1_LANG', function($q)
				{
					$lang = ToolsServiceProvider::getLanguageComplete(\Config::get('app.locale'));
					$q	->on('FGHCES1_LANG.EMP_HCES1_LANG','=','FGASIGL0.EMP_ASIGL0')
						->on('FGHCES1_LANG.NUM_HCES1_LANG','=','FGASIGL0.NUMHCES_ASIGL0')
						->on('FGHCES1_LANG.LIN_HCES1_LANG','=','FGASIGL0.LINHCES_ASIGL0')
						->on('FGHCES1_LANG.LANG_HCES1_LANG','=',"'".$lang."'");
				});
	}

	#Requiere que se haya hecho el join a FgSub
	public function scopeJoinLangSubastaFavorites($query){
		$query = $query->leftjoin('FGSUB_LANG', function($q)
				{
					$lang = ToolsServiceProvider::getLanguageComplete(\Config::get('app.locale'));
					$q	->on('FGSUB.EMP_SUB','=','FGSUB_LANG.EMP_SUB_LANG')
						->on('FGSUB.COD_SUB','=','FGSUB_LANG.COD_SUB_LANG')
						->on('FGSUB_LANG.LANG_SUB_LANG','=',"'".$lang."'");
				});
	}

	#Requiere que se haya hecho el join a Asigl0
	public function scopeJoinSessionFavorites($query){
		$query = $query->join('"auc_sessions" auc', function($q)
				{
					$q	->on('auc."company"','=','FGASIGL0.EMP_ASIGL0')
						->on('auc."auction"','=','FGASIGL0.SUB_ASIGL0')
						->on('auc."init_lot"','<=','FGASIGL0.REF_ASIGL0')
						->on('auc."end_lot"','>=','FGASIGL0.REF_ASIGL0');
				});

	}

	public function scopeJoinAsigl1Favorites($query){
		$query = $query->join('FGASIGL1', function($q)
				{
					$q	->on('FGASIGL1.EMP_ASIGL1','=','WEB_FAVORITES.ID_EMP')
						->on('FGASIGL1.SUB_ASIGL1','=','WEB_FAVORITES.ID_SUB')
						->on('FGASIGL1.REF_ASIGL1','=','WEB_FAVORITES.ID_REF');
				});
	}

	public function scopeJoinLicitFavorites($query)
	{
		$query = $query->join('FGLICIT', function($q)
				{
					$q	->on('FGLICIT.EMP_LICIT','=','WEB_FAVORITES.ID_EMP')
						->on('FGLICIT.CLI_LICIT','=','WEB_FAVORITES.COD_CLI')
						->on('FGLICIT.SUB_LICIT','=','WEB_FAVORITES.ID_SUB');
				});
	}


}
