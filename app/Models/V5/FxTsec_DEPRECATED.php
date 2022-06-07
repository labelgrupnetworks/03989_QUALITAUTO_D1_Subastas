<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;


class FxTsec extends Model
{
    protected $table = 'FXTSEC';
    protected $primaryKey = 'GEMP_TSEC, COD_TSEC';
    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];
    protected $attributes = [];

    #definimos la variable gemp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_tsec' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }



}
