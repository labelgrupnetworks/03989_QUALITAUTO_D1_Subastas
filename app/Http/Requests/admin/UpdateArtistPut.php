<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\admin\V5\AdminArtistController as ADMIN;

class UpdateArtistPut extends FormRequest
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

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'id_artist' => 'nullable',
			'name_artist' => 'required|max:255',
			'info_artist' => 'max:255',
			'phone_artist' => 'nullable|max:255',
			'email_artist' => 'nullable|max:255',
			'idexternal_artist' => 'nullable|max:255',
			'biography_artist' => '',
			'extra_artist' => '',
			'active_artist' => 'nullable',
		];
	}
}
