<?php

namespace App\Services\Notifications;

use App\libs\EmailLib;
use App\Models\V5\FgSub;
use App\Models\V5\FxDvc0Seg;
use App\Services\Payments\OrderService;

class TrackingChangeNotificationService
{
	private ?string $file = null;

	/**
	 * @param string $codCli
	 * @param string $codSeg
	 * @param string|null $codSub
	 * @param string|null $number
	 * @param string|null $serie
	 */
	public function __construct(
		private readonly string $codCli,
		private readonly string $codSeg,
		private readonly string $codSub,
		private readonly string $serie,
		private readonly string $number,
	) {}

	public function addAttachment(string $filePath): void
	{
		if(empty($filePath)) {
			return;
		}

		$nameExplode = explode('REPORTS', $filePath);
		$fileName = 'reports' . $nameExplode[1];

		$this->file = public_path($fileName);
	}

	public function send()
	{
		$emailsTemplates = [
			'1' => 'TRACKING_CHANGE_SEG_STATE_1',
			'2' => 'TRACKING_CHANGE_SEG_STATE_2',
			'4' => 'TRACKING_CHANGE_SEG_STATE_4',
			'6' => 'TRACKING_CHANGE_SEG_STATE_6',
			'9' => 'TRACKING_CHANGE_SEG_STATE_9',
		];

		if (!array_key_exists($this->codSeg, $emailsTemplates)) {
			return;
		}

		$email = new EmailLib($emailsTemplates[$this->codSeg]);
		if (empty($email->email)) {
			return;
		}

		$email->setUserByCod($this->codCli, true);

		if(!empty($this->number) && !empty($this->serie)) {
			$this->addOrderDetailsToEmail($email, $this->serie, $this->number);
		}

		$auction = FgSub::select('dfec_sub')
			->joinLangSub()
			->where('cod_sub', $this->codSub)
			->first();

		$deliveryDate = FxDvc0Seg::getEstimatedDeliveryDate($auction->dfec_sub);

		$email->setAtribute('AUCTION_NAME', $auction->des_sub);
		$email->setDate($auction->dfec_sub, null);

		$email->setAtribute('DELIVERY_DATE', $deliveryDate);

		if(!empty($this->file)) {
            $email->attachments[] = $this->file;
		}

		$email->send_email();
	}

	private function addOrderDetailsToEmail(EmailLib $email, string $serie, string $number): void
	{
		$orderService = new OrderService();
		$order = $orderService->getOrderDetails($serie, $number);

		$orderTable = view('front::emails.component.order_detail', $order)->render();

		$address = $orderService->getOrderShippingAddress($this->serie, $this->number);
		$addressView = view('front::emails.component.order_address', [
			'name' => $address->nom_dvc0dir,
			'address' => $address->dir_dvc0dir,
			'postalCode' => $address->cp_dvc0dir,
			'city' => $address->pob_dvc0dir,
			'country' => $address->pais_dvc0dir
		])->render();

		$email->setAtribute('ORDER_DETAILS', $orderTable);
		$email->setAtribute('ORDER_ADDRESS', $addressView);
	}
}
