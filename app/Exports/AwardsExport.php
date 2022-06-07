<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;

class AwardsExport implements FromArray, WithHeadings, ShouldAutoSize
{
	use Exportable;

	public function __construct($array, $headers)
	{
		$this->array = $array;
		$this->headers = $headers;
	}

	public function array(): array
    {
        return $this->array;
    }

	public function headings(): array
	{
		return $this->headers;
	}
}
