<?php

namespace App\Exceptions\Auction;

class DepositAlreadyExistsException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
