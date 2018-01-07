<?php

namespace App\Events;

class UserResetPassword extends Event
{
	public $user;
	public $eventName;
	public $ip;

	/**
	 * UserResetPassword constructor.
	 *
	 * @param $user
	 */
	public function __construct($user, $ip)
	{
		//
		$this->user = $user;
		$this->ip = $ip;
		parent::__construct();
	}
}
