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

		$recipients = (new RecipientsNotificationList)->getAllTeams();

		foreach ($recipients as $recipient) {
			$notification = Notification::route('mail', $recipient);
			$this->sendNotification($notification, new AuctionInTime($auction, $when));
		}
	}
}
