<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Monolog\Formatter\WildfireFormatter;

use function PHPSTORM_META\map;

class SiteMapImport implements ToCollection, WithHeadingRow, WithChunkReading
{
	use Importable;

    public function collection(Collection $rows)
    {
		return $rows;
	}

	public function chunkSize(): int
    {
        return 100;
    }
}
