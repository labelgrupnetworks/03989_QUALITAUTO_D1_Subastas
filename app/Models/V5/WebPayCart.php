<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

#TABLA DONDE GUARDAMOS LAS PETICIONES DE PAGO DEL CARRITO con códgi de pago
class WebPayCart extends Model
{
    protected $table = 'WEB_PAYCART';
    protected $primaryKey = 'IDTRANS_PAYCART, EMP_PAYCART';
    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

     #definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = []){
        $this->attributes=[
            'emp_paycart' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
	}

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_paycart', \Config::get("app.emp"));
        });
    }
}
