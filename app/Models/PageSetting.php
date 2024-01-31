<?php

namespace App\Models;

use App\Models\V5\FgOrtsec0;

class PageSetting
{

	public $settings = [];

	private $tipo_sub = '';

	public function __construct()
	{
	}

	public function getSettings() : array
	{
		if (empty(session()->all()['user'])) {
			return [];
		}

		$userSession = session()->all()['user'];
		$isAdmin = $userSession['admin'][0];

		if (!$isAdmin) return [];

		$routeName = request()->route()->getName();
		$routeParams = request()->route()->parameters();
		// $routeQuery = request()->query();

		$settings = match (true) {
			($routeName == 'urlAuction') => $this->gridLotSettings($routeParams),
			str_contains($routeName, 'subastas.') => $this->gridAuctionsSettings(),
			($routeName == 'subasta.lote.ficha') => $this->lotFichaSettings($routeParams),
			($routeName == 'urlAuctionInfo') => $this->auctionInfoSettings($routeParams),
			($routeName == 'category') => $this->categorySettings($routeParams),
			($routeName == 'calendar') => $this->calendarSettings(),
			default => [],
		};

		$this->tempDumpRouteData();
		return array_merge($this->settings, $settings);
	}

	public function addSettings(array $settings)
	{
		$this->settings = array_merge($this->settings, $settings);
	}

	#region Subastas

	private function gridAuctionsSettings()
	{
		$this->tipo_sub = explode('.', $this->getRouteName())[1] ?? '';
		$this->tipo_sub = $this->calculateTipoSub($this->tipo_sub);
		return [
			$this->newRoute('edit_auctions', route('subastas.index') . '?tipo_sub=' . $this->tipo_sub),
		];
	}

	private function auctionInfoSettings(array $params)
	{
		return [
			$this->newRoute('edit_auctions', route('subastas.index') . '?tipo_sub=' . $this->tipo_sub),
			$this->newRoute('edit_auction', route('subastas.edit', ['subasta' => $params['cod']])),
		];
	}

	#endregion

	#region Lotes

	private function gridLotSettings(array $params)
	{
		if (empty($params['cod'])) {
			return [];
		}
		return [
			$this->newRoute('edit_lots', route('subastas.show', ['subasta' => $params['cod']])),
		];
	}

	private function lotFichaSettings(array $params)
	{
		return [
			$this->newRoute('edit_lots', route('subastas.show', ['subasta' => $params['cod']])),
			$this->newRoute('edit_lot', route('subastas.lotes.edit', ['cod_sub' => $params['cod'], 'lote' => $params['ref']])),
		];
	}

	#endregion

	#region Categorías

	private function categorySettings(array $params)
	{
		$id_category = (new FgOrtsec0())->getOrtsec0IDbyKey($params['keycategory'])->lin_ortsec0;
		return [
			$this->newRoute('edit_categories', route('category.index')),
			$this->newRoute('edit_category', route('category.edit') . '?idcategory=' . $id_category),
		];
	}

	#endregion

	#region Making routes

	private function newRoute(string $name, string $url)
	{
		return [
			'name' => $name,
			'url' => $url,
		];
	}

	private function getRouteName()
	{
		return request()->route()->getName();
	}

	#endregion

	public function tempDumpRouteData()
	{
		dump(request()->route()->uri, request()->route()->getName(), request()->route()->parameters(), request()->query());
	}

	#region Helpers

	private function calculateTipoSub(string $tipo_sub)
	{
		switch ($tipo_sub) {
			case 'presenciales':
				return 'W';
			case 'online':
				return 'O';
			case 'venta_directa':
				return 'V';
			default:
				return '';
		}
	}

	#endregion
}
