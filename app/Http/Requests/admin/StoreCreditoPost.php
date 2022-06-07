<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreditoPost extends FormRequest
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
			'sub_creditosub' => 'required',
			'cli_creditosub' => 'required',
			'actual_creditosub' => 'required|numeric',
			'nuevo_creditosub' => 'required|numeric',
			'fecha_creditosub' => 'required'
        ];
    }
}
