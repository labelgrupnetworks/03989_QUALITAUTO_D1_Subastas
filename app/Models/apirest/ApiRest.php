<?php

namespace App\Models\apirest;

use Illuminate\Database\Eloquent\Model;

use App\Models\apirest\UserApiRest;

class ApiRest extends Model
{
    public function __construct(){

        //\Config::set('app.emp', '001');

    }

    public function generateFilter($sql,$like = array(),$where = array()){

        foreach($like as $key => $val){

            $sql->where($key,'like', '%'.$val.'%');
        }

        foreach($where as $key => $val){

            $sql->where(strtoupper($key),strtoupper($val));
        }

        return $sql;
    }
}
