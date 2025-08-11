<?php

namespace App\Services\Notifications;

use App\libs\EmailLib;
use App\Models\V5\FgRepresentados;
use App\Models\V5\FxCli;
use Illuminate\Support\Facades\Config;

class RequestBiddingPermissionAdminNotificationService
{

	/**
	 * @param FxCli $client
	 * @param string $codSub
	 * @param string $refSub
	 * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array $files
	 * @param FgRepresentados|null $represented
	 */
	public function __construct(
		private readonly FxCli $client,
		private readonly string $codSub,
		private readonly string $refSub,
		private readonly array $files,
		private readonly ?FgRepresentados $represented
	) {}

	/**
	 * Enviar una notificación al administrador solicitando permiso de licitación.
	 *
	 * @return bool
	 */
	public function send()
	{
		//$email = new EmailLib('AUTHORIZE_BID');
		$email = new EmailLib('AUTHORIZE_BID_TEMP');
		if (empty($email->email)) {
			return false;
		}

		$email->setClient_code($this->client->cod_cli);
		$email->setName($this->client->nom_cli);
		$email->setAtribute("RSOC_CLI", $this->client->rsoc_cli);
		$email->setEmail($this->client->email_cli);
		$email->setCif($this->client->cif_cli);

		$isRepresenting = !empty($this->represented);

		$email->setAtribute("REPRESENTING", $isRepresenting ? 'Si' : 'No');

		$representedToSting = '';
		if($isRepresenting){
			$representedToSting = $this->represented->toEmailString();
		}
		$email->setAtribute("REPRESENTED_TO", $representedToSting);

		$email->setLot($this->codSub, $this->refSub);
		$email->attachmentsFiles = $this->files;
		$email->setTo(Config::get('app.admin_email'));

		if($email->send_email()){
			return true;
		}
		return false;
	}

}
