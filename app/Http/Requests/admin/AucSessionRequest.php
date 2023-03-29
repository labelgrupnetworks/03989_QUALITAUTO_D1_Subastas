<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class AucSessionRequest extends FormRequest
{

	public static function myRules()
    {

		return [
			'id_auc_sessions' => "required|alpha_num|max:8",
			'reference' => "required|alpha_num|max:3",
			'name' => "required|max:65",
			'description' => "required|max:1000",
			'start' => "date_format:Y-m-d H:i:s|nullable",
			'finish' => "date_format:Y-m-d H:i:s|nullable",
			'orders_start' => "date_format:Y-m-d H:i:s|nullable",
			'orders_end' => "date_format:Y-m-d H:i:s|nullable",
			'init_lot' => "required|numeric|max:999999" ,
			'end_lot' => "required|numeric|max:999999",
			"image_session" => "image|mimes:jpeg,png,jpg",
			//'phoneorders' => "nullable|alpha_num|max:1",
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
