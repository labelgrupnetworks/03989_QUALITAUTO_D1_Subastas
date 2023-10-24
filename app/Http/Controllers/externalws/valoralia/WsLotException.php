<?php

namespace App\Http\Controllers\externalws\valoralia;

use Exception;

class WsLotException extends Exception
{
	public $request;
	public $response;

	public function __construct(
		$message,
		$request = null,
		$response = null
	) {
		parent::__construct($message);
		$this->request = $request;
		$this->response = $response;
	}

	public function getRequest()
	{
		return $this->request;
	}

	public function getResponse()
	{
		return $this->response;
	}
}
