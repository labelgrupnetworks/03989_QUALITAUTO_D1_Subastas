<?php

namespace App\Events\user;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNewsletterSubscribed
{
    use Dispatchable, SerializesModels;

	public string $email;
	public string $origin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email, $origin)
    {
        $this->email = $email;
		$this->origin = $origin;
    }
}
