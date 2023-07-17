<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class Web_Blog_Lang extends Model
{
	protected $table = 'WEB_BLOG_LANG';
	protected $primaryKey = 'ID_WEB_BLOG_LANG';

	public $timestamps = false;
	public $incrementing = false;
	//   public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];


}
