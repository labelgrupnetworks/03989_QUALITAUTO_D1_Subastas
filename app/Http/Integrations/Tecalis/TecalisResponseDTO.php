<?php

namespace App\Http\Integrations\Tecalis;

class TecalisResponseDTO
{
	/**
	 * @param string $message Mensaje de respuesta.
	 * @param string $token_pwcs Token de autenticación.
	 * @param string $expireAt Fecha de expiración del token.
	 * @param string $pwcs_url URL para acceder automáticamente al formulario.
	 * @param string $sdr_frm_url URL para acceder a formulario donde introducir número de teléfono o email para recibir enlace a pwcs_url.
	 * @param string $pst_frm_url URL para enviar por post número de teléfono o email donde se enviarán enlace a pwcs_url.
	 * @param string $auth_uuid UUID único con el que se identificará el proceso.
	 */
	public function __construct(
        public string $message,
        public string $token_pwcs,
        public string $expireAt,
        public string $pwcs_url,
        public string $sdr_frm_url,
        public string $pst_frm_url,
        public string $auth_uuid
    ) {}

	public static function fromArray(array $data): self
	{
		return new self(
			data_get($data, 'message'),
			data_get($data, 'token_pwcs'),
			data_get($data, 'expireAt'),
			data_get($data, 'pwcs_url'),
			data_get($data, 'sdr_frm_url'),
			data_get($data, 'pst_frm_url'),
			data_get($data, 'auth_uuid')
		);
	}
}
