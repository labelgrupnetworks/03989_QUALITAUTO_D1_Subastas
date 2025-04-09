<?php

namespace App\Services\Content;

use App\Models\V5\FsEmpres;

class EnterpriseParamsService
{
	public function getCompany()
	{
		return FsEmpres::first();
	}
}
