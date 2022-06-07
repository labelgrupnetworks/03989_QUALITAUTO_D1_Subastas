<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ExcelImport implements ToCollection, WithChunkReading, WithCalculatedFormulas
{
    public function collection(Collection $rows)
    {
        return $rows;
	}

	public function chunkSize(): int
    {
        return 100;
    }
}
