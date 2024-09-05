<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Integrations\PrestaShop\DTO;

/**
 * Description of Customer_Presta
 *
 * @author enadal
 */
class Customer
{

	public $id_default_group;
	public $passwd;
	public $lastname;
	public $firstname;
	public $email;
	public $id_gender;
	public $birthday;
	public $active;
	public $group;

	public function __construct($passwd, $lastname, $firstname, $email, $id_gender, $birthday, $active = 1, $id_default_group = "3", $group = "3")
	{
		$this->id_default_group = $id_default_group;
		$this->passwd = $passwd;
		$this->lastname = $lastname;
		$this->firstname = $firstname;
		$this->email = $email;
		$this->id_gender = $id_gender;
		$this->birthday = $birthday;
		$this->active = $active;
		$this->group = $group;
	}

	public function getXml()
	{
		return <<<XML
        <prestashop>
            <customer>
                <id_default_group>$this->id_default_group</id_default_group>
                <passwd>$this->passwd</passwd>
                <lastname>$this->lastname</lastname>
                <firstname>$this->firstname</firstname>
                <email>$this->email</email>
                <id_gender>$this->id_gender</id_gender>
                <birthday>$this->birthday</birthday>
                <active>$this->active</active>
                <associations>
                    <groups>
                        <group>
                            <id>$this->group</id>
                        </group>
                    </groups>
                </associations>
            </customer>
        </prestashop>
XML;
	}
}
