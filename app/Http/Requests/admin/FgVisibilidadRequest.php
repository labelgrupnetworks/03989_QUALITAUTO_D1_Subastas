<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class FgVisibilidadRequest extends FormRequest
{
	public static function myRules()
    {
		return [
			'cli_visibilidad' => 'alpha_num|max:8|nullable',
			'sub_visibilidad' => 'alpha_num|max:8|nullable',
			'ref_visibilidad' => 'numeric|nullable',
			'inverso_visibilidad' => 'alpha_num|max:1'
        ];
    }

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
		return $this->myRules();
    }
}
