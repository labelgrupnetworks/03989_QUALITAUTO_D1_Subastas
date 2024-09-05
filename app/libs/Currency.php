<?php

namespace App\libs;

use App\Models\V5\FsDiv;
use App\Providers\ToolsServiceProvider;

/**
 * Description of Currency
 *
 * @author LABEL-JPALAU
 */
class Currency
{
	public function __construct(
		public $price = 0,
		public $cod = "",
		public $exchange = 0,
		public $symbol = "",
		public $position = 'R'
	) {
	}

	//carga el valor pasado por price y por la divisa indicada
	public function currency($price, $cod, $divori)
	{
		$this->setDivisa($cod, $divori);
		$this->price =  $price;
	}

	public function setDivisa($cod, $divori = "EUR")
	{
		$currency = FsDiv::query()
			->select('cod_div', 'des_div', 'impd_div', 'symbolhtml_div', 'pos_div')
			->where('cod_div', $cod)
			->where('divori_div', $divori)
			->first();

		if ($currency) {
			$this->exchange = $currency->impd_div;
			$this->cod = $currency->cod_div;
			$this->symbol = $currency->symbolhtml_div;
			$this->position = $currency->pos_div;
		}

		return $this;
	}

	/**
	 * devuelve todos los cambios de divisas en relación a una divisa
	 */
	public function getAllCurrencies($divori = "EUR")
	{
		return FsDiv::query()
			->select('cod_div', 'des_div', 'impd_div', 'symbolhtml_div', 'pos_div')
			->where('divori_div', $divori)
			->orderby('cod_div')
			->get()
			->keyBy('cod_div')
			->all();
	}

	public function getPrice($decimal = 2, $price = NULL)
	{
		if (!is_null($price)) {
			$this->price = $price;
		}
		return ToolsServiceProvider::moneyFormat($this->price * $this->exchange, FALSE, $decimal, $this->position);
	}

	public function getPriceSymbol($decimal = 2, $price = NULL)
	{
		//debemos permitir precio a 0, por eso n ousamso el empty si no el is_null
		if (!is_null($price)) {
			$this->price = $price;
		}
		//pongo un espacio en el momento de pasar la moneda
		return ToolsServiceProvider::moneyFormat($this->price * $this->exchange, " $this->symbol", $decimal, $this->position);
	}

	public function getPriceCod($decimal = 2, $price = NULL)
	{
		if (!is_null($price)) {
			$this->price = $price;
		}
		//pongo un espacio en el momento de pasar la moneda
		return ToolsServiceProvider::moneyFormat($this->price * $this->exchange, " $this->cod", $decimal, $this->position);
	}

	public function getExchange()
	{
		return $this->exchange;
	}

	public function getSymbol()
	{
		return $this->symbol;
	}

	public function getCod()
	{
		return $this->cod;
	}
}
