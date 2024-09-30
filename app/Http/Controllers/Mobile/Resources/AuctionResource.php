<?php

namespace App\Http\Controllers\Mobile\Resources;

use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
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
            'codsession' => $this->id_auc_sessions,
			'title' => $this->name,
			'type' => $this->tipo_sub,
			'status' => $this->subc_sub,
			'image' => $this->getAuctionImage($this->cod_sub, $this->reference),
			'start' => $this->startDate(),
			'description' => $this->description,
			'files' => $this->files ?? [],
			'links' => [
				'self' => route('mobile.auction', ['codsession' => $this->id_auc_sessions]),
			],
        ];
    }

	private function startDate()
	{
		//En historica no tienen start
		if($this->subc_sub !== 'S') {
			return null;
		}

		return $this->tipo_sub == 'O' ? $this->session_end : $this->session_start;
	}

	private function getAuctionImage($cod_sub, $reference)
	{
		#intentamos conseguir imagen de sesión
		$image_to_load = ToolsServiceProvider::url_img_session("subasta_large", $cod_sub, $reference);

		#si no existe conseguimos la imagen de la subasta\
		if (!file_exists($image_to_load) || filesize($image_to_load) < 500) {
			$image_to_load = ToolsServiceProvider::url_img_auction("subasta_large", $cod_sub);
		}

		return $image_to_load;
	}
}
