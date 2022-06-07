<?php

# Ubicacion del modelo

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Web_Faq extends Model {

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.
    protected $table = 'Web_Faq';
    protected $primaryKey = 'EMP_FAQ, COD_FAQ, LANG_FAQ';
    //protected $connection = 'SUBALIA';
    public $timestamps = false;
    public $incrementing = false;
    //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_faq' => \Config::get("app.main_emp")

        ];
        parent::__construct($vars);
    }

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_faq', \Config::get("app.main_emp"));
        });
	}

}
