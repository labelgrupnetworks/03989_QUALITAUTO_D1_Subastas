<?php

namespace App\Http\Controllers;

use Redirect;

//opcional
use App;
use DB;
use Request;
use Validator;
use Input;
use Session;
use Routing;
use Config;
use Route;
use SimpleXMLElement;

use App\libs\PrestaShopWebservice;
use App\Models\V5\Customer_Presta as CustomerPresta;
use App\Models\V5\Address_Presta as AddressPresta;


/**
 * @Pendiente
 * Mover variables de entorno a .env
 */
class PrestashopController extends Controller{

    public $webService;

     public function __construct(){
         if (!defined('PS_SHOP_PATH')){
            define('PS_SHOP_PATH', Config::get("app.ps_shop_path"));  // Root path of your PrestaShop store
         }
         if (!defined('PS_WS_AUTH_KEY')){
            define('PS_WS_AUTH_KEY', Config::get("app.ps_ws_auth_key")); // Auth key (Get it in your Back Office)
         }
        // Crea la llamada con path y webServiceKey, ultimo parametro es para recibir respuesta en html
        $this->webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, false);
    }

    /**
     * Comprueba que exista cliente dado un mail
     * @param type $mail
     * @return boolean
     */
    public function existCustomer($mail){

        $xmlCustomers = $this->webService->get([
            'resource' => 'customers',
        ]);

        foreach ($xmlCustomers->customers->customer as $c) {

            $xmlCustomer =  $this->webService->get([
                        'resource' => 'customers',
                        'id' => $c['id']
            ]);

            if($xmlCustomer->customer->email == $mail){
                return true;
            }
        }

        return false;
    }


    /**
     * Crea un usuario si este no existe
     * @param CustomerPresta $customer
     * @return boolean
     */
    public function createCustomer(CustomerPresta $customer) {

        if ($this->existCustomer($customer->email)){
            return false;
        }

        $xml = new SimpleXMLElement($customer->getXml());
        $newCustomer = $this->webService->add([
            'resource' => 'customers',
            'postXml' => $xml->asXML()
        ]);

         return $newCustomer;
    }

    /**
     * Añade una dirección a un usuario
     * @param AddressPresta $address
     * @return type
     */
    public function createAddress(AddressPresta $address) {

        $xml = new SimpleXMLElement($address->getXml());
        $newAddress = $this->webService->add([
            'resource' => 'addresses',
            'postXml' => $xml->asXML()
        ]);

        $addressXML = $newAddress->address[0];
        return $addressXML->id;
    }

    public function getIdCountry($isoCode){

        $xml = $this->webService->get([
            'resource' => 'countries'
        ]);

        $countries = array();
        foreach ($xml->countries->country as $c) {
            $country_id = $c['id'];
            $country = $this->webService->get([
                        'resource' => 'countries',
                        'id' => $country_id
            ]);

            if ( strcmp( strtolower($isoCode), strtolower($country->country->iso_code) ) == 0 ){
                return ($country->country->id);
            }
        }

        return false;

    }


    /**
     * Recupera todos los clientes
     * @return array([
     *      ids => ids todos los clientes,
     *      customers => clientes y todos sus atributos
     * ])
     */
    public function getAllCustomers() {

        $data = array();
        /**
         * Realizamos la llamada a la plantilla xml que necesitamos.
         * Dejo ejemplo de hacerlo con variable
         *
         * $opt['resource'] = 'customers';
         * $xml = $webService->get($opt);
         */

        $xml = $this->webService->get([
            'resource' => 'customers'
        ]);

        /**
         * Recorremos array de ids recibidas esta vez volviendo a llamar a los recuros, pero esta vez pasando el parametro que buscamos.
         */
        $customers = array();
        foreach ($xml->customers->customer as $c) {
            $customer_id = $c['id'];
            array_push($customers, $this->webService->get([
                        'resource' => 'customers',
                        'id' => $customer_id
            ]));
        }

        $data['ids'] = $xml->customers->children();
        $data['customers'] = $customers;

        return $data;
    }



    /**
     * @deprecated
     * @see PrestashopController::createAddress
     * No se esta utilizando, peor lo guardo como ejemplo por si alguna vez hace falta
     * Metodo llamando recuperando el xml directamente del webservice
     *
     * @param AddressPresta $address
     */
    public function createAddress_deprecated(AddressPresta $address) {

        $xmlResponse = $this->webService->get(['url' => PS_SHOP_PATH . '/api/addresses?schema=blank']);
        $addressXML = $xmlResponse->address[0];

        $addressXML->id_customer = $address->id_customer;
        $addressXML->alias = $address->alias;
        $addressXML->dni = $address->dni;
        $addressXML->lastname = $address->lastname;
        $addressXML->firstname = $address->firstname;
        $addressXML->address1 = $address->address1;
        $addressXML->city = $address->city;
        $addressXML->id_country = $address->id_country;

        /*
        //Parametros requeridos
        $addressXML->id_customer = 12;
        $addressXML->alias = 'Dirección de prueba';
        $addressXML->dni = '47772418S';
        $addressXML->lastname = 'Eloy';
        $addressXML->firstname = 'n';
        $addressXML->address1 = 'mi direccion';
        $addressXML->city = 'Barcelona';
        $addressXML->id_country = 6;

        //Parametros opcionales
        $addressXML->postcode = '08830';
        $addressXML->address2 = 'mi direccion 2';
        $addressXML->phone = '977554433';
        $addressXML->phone_mobile = '677554433';
         *
         */


        //Las excepciones las captura antes la libreria "webService", por lo que no se hasta que punto haría falta el try catch (probar debug false)
        try {
            $addedAddressResponse = $this->webService->add([
                'resource' => 'addresses',
                'postXml' => $xmlResponse->asXML(),
            ]);
            $addressXML = $addedAddressResponse->address[0];
            echo sprintf("Successfully create address with ID: %s", (string) $addressXML->id);
        } catch (PrestaShopWebserviceException $e) {
            echo $e->getMessage();
        }
    }

}
