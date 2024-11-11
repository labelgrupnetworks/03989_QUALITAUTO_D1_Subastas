<?php

namespace App\Http\View\Composers;

use App\Models\Subasta;
use Illuminate\View\View;

class GlobalComposer
{

	static $subastas;

	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{
		return $view->with('global', ['subastas' => Subasta::auctionsToViews()]);
	}
}
