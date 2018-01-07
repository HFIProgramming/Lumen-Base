<?php

namespace App\Http\Controllers\Auth;

use App\PasswordResetToken;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
	public $UserResetLockPrefix = 'UserResetTimes-';

	//
	public function __construct()
	{
	}

	/**
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function forgetPassword(Request $request)
	{
		$email = $request->input('email');
		$this->validate($request, ['email' => 'required|email']);

		$user = User::query()->where('email', $email)->firstOrFail();
		if ($this->checkLockedTime($user)) {
			return JsonStatus('Exceeded the amount of times resetting password per day', 403);
		}

		$this->setLockedTime($user);

		$token = str_random(64);
		$resetModel = PasswordResetToken::query()->updateOrCreate([
			'user_id' => $user->id,
		], [
			"token"   => $token,
			'user_id' => $user->id,
		]);

		/*		Mail::raw('Your Reset Token: ' . $token, function ($msg) use ($user) {
					$msg->to([$user->email]);
				}); */

		return JsonStatus();
	}

	/**
	 *
	 *
	 * @param $user
	 * @return bool
	 */
	protected function checkLockedTime($user)
	{
		if (empty(Cache::get($this->UserResetLockPrefix . $user->id))) {
			return false;
		}

		$times = Cache::get($this->UserResetLockPrefix . $user->id);

		if ($times <= env('AUTH_LOCKED')) {
			return false;
		}

		return true;
	}

	/**
	 *
	 *
	 * @param $user
	 */
	protected function setLockedTime($user)
	{
		if (empty(Cache::get($this->UserResetLockPrefix . $user->id))) {
			Cache::put($this->UserResetLockPrefix . $user->id, 1, 1440);
		} else {
			Cache::increment($this->UserResetLockPrefix . $user->id);
		}
	}

}
