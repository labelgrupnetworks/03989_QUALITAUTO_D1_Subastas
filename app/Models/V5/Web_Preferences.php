<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;


class Web_Preferences extends Model
{
    protected $table = 'Web_Preferences';
    protected $primaryKey = 'ID_PREF_KEY';
    public $timestamps = false;
    public $incrementing = true;
 	//public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];


	#definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_pref' => \Config::get("app.emp")
		];

        parent::__construct($vars);
    }

	# scope para unir FXCLIWEB con WEB_PREFERENCES
	public function scopeJoinUsersPreferences($query)
	{
        return $query->join('FXCLIWEB', 'FXCLIWEB.EMP_CLIWEB = WEB_PREFERENCES.EMP_PREF AND FXCLIWEB.COD_CLIWEB = WEB_PREFERENCES.COD_CLIWEB_PREF');
    }

	# scope para unir FGORTSEC0 con WEB_PREFERENCES
	public function scopeJoinOrtsec0Preferences($query)
	{
        return $query->leftjoin('FGORTSEC0', "FGORTSEC0.EMP_ORTSEC0 = WEB_PREFERENCES.EMP_PREF AND FGORTSEC0.LIN_ORTSEC0 = WEB_PREFERENCES.LIN_ORTSEC0_PREF AND FGORTSEC0.SUB_ORTSEC0 = '0'");
	}

	# scope para unir FXSEC con WEB_PREFERENCES
	public function scopeJoinSecPreferences($query)
	{
        return $query->leftjoin('FXSEC', "FXSEC.GEMP_SEC = '". config('app.gemp') ."'  AND FXSEC.COD_SEC = WEB_PREFERENCES.COD_SEC_PREF");
	}


}
