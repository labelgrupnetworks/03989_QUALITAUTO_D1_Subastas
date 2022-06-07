<?php

namespace App\Http\Requests\admin;

use App\Models\V5\FgSg;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
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

		$newsletter = [];
		for ($i=1; $i <= 20; $i++) {
			$newsletter["newsletter$i"] = 'alpha_num|max:1|nullable';
		}



		//Si se crea usuario web o no se añade idorigincli el email tiene que ser obligatorio
		return [
			"idorigincli" => "alpha_num|max:8",
			"codcli" => "alpha_num|max:8",
			"idnumber"=> "max:20",
			"email" => 'email|max:80|required_without:idorigincli|required_if:notwebuser,N',
			"password" => "max:256" ,
			"name"=>"max:60",
			"registeredname" => "max:60",
			"country" => "alpha|max:2",
			"province" => "max:30",
			"city" => "max:30",
			"zipcode" => "max:10",
			"address" => "max:60",
			"phone" => "max:40",
			"mobile" => "max:40",
			"fax" => "max:40",
			"legalentity" => "alpha_num|max:1",
			"notes" =>"max:200",
			"temporaryblock" => "alpha_num|max:1",
			"createdate" =>"date_format:Y-m-d H:i:s|nullable" ,
			"updatedate" => "date_format:Y-m-d H:i:s|nullable",
			"source" => "alpha_num|max:2|nullable",
			"documenttype" =>"alpha_num|max:1|nullable",
			"docrepresentative" => "max:20|nullable",
			"typerepresentative" => "alpha_num|max:1|nullable" ,
			"profession" => "max:15|nullable",
			"countryshipping" => "alpha|max:2",
			"namecountryshipping" => "max:50",
			"provinceshipping" => "max:30",
			"cityshipping" => "max:30",
			"zipcodeshipping" => "max:10",
			"addressshipping" => "max:60",
			"emailshipping" => "max:60",
			"phoneshipping" => "max:40",
			"mobileshipping" => "max:40",
			"enviocatalogo" => "alpha_num|max:1|nullable",
			"notwebuser" => "alpha_num|max:1|nullable",
			"prefix" => "alpha_num|max:4|nullable",
			"language" => "alpha_num|max:2|nullable",
			"track" => [
				"alpha_num", "max:2", "nullable",
				Rule::in(FgSg::getList()->keys())
			],
			"provenance" => "nullable"

		] + $newsletter;
    }
}
