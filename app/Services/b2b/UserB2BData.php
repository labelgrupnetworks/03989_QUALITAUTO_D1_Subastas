<?php

namespace App\Services\b2b;

use App\Models\V5\FgSubInvites;

class UserB2BData
{
	public function __construct(
		public readonly string $name,
		public readonly string $email,
		public readonly ?string $idnumber,
		public readonly ?string $phone,
		public readonly ?string $id = null,
		public readonly ?string $hasPassword = null,
		public readonly ?string $linkResetPassword = null,
	) {}

	public static function fromArray(array $data): self
	{
		return new static(
			$data['name'],
			$data['email'],
			data_get($data, 'idnumber'),
			data_get($data, 'phone'),
			data_get($data, 'id'),
			data_get($data, 'hasPassword'),
			data_get($data, 'linkResetPassword'),
		);
	}

	public static function fromInvitationWithInvited(FgSubInvites $invitation): self
	{
		return new static(
			$invitation->invited_nom_subinvites,
			$invitation->invited->email_cliweb,
			$invitation->invited_cif_subinvites,
			$invitation->invited_tel_subinvites,
			$invitation->invited_codcli_subinvites,
			$invitation->invited->hasPassword,
			$invitation->invited->recoveryLink
		);
	}

	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'email' => $this->email,
			'idnumber' => $this->idnumber,
			'phone' => $this->phone,
			'hasPassword' => $this->hasPassword,
			'linkResetPassword' => $this->linkResetPassword,
		];
	}
}
