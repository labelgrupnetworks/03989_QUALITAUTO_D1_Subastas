<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;


class CollectionExport extends StringValueBinder implements WithHeadings, ShouldAutoSize, FromCollection
{
	use Exportable;

	public $collection;
	public string $translateParam;

	public function __construct($collection, $translateParam = 'admin-app.fields')
	{
		$this->collection = $collection;
		$this->translateParam = $translateParam;
	}

	public function collection()
	{
		return $this->collection;
	}

	public function headings(): array
	{
		$collectionKeys = array_keys($this->collection->first()->toArray());
		$headings = [];
		foreach ($collectionKeys as $key) {
			$headings[] = trans($this->translateParam . '.' . $key);
		}

		return $headings;
	}

}
