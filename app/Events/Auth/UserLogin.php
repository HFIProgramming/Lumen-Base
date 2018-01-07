<?php

namespace App\Events;

class UserLogin extends Event
{
	public $user;
	public $ip;
	public $eventName;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($user, $ip)
	{
		//
		$this->ip = $ip;
		$this->user = $user;
		parent::__construct();
	}
}
