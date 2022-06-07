<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebNewbannerItemModel extends Model
{
    protected $table = 'web_newbanner_item';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];


}
