<?php

namespace App\Exports\custom;

use Maatwebsite\Excel\Concerns\Exportable;

abstract class BaseCustomExport {

	use Exportable {
		download as traitDownload;
	}

	protected $name;
	protected $downloadName;

	public function getDownloadName() :string
	{
		return $this->downloadName;
	}

	public function getName() :string
	{
		return $this->name;
	}

	public function download(?string $fileName = null, ?string $writerType = null, ?array $headers = null)
	{
		return $this->traitDownload($fileName ?? $this->getDownloadName(), $writerType, $headers);
	}
}
