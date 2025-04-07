<?php

namespace App\Enums\User;

enum UserKycStatus: string
{
	case PENDING = 'PENDING';
	case APPROVED = 'APPROVED';
	case REJECTED = 'REJECTED';

	public function isPending(): bool
	{
		return $this === self::PENDING;
	}

	public function isApproved(): bool
	{
		return $this === self::APPROVED;
	}

	public function isRejected(): bool
	{
		return $this === self::REJECTED;
	}
}
