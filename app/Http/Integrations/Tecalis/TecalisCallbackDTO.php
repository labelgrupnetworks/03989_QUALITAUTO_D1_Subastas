<?php

namespace App\Http\Integrations\Tecalis;

use Illuminate\Http\Request;

class TecalisCallbackDTO
{
	const VERIFICATION_OK = 'Verification OK';

	public function __construct(
		public string $status,
		public string $auth_uuid,
		public string $op_uuid
	) {
	}

	public static function fromRequest(Request $request): self
	{
		return new self(
			$request->input('status'),
			$request->input('auth_uuid'),
			$request->input('op_uuid')
		);
	}

	public function isVerificationOk(): bool
	{
		return $this->status === self::VERIFICATION_OK;
	}
}
