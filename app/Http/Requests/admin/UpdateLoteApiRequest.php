<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoteApiRequest extends FormRequest
{

	public static function myRules()
    {

		return [
			'idorigin' => "required|max:255",
			'idauction' => "required|alpha_num|max:8",
			'reflot' => "required|numeric|max:999999999",
			'idsubcategory' => "required|alpha_num|max:2",
			'title' => "required",
			'description' => "required",
			'extrainfo' => "nullable",
			'search' => "nullable",
			'startprice' => "required|numeric|min:0",
			'lowprice' => "numeric|nullable",
			'highprice' => "numeric|nullable",
			'reserveprice' => "numeric|nullable",
			'highlight' => "filled|alpha|max:1",
			'close' => "filled|alpha|max:1",
			'buyoption' => "filled|alpha|max:1",
			'soldprice' => "filled|alpha|max:1",
			'retired' => "filled|alpha|max:1",
			'hidden' => "filled|alpha|max:1",
			'disclaimed' => "filled|alpha|max:1",
			'startdate' => "date_format:Y-m-d|nullable",
			'enddate' => "date_format:Y-m-d|nullable",
			'starthour' => "date_format:H:i:s|nullable",
			'endhour' => "date_format:H:i:s|nullable",
			'feature' => "array",
			"costprice" => "numeric|nullable",
			"biddercommission" => "numeric|nullable",
			"biddercommissionini" => "numeric|nullable",
			"ownercommission" => "numeric|nullable",
			"ownercommissionini" => "numeric|nullable",
			"warehouse" =>"alpha_num|max:9|nullable",
			"numberobjects" => "numeric|nullable",
			"high" => "numeric|nullable",
			"width" => "numeric|nullable",
			"diameter" => "numeric|nullable",
			"thickness" => "numeric|nullable",
			"weight" => "numeric|nullable",
			"volumetricweight" => "numeric|nullable",
			"video" =>  "filled|alpha|max:1",
			"ministry" =>"alpha_num|max:1|nullable",
			"exportpermission" =>"alpha_num|max:1|nullable",
			"order" =>"numeric|nullable",
			"maxbid" => "numeric|nullable",
			"infoforauctioner" =>"alpha_num|max:2000|nullable" ,
			"owner" =>"alpha_num|max:8|nullable",
			"imgfriendly" =>"max:256|nullable",
			"htmlcontent" => "nullable",
			"label" =>"numeric|nullable",
			"withstock" => "alpha_num|max:1|nullable",
			"stock" => "numeric|nullable",
			"idexternal" => "alpha_num|max:30|nullable",
			"other_id" => "max:30|nullable",
			"note" => "nullable",
			"createdate" => "date_format:Y-m-d|nullable",
			//"network_nft" => "required_if:es_nft_asigl0,S"
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
