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



}
