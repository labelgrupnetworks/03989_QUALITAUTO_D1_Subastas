<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;

class Web_Category_Blog_Lang extends Model
{
    protected $table = 'web_category_blog_lang';
	protected $primaryKey = null;

    public $timestamps = false;
    public $incrementing = false;
    protected $guarded = [];

	public function webCategory()
	{
		return $this->belongsTo('App\Models\V5\Web_Category_Blog', 'id_category_blog', 'id_category_blog_lang');
	}

}
