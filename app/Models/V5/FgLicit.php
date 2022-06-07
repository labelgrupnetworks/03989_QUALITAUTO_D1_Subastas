<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgLicit extends Model
{
    protected $table = 'FGLICIT';
    protected $primaryKey = 'EMP_LICIT, SUB_LICIT, COD_LICIT';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_licit' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_licit', \Config::get("app.emp"));
        });
    }

       #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
    public function scopeWhereUpdateApi($query, $item){
        return $query->where('sub_licit', $item["sub_licit"])
                    ->where('cod_licit', $item["cod_licit"]);
    }

    public function scopeJoinCli($query){
        return $query->join("FXCLI", "GEMP_CLI = '".\Config::get("app.gemp") . "'  AND COD_CLI = CLI_LICIT");
    }

    static function getLicitsSubIdOrigin($codsub){
        $licits=array();
        $licitsTmp = self::select("cod_licit, cod2_cli, cod_cli")->where("sub_licit",$codsub)->joinCli()->get();
        foreach($licitsTmp as $licit){
            $licits[$licit->cod2_cli]= $licit->toarray();
        }
        return $licits;
    }




}
