<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\V5;

/**
 * Description of Customer_Presta
 *
 * @author enadal
 */
class Address_Presta{
    
    public $id_customer;
    public $alias;
    public $dni;
    public $lastname;
    public $firstname;
    public $address1;
    public $city;
    public $id_country;
    
    public $postcode;
    public $phone;
    public $id_state; //provincias en España

    public function __construct($id_customer, $alias, $dni, $lastname, $firstname, $address1, $city, $id_country, $postcode = "", $phone = "", $id_state = ""){
        //requeridos
        $this->id_customer = $id_customer;
        $this->alias = $alias;
        $this->dni = $dni;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->address1 = $address1;
        $this->city= $city;
        $this->id_country = $id_country;
        
        //Opcionales
        $this->postcode = $postcode;
        $this->phone = $phone;
        $this->id_state = $id_state;
    }
    
    public function getXml(){
        return <<<XML
        <prestashop>
            <address>
                <id_customer>$this->id_customer</id_customer>
                <id_country>$this->id_country</id_country>
                <id_state>$this->id_state</id_state>
                <alias>$this->alias</alias>
                <lastname>$this->lastname</lastname>
                <firstname>$this->firstname</firstname>
                <address1>$this->address1</address1>
                <address2></address2>
                <postcode>$this->postcode</postcode>
                <city>$this->city</city>
                <phone>$this->phone</phone>
                <phone_mobile></phone_mobile>
                <dni>$this->dni</dni>
            </address>
        </prestashop>
XML;
    }

}
