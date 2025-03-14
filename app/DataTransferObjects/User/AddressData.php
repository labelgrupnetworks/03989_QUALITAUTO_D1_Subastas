<?php

namespace App\DataTransferObjects\User;

use Illuminate\Support\Str;

class AddressData
{
	public function __construct(
		public ?string $codd_clid,
		public ?string $clid_direccion,
		public ?string $clid_pais,
		public ?string $des_pais,
		public ?string $clid_poblacion,
		public ?string $clid_cpostal,
		public ?string $clid_codigoVia,
		public ?string $clid_provincia,
		public ?string $clid_rsoc,
		public ?string $usuario,
		public ?string $telefono,
		public ?string $email_clid,
		public ?string $cod2_clid,
		public ?string $preftel_clid,
		public ?string $rsoc2_clid,
		public ?string $mater_clid = 'N'
	) {}

	public static function fromArray(array $data): self
	{
		return new self(
			codd_clid: data_get($data, 'codd_clid'),
			clid_direccion: data_get($data, 'clid_direccion'),
			clid_pais: data_get($data, 'clid_pais', 'ES'),
			des_pais: data_get($data, 'des_pais'),
			clid_poblacion: data_get($data, 'clid_poblacion'),
			clid_cpostal: data_get($data, 'clid_cpostal'),
			clid_codigoVia: data_get($data, 'clid_codigoVia'),
			clid_provincia: data_get($data, 'clid_provincia'),
			clid_rsoc: data_get($data, 'clid_rsoc', data_get($data, 'rsoc')),
			usuario: data_get($data, 'usuario', data_get($data, 'usuario_clid')),
			telefono: data_get($data, 'telefono', data_get($data, 'clid_telf')),
			email_clid: data_get($data, 'email_clid'),
			cod2_clid: data_get($data, 'cod2_clid'),
			preftel_clid: data_get($data, 'preftel_clid', data_get($data, 'preftel_cli')),
			rsoc2_clid: data_get($data, 'rsoc2_clid'),
			mater_clid: data_get($data, 'mater_clid', 'N')
		);
	}

	public function setCoddClid(?string $codd_clid): self
	{
		$this->codd_clid = $codd_clid;
		return $this;
	}

	public function setDesPais(?string $des_pais): self
	{
		$this->des_pais = $des_pais;
		return $this;
	}

	public function toEloquentArray(): array
	{
		$data = [
			'codd_clid' => $this->codd_clid,
			'dir_clid' => Str::substr($this->clid_direccion, 0, 30),
			'dir2_clid' => Str::substr($this->clid_direccion, 30, 30),
			'cp_clid' => $this->clid_cpostal,
			'pob_clid' => $this->clid_poblacion,
			'codpais_clid' => $this->clid_pais,
			'pais_clid' => $this->des_pais,
			'sg_clid' => $this->clid_codigoVia,
			'pro_clid' => Str::substr($this->clid_provincia, 0, 30, 'UTF-8'),
			'nomd_clid' => $this->usuario,
			'tel1_clid' => $this->telefono,
			'rsoc_clid' => $this->clid_rsoc,
			'email_clid' => $this->email_clid,
			'cli2_clid' => $this->cod2_clid,
			'preftel_clid' => $this->preftel_clid,
			'rsoc2_clid' => $this->rsoc2_clid,
			'mater_clid' => $this->mater_clid,
			'tipo_clid' => 'E',
		];

		//evitamos sobreescribir valores nulos
		return array_filter($data, fn($value) => $value !== null);
	}
}
