<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Validar los datos de registro de un usuario
 * @todo no esta terminado ni implementado
 */
class RegisterRequest extends FormRequest
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
		$requiredKeys = explode(",", Config::get('app.registerChecker' . $this->input('pri_emp'), ''));
		$passwordRules = ['required', 'min:5'];
		if (Config::get('app.strict_password_validation', false)) {
			$passwordRules = ['required', 'min:8', 'max:256', Password::min(8)->letters()->mixedCase()->numbers()->symbols()];
		}


		$rules = [
			'email' => [
				'required',
				'email',
				// comprobar que el email no exista en la tabla de usuarios
				// Rule::unique('App\Models\V5\FxCliWeb', 'usrw_cliweb')->where(function ($query) {
				// 	return $query->where('gemp_cliweb', Config::get('app.gemp'))->where('emp_cliweb', Config::get('app.emp'));
				// }),
			],
			'password' => $passwordRules,
			//'dni1' => [Rule::requiredIf(fn () => $this->has('dni1') && $this->file('dni1')), 'max:1000000', 'mimes:jpg,jpeg,png,pdf,webp,heic,heif,JPG,JPEG,PNG,PDF,WEBP,HEIC,HEIF'],
			'dni1' => ['max:1000000', 'mimes:jpg,jpeg,png,pdf,webp,heic,heif,JPG,JPEG,PNG,PDF,WEBP,HEIC,HEIF', 'required_with:dni2'],
			'dni2' => ['max:1000000', 'mimes:jpg,jpeg,png,pdf,webp,heic,heif,JPG,JPEG,PNG,PDF,WEBP,HEIC,HEIF', 'required_with:dni1'],
			'files_email.*' => 'max:20000|mimes:jpg,jpeg,png,tiff,bmp,gif,pdf',
		];

		array_walk($requiredKeys, function ($key) use (&$rules) {
			$rules[$key] = 'required';
		});

		return $rules;
	}

	/**
	 * Handle a passed validation attempt.
	 *
	 * @return void
	 */
	protected function passedValidation()
	{
		//transform al values in uppercase
		$strToDefault = Config::get('app.strtodefault_register', 0);
		if ($strToDefault) {
			$this->merge(array_map('strtoupper', $this->all()));
		}

		//añadir el campo files_email si no existe
		/* $this->merge([
			'files_email' => $this->file('files_email') ?? [],
		]); */
	}

	// Sobrescribe el método failedValidation
	protected function failedValidation(Validator $validator)
	{
		$data = array_filter($this->all(), fn($value, $key) => !in_array($key, ['password', 'confirm_password', '_token']), ARRAY_FILTER_USE_BOTH);

		Log::error('Validación fallida en RegisterRequest', [
			'errors' => $validator->errors(),
			'input' => $data
		]);

		// Puedes lanzar una excepción personalizada o la por defecto
		//throw new HttpResponseException(response()->json($validator->errors(), 422)); //personalizada
		parent::failedValidation($validator);
	}

}
