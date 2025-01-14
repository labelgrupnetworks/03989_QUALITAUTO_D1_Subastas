<?php

namespace App\Events\user;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNewsletterSubscribed
{
    use Dispatchable, SerializesModels;

	public string $email;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }
}
