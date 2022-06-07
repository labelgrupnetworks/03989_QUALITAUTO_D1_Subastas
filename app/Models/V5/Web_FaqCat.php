<?php

# Ubicacion del modelo

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Web_FaqCat extends Model {

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.
    protected $table = 'Web_FaqCat';
    protected $primaryKey = 'EMP_FAQCAT, COD_FAQCAT, LANG_FAQCAT';
    //protected $connection = 'SUBALIA';
    public $timestamps = false;
    public $incrementing = false;
    //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	public function __construct(array $vars = []){
        $this->attributes=[
			'emp_faqcat' => \Config::get("app.main_emp")

        ];
        parent::__construct($vars);
    }

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_faqcat', \Config::get("app.main_emp"));
        });
	}

    static function getInlineOrderedFaqCat() {

        $info = Web_FaqCat::where("EMP_FAQCAT", \Config::get("app.main_emp"))->get();
        $data = array();
        $cats = array();

        foreach ($info as $item) {
            $cats[$item->cod_faqcat] = $item;
        }

        foreach ($info as $item) {

            if (!$item->parent_faqcat) {
                continue;
            }

            $data[$item->cod_faqcat] = $cats[$item->parent_faqcat]['nombre_faqcat'] . " :: " . $cats[$item->cod_faqcat]['nombre_faqcat'];
        }

        return $data;
    }

}
