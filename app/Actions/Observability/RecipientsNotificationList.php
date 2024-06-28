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
				'rsanchez@labelgrup.com',
				'llandeira@labelgrup.com',
				'enadal@labelgrup.com',
				'subastas@labelgrup.com'
			],
			'erp' => [
				'dibanez@labelgrup.com',
				'mbanos@labelgrup.com',
				'subastas@labelgrup.com'
			],
			'sistemas' => [
				'sistemas@labelgrup.com',
				'subastas@labelgrup.com'
			],
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

	public function getAllTeams()
	{
		return array_unique(array_merge(
			$this->getWebTeam(),
			$this->getErpTeam(),
			$this->getSistemasTeam()
		));
	}

	public function getWebAlerts()
	{
		return array_unique(array_merge(
			$this->getWebTeam(),
			$this->getSistemasTeam()
		));
	}

	private function whenLocalEnv()
	{
		return [Config::get('mail.mail_to')];
	}
}
