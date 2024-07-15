<?php

namespace App\Actions\Observability;

use App\Actions\Traits\ActionNotificable;
use App\Models\V5\FgSub;
use App\Notifications\AuctionInTime;
use Illuminate\Support\Facades\Notification;

class HasAuctionAction
{
	use ActionNotificable;

	public function __invoke($when)
	{
		$datesBetween = match ($when) {
			'week' => [date('Y-m-d', strtotime('+1 week')) . ' 00:00:00', date('Y-m-d', strtotime('+1 week')) . ' 23:59:59'],
			'day' => [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'],
			'default' => [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'],
		};

		$auction = FgSub::JoinSessionSub()
			->where('tipo_sub', 'W')
			->whereBetween('"start"', $datesBetween)
			->first();

		if (!$auction) {
			return;
		}

		$recipientsList = new RecipientsNotificationList;
		$recipients = $recipientsList->getWebTeam();
		if($when === 'day') {
			$recipients = $recipientsList->getAllTeams();
		}

		$notification = Notification::route('mail', $recipients);
		$this->sendNotification($notification, new AuctionInTime($this->auctionValueObject($auction), $when));
	}

	private function auctionValueObject($auction)
	{
		return (object) [
			'cod_sub' => $auction->cod_sub,
			'agrsub_sub' => $auction->agrsub_sub,
			'orders_start' => $auction->orders_start,
			'orders_end' => $auction->orders_end,
			'tipo_sub' => $auction->tipo_sub,
			'reference' => $auction->reference,
			'name' => $auction->name,
			'description' => $auction->description,
			'id_auc_sessions' => $auction->id_auc_sessions,
			'session_start' => $auction->session_start,
			'session_end' => $auction->session_end,
			'emp_sub' => $auction->emp_sub
		];
	}
}
