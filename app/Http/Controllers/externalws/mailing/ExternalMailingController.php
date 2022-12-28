<?php

namespace App\Http\Controllers\externalws\mailing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\externalws\mailing\services\ExternalMailingService;
use App\Jobs\UniversalJob;

class ExternalMailingController extends Controller
{
	public $service;

	function __construct(ExternalMailingService $service)
	{
		$this->service = $service;
	}

	function add($email_cli)
	{
		UniversalJob::dispatch(get_class($this->service), 'subscribe', $email_cli)->onQueue(config('app.queue_env'));
		//return $this->service->subscribe($email_cli);
	}

	function remove($email_cli)
	{
		UniversalJob::dispatch(get_class($this->service), 'unsuscribe', $email_cli)->onQueue(config('app.queue_env'));
		//return $this->service->unsuscribe($email_cli);
	}
}
