<?php

namespace App\Http\Controllers\Mobile\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'idcategory' => $this->lin_ortsec0,
			'description' => $this->des_ortsec0_lang ?? $this->des_ortsec0,
			'order' => $this->orden_ortsec0,
			'metadescription' => $this->meta_description_ortsec0_lang ?? $this->meta_description_ortsec0,
			'metatitle' => $this->meta_titulo_ortsec0_lang ?? $this->meta_titulo_ortsec0,
			'metacontent' => $this->meta_contenido_ortsec0_lang ?? $this->meta_contenido_ortsec0,
			'urlfriendly' => $this->key_ortsec0_lang ?? $this->key_ortsec0,
			'links' => [
				'self' => '',
			],
        ];
    }
}
