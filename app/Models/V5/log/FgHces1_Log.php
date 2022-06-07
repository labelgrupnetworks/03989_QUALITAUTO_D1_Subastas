<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgHces1_Log extends Model
{
    protected $table = 'fghces1_log';
    protected $primaryKey = 'EMP_HCES1, NUM_HCES1, LIN_HCES1';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];


	public function scopelog($query){
        return $query->joinUsr()->select("FSUSR.NOM_USR, FGHCES1_LOG.*");
	}

	public function scopeJoinUsr($query){
        return $query->leftjoin("FSUSR","FSUSR.COD_USR = FGHCES1_LOG.USR_UPDATE_HCES1");
	}


}
