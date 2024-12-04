<?php

namespace App\Imports\b2b;

use App\Http\Services\b2b\UserB2BData;
use App\Http\Services\b2b\UserB2BService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersB2BImport implements ToCollection, WithChunkReading, WithCalculatedFormulas, WithHeadingRow
{
	private $service;
	private $ownerCod;

	public function __construct(UserB2BService $service, $ownerCod)
	{
		$this->service = $service;
		$this->ownerCod = $ownerCod;
	}

    public function collection(Collection $rows)
    {
		foreach ($rows as $row)
        {
			try {
				$this->service->createInvitation($this->ownerCod, UserB2BData::fromArray($row->toArray()));
			} catch (\Throwable $th) {
				Log::error($th->getMessage());
			}
        }
        return $rows;
	}

	public function chunkSize(): int
    {
        return 100;
    }
}
