<?php

namespace App\Http\Services\b2b;

class UserB2BData
{
	public function __construct(
		public readonly string $name,
		public readonly string $email,
		public readonly ?string $idnumber,
		public readonly ?string $phone,
	) {}

	public static function fromArray(array $data): self
    {
        return new static(
            $data['name'],
            $data['email'],
			data_get($data, 'idnumber'),
			data_get($data, 'phone'),
		);
	}

	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'email' => $this->email,
			'idnumber' => $this->idnumber,
			'phone' => $this->phone,
		];
	}
}
