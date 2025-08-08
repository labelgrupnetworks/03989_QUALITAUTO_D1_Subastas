<?php

namespace App\DataTransferObjects\User;

class SubaliaPostUserDTO
{

	public function __construct(
		public string $nombre1,
		public string $nombre2,
		public string $email_cli,
		public string $nom_cli,
		public string $tel1_cli,
		public string $cif_cli,
		public string $codpais_cli,
		public string $cp_cli,
		public string $pob_cli,
		public string $pro_cli,
		public string $dir_cli,
		public string $sg_cli,
		public string $fecnac_cli,
		public string $sexo_cli,
		public string $fisjur_cli
	) {}

	public static function fromArray(array $data): self
	{
		return new self(
			$data['nombre1'] ?? '',
			$data['nombre2'] ?? '',
			$data['email_cli'] ?? '',
			$data['nom_cli'] ?? '',
			$data['tel1_cli'] ?? '',
			$data['cif_cli'] ?? '',
			$data['codpais_cli'] ?? '',
			$data['cp_cli'] ?? '',
			$data['pob_cli'] ?? '',
			$data['pro_cli'] ?? '',
			$data['dir_cli'] ?? '',
			$data['sg_cli'] ?? '',
			$data['fecnac_cli'] ?? '',
			$data['sexo_cli'] ?? '',
			$data['fisjur_cli'] ?? ''
		);
	}
}
