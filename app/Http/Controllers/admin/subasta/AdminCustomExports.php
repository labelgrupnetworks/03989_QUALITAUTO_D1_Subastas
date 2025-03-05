<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\custom\CustomExport;
use App\Http\Controllers\Controller;

class AdminCustomExports extends Controller
{
	public function download($id)
	{
		$export = (new CustomExport)->getExport($id);
		return $export->download();
	}
}
