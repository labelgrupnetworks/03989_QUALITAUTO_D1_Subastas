<?php

namespace App\Http\Integrations\PrestaShop;

use App\Http\Integrations\PrestaShop\DTO\Address;
use App\Http\Integrations\PrestaShop\DTO\Customer;
use Illuminate\Support\Facades\Config;
use SimpleXMLElement;

class PrestashopService
{
	public $connector;

	public function __construct()
	{
		$this->connector = new PrestashopConnector();
	}

	/**
	 * Crear cliente en prestashop
	 * @param object $user - no existe el objeto user, montar antes un dto con los datos necesarios
	 * @param string $password - El password del usuario tiene que ser antes de encriptar
	 */
	public function createClient($user, $password)
	{
		if(!Config::get("app.ps_activate", 0)){
			return false;
		}

		$last = $user->last_name;
		$name = $user->name;

		$resp = $this->addUserToPresta($password, $last, $name, $user->email, $user->sexo, $user->fecnac_cli);

		if ($resp != false) {
			$id_Presta = $resp->customer->id;
			$this->addAressToPresta($id_Presta, $user->nif, $last, $name, $user->codigoVia . " " . $user->direccion, mb_substr($user->poblacion, 0, 30, 'UTF-8'), $user->clid_pais, $user->cpostal, $user->telefono);
		}
	}

	/**
	 * Crear usuario en prestashop
	 * @param string $passwd contraseña
	 * @param string $lastname apellido
	 * @param string $firstname nombre
	 * @param string $email mail
	 * @param string $id_gender genero(H | M)
	 * @param string $birthday fecha nacimiento
	 * @return SimpleXMLElement
	 */
	private function addUserToPresta(string $passwd, string $lastname, string $firstname, string $email, string $id_gender, string $birthday)
	{
		if (empty($passwd) || empty($lastname) || empty($firstname) || empty($email) || empty($id_gender) || empty($birthday)) {
			return false;
		}

		$birthday_cli_temp = $birthday;
		$birthday_cli = date('Y-m-d', strtotime($birthday_cli_temp));

		$sexo = ($id_gender == "H") ? 1 : 2;
		$customer = new Customer($passwd, $lastname, $firstname, $email, $sexo, $birthday_cli);

		$result = $this->connector->createCustomer($customer);

		return $result;
	}

	/**
	 *
	 * @PENDIENTE
	 *  - comprobar si getIdCountry no retorna valor
	 *
	 * @param type $id_customer
	 * @param type $alias
	 * @param type $dni
	 * @param type $lastname
	 * @param type $firstname
	 * @param type $address1
	 * @param type $city
	 * @param type $id_country codigo del pais, ej: "ES"
	 */
	private function addAressToPresta($id_customer, $dni, $lastname, $firstname, $address1, $city, $id_country, $postcode, $phone)
	{
		if (empty($id_customer) || empty($dni) || empty($lastname) || empty($firstname) || empty($address1) || empty($city) || empty($id_country)) {
			return false;
		}

		//convertir el codigo pais al id de pais en prestashop
		$id_country = $this->connector->getIdCountry($id_country);

		//crear un alias con apellido y direccion
		$alias = $lastname . "_address";

		$addressPresta = new Address($id_customer, $alias, $dni, $lastname, $firstname, $address1, $city, $id_country, $postcode, $phone);
		$this->connector->createAddress($addressPresta);
	}
}
