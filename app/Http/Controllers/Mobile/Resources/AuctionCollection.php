<?php

namespace App\Http\Controllers\Mobile\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AuctionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

		$filtersApplied = $request->all();
		$filtersApplied = array_filter($filtersApplied, function($value) {
			return $value !== null;
		});


        return [
            'data' => $this->collection,
			'status' => 'success',
            'links' => [
                'self' => 'link-value',
            ],
			'filters' => [
				'applied' => $filtersApplied,
				'available' => [
					'lang' => ['ES', 'EN'],
					'status' => ['S', 'H']
				]
			]
        ];
    }
}
