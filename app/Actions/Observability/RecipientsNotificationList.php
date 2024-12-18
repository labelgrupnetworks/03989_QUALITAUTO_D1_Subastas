<?php

namespace App\Actions\Observability;

use Illuminate\Support\Facades\Config;

class RecipientsNotificationList
{

	private function recipients($deparment)
	{
		if (Config::get('app.env', 'local') === 'local') {
			return $this->whenLocalEnv();
		}

		$recipients = [
			'debug' => [
				'enadal@labelgrup.com'
			],
			'web' => [
				'enadal@labelgrup.com'
			],
			'erp' => [
				'dibanez@labelgrup.com',
				'mbanos@labelgrup.com'
			],
			'sistemas' => [
				'sistemas@labelgrup.com'
			],
			'subastas' => [
				'subastas@labelgrup.com'
			]
		];

		return $recipients[$deparment];
	}

	public function getDebugTeam()
	{
		return $this->recipients('debug');
	}

	public function getWebTeam()
	{
		return $this->recipients('web');
	}

	public function getErpTeam()
	{
		return $this->recipients('erp');
	}

	public function getSistemasTeam()
	{
		return $this->recipients('sistemas');
	}

	public function getSubastasTeam()
	{
		return $this->recipients('subastas');
	}

	public function getAllTeams()
	{
		return array_merge(
			$this->getWebTeam(),
			$this->getErpTeam(),
			$this->getSistemasTeam(),
			$this->getSubastasTeam()
		);
	}

	public function getWebAlerts()
	{
		return array_merge(
			$this->getWebTeam(),
			$this->getSistemasTeam(),
			$this->getSubastasTeam()
		);
	}

	private function whenLocalEnv()
	{
		return [Config::get('mail.mail_to')];
	}
}
