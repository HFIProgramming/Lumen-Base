<?php

namespace App\Listeners;

use App\ActionLog;

class LogAction
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}


	public function log($event)
	{
		$log = new ActionLog();
		$user = $event->user;
		$log->user_id = $user->id;
		$log->action = $event->eventName;
		$log->description = $event->description;
		$log->model_name = $event->modelName;
		$log->ip_address = $event->ip;

		$log->save();
	}

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  Illuminate\Events\Dispatcher $events
	 * @return array
	 */
	public function subscribe($events)
	{
		$events->listen(
			'App\Events\UserRegister',
			'App\Listeners\LogAction@log'
		);

		$events->listen(
			'App\Events\UserResetPassword',
			'App\Listeners\LogAction@log'
		);

		$events->listen(
			'App\Events\UserLogin',
			'App\Listeners\LogAction@log'
		);
	}
}
