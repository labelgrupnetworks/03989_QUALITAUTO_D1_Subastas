<?php

namespace App\Services\Notifications;

use App\libs\EmailLib;
use App\Models\V5\FgSub;
use App\Models\V5\FxDvc0Seg;
use App\Services\Payments\OrderService;

class TrackingChangeNotificationService
{
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
		private readonly string $number,
		private readonly string $serie,
	) {}

	public function send()
	{
		$emailsTemplates = [
			'1' => 'TRACKING_CHANGE_SEG_STATE_1_TEST',
			'2' => 'TRACKING_CHANGE_SEG_STATE_2_TEST',
			'3' => 'TRACKING_CHANGE_SEG_STATE_3_TEST',
			'4' => 'TRACKING_CHANGE_SEG_STATE_4_TEST',
		];

		$email = new EmailLib($emailsTemplates[$this->codSeg]);
		if (empty($email->email)) {
			return;
		}

		if(empty($this->number) || empty($this->serie)) {
			return;
		}

		$orderService = new OrderService();
		$order = $orderService->getOrderDetails($this->number, $this->serie);
		$orderTable = view('front::emails.component.order_detail', $order)->render();

		$address = $orderService->getOrderShippingAddress($this->number, $this->serie);
		$addressView = view('front::emails.component.order_address', [
			'name' => $address->nom_dvc0dir,
			'address' => $address->dir_dvc0dir,
			'postalCode' => $address->cp_dvc0dir,
			'city' => $address->pob_dvc0dir,
			'country' => $address->pais_dvc0dir
		])->render();

		$auction = FgSub::select('dfec_sub')
			->joinLangSub()
			->where('cod_sub', $this->codSub)
			->first();

		$deliveryDate = FxDvc0Seg::getEstimatedDeliveryDate($auction->dfec_sub);

		$email->setUserByCod($this->codCli, true);
		$email->setAtribute('AUCTION_NAME', $auction->des_sub);
		$email->setAtribute('DELIVERY_DATE', $deliveryDate);
		$email->setAtribute('ORDER_DETAILS', $orderTable);
		$email->setAtribute('ORDER_ADDRESS', $addressView);

		$email->send_email();
	}
}
