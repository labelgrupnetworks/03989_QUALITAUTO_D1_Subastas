<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ViewExcelExport implements FromView
{

	public function __construct($artists,$caracteristicas,$lots,$auction)
	{
		$this->artists = $artists;
		$this->caracteristicas = $caracteristicas;
		$this->lots = $lots;
		$this->auction = $auction;
	}

    public function view(): View
    {
        return view('front::reports.expoArtExcel', [
			'artists' => $this->artists,
			'caracteristicas' => $this->caracteristicas,
			'lots' => $this->lots,
			'auction' => $this->auction
		]);
    }
}
