<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
{

	public static function myRules()
    {

		return [
			'nom_pro' => "required|max:40",
			'pro_pro' => "required|alpha_num|max:30",
			'tel1_pro' => "required|alpha_num|max:40",
			'email_pro' => "required|email:rfc|max:80",
			'margen_pro' => "required|max:8",
			'baja_temp_pro' => "required|alpha_num|max:1",
			'sg_pro' => "nullable|max:2",
			'dir_pro' => "nullable|max:40",
			'pais_pro' => "nullable|max:50",
			'cp_pro' => "nullable|max:10",
			'pob_pro' => "nullable|max:40",
			'cif_pro' => "nullable|max:15",
			'contacto_pro' => "nullable|max:60",
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
