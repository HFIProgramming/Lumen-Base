<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
	use SerializesModels;
	public $eventName;

	public $description = NULL;
	public $modelName = NULL;
	public $ip = "0.0.0.0";

	public function __construct()
	{
		$this->eventName = self::getEventName();
	}

	public static function getEventName()
	{
		return get_called_class();
	}
}
