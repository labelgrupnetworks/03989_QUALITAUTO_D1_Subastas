<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;

class Web_Content_Resource extends Model
{
	protected $table = 'web_content_resource';
	protected $primaryKey = 'id_content';

	public $timestamps = false;
	public $incrementing = true;
	//public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];


	public function webContentPage()
	{
		return $this->hasOne(Web_Content_Page::class, 'type_id_content_page', 'id_content')->where('type_content_page', '!=', Web_Content_Page::TYPE_CONTENT_PAGE_HTML);
	}

}
