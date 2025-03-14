<?php

namespace App\Http\Requests\User;

use App\DataTransferObjects\User\AddressData;
use Illuminate\Foundation\Http\FormRequest;


/**
 * Validar los datos de registro de un usuario
 * @todo no esta terminado ni implementado
 */
class AddressRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'codd_clid' => 'max:4',
			'clid_direccion' => 'max:60',
			'clid_pais' => 'max:2',
			'clid_poblacion' => 'max:30',
			'clid_cpostal' => 'max:10',
			'clid_codigoVia' => 'max:2',
			'clid_provincia' => 'max:30',
			'clid_rsoc' => 'max:50', //solo me sale en tauler
			'rsoc' => 'max:50',
			'usuario' => 'max:60',
			'usuario_clid' => 'max:60',
			'telefono' => 'max:40',
			'email_clid' => 'max:80',
			'preftel_clid' => 'max:4',
			'preftel_cli' => 'max:4',
			'rsoc2_clid' => 'max:40'
		];
	}

	public function toDTO() :AddressData
	{
		return AddressData::fromArray($this->validated());
	}
}
