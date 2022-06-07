<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubastasPost extends FormRequest
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

			"imagen_sub" => "image|mimes:jpeg,png,jpg",
			"cod_sub" => "required|alpha_num|max:8",
      		"des_sub" => "required|max:255",
      		"descdet_sub" => "required",
      		"tipo_sub" => "required|alpha_num|max:1",
      		"subc_sub" => "required|alpha_num|max:1",
      		"dfec_sub" => "required",
      		"dhora_sub" => "required",
      		"hfec_sub" => "required",
      		"hhora_sub" => "required"
        ];
    }
}
