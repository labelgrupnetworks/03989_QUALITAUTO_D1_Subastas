<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ViewExcelExport implements FromView
{
	private $vars = [];
	private $view = [];
	public function __construct($view,$vars)
	{
		$this->view = $view;
		$this->vars = $vars;

	}

    public function view(): View
    {
        return view('front::reports.'.$this->view, $this->vars);
    }
}
