<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class Cookies
{
	private $cookieName;
	private $preferences;
	private $timeToLive;

	const THIRD_GOOGLE = 'Google';
	const THIRD_HOTJAR = 'Hotjar';
	const THIRD_FACEBOOK = 'Facebook';
	const THIRD_LINKEDIN = 'LinkedIn';
	const THIRD_SMARTLOOK = 'Smartlook';
	const THIRD_LIVEAGENT = 'LiveAgent';

	public function __construct()
	{
		$theme = Config::get('app.theme');
		$this->cookieName = "{$theme}_preferences";
		$this->preferences = $this->getCookies();
		$this->timeToLive = (60 * 24 * 365); //1 año

		//mantener un tiempo prudencial para eliminar cookies antiguas
		$this->sanitizeOldCookies();
	}

	public function getCookieName()
	{
		return $this->cookieName;
	}

	public function getCookies()
	{
		$preferencesJson = Cookie::get($this->cookieName, $this->defaultPreferences());
		return json_decode($preferencesJson, true);
	}

	public function getConfigurations()
	{
		return $this->preferences['configuration'];
	}

	public function getLotConfiguration()
	{
		return $this->preferences['configuration']['lot'];
	}

	public function setAllPermissions()
	{
		$permissions = [
			'analysis' => 1,
			'advertising' => 1,
		];

		$this->preferences['permissions'] = $permissions;
		$this->savePreferences();
	}

	public function removeAllPermissions()
	{
		$this->removeAnalysisCookies();
		$this->removeAdvertisingCookies();

		$permissions = [
			'analysis' => 0,
			'advertising' => 0,
		];

		$this->preferences['permissions'] = $permissions;
		$this->savePreferences();
	}

	public function setPermissions($permissions)
	{
		if(!$permissions['analysis']){
			$this->removeAnalysisCookies();
		}

		if(!$permissions['advertising']){
			$this->removeAdvertisingCookies();
		}

		$this->preferences['permissions'] = $permissions;
		$this->savePreferences();
	}

	public function addConfigurations($configurations)
	{
		$this->preferences['configuration'] = array_merge($this->preferences['configuration'], $configurations);
		$this->savePreferences();
	}

	public function isAnalysisAllowed()
	{
		return $this->preferences['permissions']['analysis'];
	}

	public function isAdvertisingAllowed()
	{
		return $this->preferences['permissions']['advertising'];
	}

	public function getAnalysisCookies()
	{
		$cookiesConfig = Config::get('app.cookies.analysis', []);
		return $this->getThirdCookies($cookiesConfig);
	}

	public function getAdvertisingCookies()
	{
		$cookiesConfig = Config::get('app.cookies.advertising', []);
		return $this->getThirdCookies($cookiesConfig);
	}

	private function getThirdCookies(array $keys)
	{
		$thirdCookies = $this->thirdCookies();
		$cookies = [];

		foreach ($keys as $key) {
			$cookies = array_merge($cookies, $thirdCookies[$key]);
		}

		return $cookies;
	}

	private function thirdCookies()
	{
		return [
			self::THIRD_GOOGLE => ['_ga_*', '_ga', '_gat_*', '_gcl_au', '_gid'],
			self::THIRD_HOTJAR => ['_hjAbsoluteSessionInProgress', '_hjIncludedInSessionSample_*', '_hjSessionUser_*', '_hjSession_*'],
			self::THIRD_FACEBOOK => ['_fbp'],
			self::THIRD_LINKEDIN => ['li_gc', 'AnalyticsSyncHistory', 'UserMatchHistory', 'lidc', 'bcookie', 'li_sugr'],
			self::THIRD_SMARTLOOK => ['SL_C_23361dd035530_SID', 'SL_C_23361dd035530_KEY', 'SL_C_23361dd035530_VID', 'SL_C_23361dd035530_DOMAIN'],
			self::THIRD_LIVEAGENT => ['LaSID', 'LaVisitorId_*', 'LaVisitorNew'],
		];
	}

	private function sanitizeOldCookies()
	{
		$oldCookies = [
			'lot',
			'cookie_config',
			'cookie_law',
			'AcceptCoockies'
		];

		foreach ($oldCookies as $cookie) {
			Cookie::queue(Cookie::forget($cookie));
		}
	}

	private function defaultPreferences()
	{
		$permissions = [
			'analysis' => 0,
			'advertising' => 0,
		];

		$configuration = [
			'lot' => 'img',
		];

		return json_encode([
			'permissions' => $permissions,
			'configuration' => $configuration
		]);
	}

	private function savePreferences()
	{
		$preferencesJson = json_encode($this->preferences);
		Cookie::queue($this->cookieName, $preferencesJson, $this->timeToLive);
	}

	private function removeAdvertisingCookies()
	{
		$advertisementCookies = [
			'_fbp',
			'SL_C'
		];

		$this->removeCookies($advertisementCookies);
	}

	private function removeAnalysisCookies()
	{
		$analyticsCookies = [
			'_g',
			'La',
			'_hj'
		];

		$this->removeCookies($analyticsCookies);
	}

	private function removeCookies($cookies)
	{
		$domain = $this->getCookiesDomain();
		$actualCookies = array_keys(Cookie::get());

		foreach ($actualCookies as $cookieName) {
			foreach ($cookies as $cookie) {
				if (strpos($cookieName, $cookie) !== false) {
					Cookie::queue(Cookie::forget($cookieName, null, $domain));
				}
			}
		}
	}

	private function getCookiesDomain()
	{
		$host = request()->getHost();
		$hostParts = explode('.', $host);

		// Verifica si el host comienza con "www." y quítalo si es necesario
		if (count($hostParts) > 0 && $hostParts[0] === 'www') {
			array_shift($hostParts);
		}

		// Verifica si tiene dominio y subdominio y quítalo si es necesario
		if (count($hostParts) > 2) {
			array_shift($hostParts);
		}

		$domain = "." . implode('.', $hostParts);
		return $domain;
	}
}
