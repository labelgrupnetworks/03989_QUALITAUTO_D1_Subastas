<?php
namespace App\Http\Controllers\externalws\bogota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * El archivo no se esta utilizando, pero lo mantengo porque contiene los metodos para la integración tanto con
 * Zoho Invoice, como con Zoho CRM. por si fuesen necesarios.
 */
class ZohoController extends Controller
{

	/**
	 * Metodo de prueba para la integracion con Zoho Invoice
	 */
    public function auth(Request $request)
    {
        $uri = route('zohocrm');
        $scope =  'ZohoInvoice.contacts.Create';
        $clientid = config('app.zoho_client_id');
        $accestype = 'offline';

        $redirectTo = 'https://accounts.zoho.com/oauth/v2/auth' . '?' . http_build_query(
        [
			'client_id' => $clientid,
			'redirect_uri' => $uri,
			'scope' => $scope,
			'response_type' => 'code',
			'access_type' => $accestype,
        ]);

        //\Session()->put('zoho_contact_id', $request->id);

        return redirect($redirectTo);
    }

	/**
	 * Metodo de prueba para la integracion con Zoho Invoice
	 */
    public function store(Request $request)
    {
		$input = $request->all();
		$code = $input['code'];
		//$code = "1000.4b9f999c196499748128de039b01c182.10a3cb6e67948911d4465c851af27074";
        $contact_id = '1';
        $client_id = config('app.zoho_client_id');
        $client_secret = config('app.zoho_client_secret');

        // Get ZohoCRM Token
        $tokenUrl = 'https://accounts.zoho.com/oauth/v2/token?code='.$code.'&client_id='.$client_id.'&client_secret='.$client_secret.'&redirect_uri='.route('zohocrm').'&grant_type=authorization_code';
        $tokenData = [];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //comentar en produccion
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); //comentar en produccion
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_POST, TRUE);//Regular post
        curl_setopt($curl, CURLOPT_URL, $tokenUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($tokenData));

        $tResult = curl_exec($curl);
        curl_close($curl);
        $tokenResult = json_decode($tResult);

        if(isset($tokenResult->access_token) && $tokenResult->access_token != '') {
            //$getContact = Contact::where('id', $contact_id)->first();
            // Add Contact in ZohoCRM
            $jsonData = '{
                "contact_name": "Mi nombre",
                "company_name": "La mia",
                "website": "",
                "billing_address": {
                    "attention": "Mr.Mi nombre",
                    "address": "",
                    "street2": "",
                    "state_code": "",
                    "city": "",
                    "state": "",
                    "zip": "",
                    "country": "Spain",
                    "fax": "",
                    "phone": "666777888"
                },
                "shipping_address": {
                    "attention": "Mr.Mi nombre",
                    "address": "",
                    "street2": "",
                    "state_code": "",
                    "city": "",
                    "state": "",
                    "zip": "",
                    "country": "Spain",
                    "fax": "",
                    "phone": "666777888"
                },
                "contact_persons": [
                    {
                        "salutation": "Mr",
                        "first_name": "Mi nombre",
                        "last_name": "",
                        "email": "",
                        "phone": "666777888",
                        "mobile": "666777888",
                        "is_primary_contact": true
                    }
                ]
            }';

            $curl = curl_init('https://invoice.zoho.com/api/v3/contacts');
            curl_setopt($curl, CURLOPT_VERBOSE, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //commentar en produccion
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); //commentar en produccion
            curl_setopt($curl, CURLOPT_TIMEOUT, 300);
            curl_setopt($curl, CURLOPT_POST, TRUE);//Regular post
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Authorization: Zoho-oauthtoken ".$tokenResult->access_token,
                "X-com-zoho-invoice-organizationid: ".config('app.zoho_organization_id'),
            ) );
            curl_setopt($curl, CURLOPT_POSTFIELDS,'JSONString='.$jsonData);

            $cResponse = curl_exec($curl);
            curl_close($curl);

            $contactResponse = json_decode($cResponse);

            if(isset($contactResponse->code) && $contactResponse->code == 0) {
                \Session::put('success','Contact created in ZohoCRM successfully.!');
                return redirect()->route('home');
            } else {
                \Session::put('error','Contact not create, please try again.!!');
                return redirect()->route('home');
            }
        } else {
            \Session::put('error','ZohoCRM token not generated, please try again.!!');
            return redirect()->route('home');
        }
    }

	/**
	 * Metodo de prueba para la integracion con Zoho CRM
	 */
	public function storeWithCurl() {

		$function = 'create_contact';
		$apiKey = '1003.a1c70826ffffe77364be3e836eac66e8.d3194b509770e42de930596fe0098895'; //api key de test

		$contact = [
			'First_Name' => 'Contact',
			'Last_Name' => 'Test',
			'Email' => 'test@laravel.com',
			'Phone' => 'Phone',
			'Date_of_Birth' => '2000/01/01',
			'Mailing_City' => 'Barcelona',
			'Mailing_Street' => 'Carrer de la Rambla',
			'Mailing_Zip' => '08008',
			'Mailing_Country' => 'Spain',
			'Lead_Source' => 'Casos de la Web',
		];

		$client = [
			'FirstName' => 'TestJson',
			'LastName' => 'LaravelJ',
			'Company' => 'Zylketr',
			'Mobile' => '555-876-4347'
		];

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://www.zohoapis.eu/crm/v2/functions/$function/actions/execute?auth_type=apikey&zapikey=$apiKey",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false, //commentar en produccion
            CURLOPT_SSL_VERIFYHOST => false, //commentar en produccion
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('arguments' => json_encode($contact))
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response, true);
		//{"code":"success","details":{"output":"Contacto creado correctamente. conctactId: null","output_type":"string","id":"130292000000008001"},"message":"function executed successfully"}
	}
}
