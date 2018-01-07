<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLogin;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
	//
	public function __construct()
	{
	}

	/**
	 * Get user Columns
	 *
	 * @return string
	 */
	protected function username()
	{
		return 'email';
	}

	/**
	 * Get user Models
	 *
	 * @param $username
	 * @return \Illuminate\Database\Eloquent\Model|static
	 */
	protected function getUserModelByUsername($username)
	{
		return User::query()->where($this->username(), $username)->firstOrFail();
	}

	/**
	 * Verify Password
	 *
	 * @param $username
	 * @param $password
	 * @param $user
	 * @return bool
	 */
	protected function verify($password, $user)
	{
		return getHash()->check($password, $user->password);
	}


	/**
	 * Process Login
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function tryLogin(Request $request)
	{
		$username = $request->input('username');
		$password = $request->input('password');

		if (empty($username) || empty($password)) {
			return JsonStatus('Missing Credential', 401);
		}

		$user = $this->getUserModelByUsername($username);

		if ($this->LoginLocked($user)) {
			return JsonStatus('Too many error retries, try again later', 403);
		}

		if (!$this->verify($password, $user)) {
			// Generate token and Push into cache
			$str = str_random(64);
			Cache::put($this->loginTokenPrefix . $str, $user->id, env('AUTH_TIMEOUT'));
			// Clear Login Locked
			Cache::forget($this->loginLockPrefix . $user->id);

			$this->loginSuccess($request, $user);

			return JsonData(['token' => $str, 'expire' => env('AUTH_TIMEOUT')]);
		} else {
			$this->LoginFails($user);

			return JsonStatus('Wrong Username and password Combination', 401);
		}
	}

	/**
	 * Log Failures
	 *
	 * @param $user
	 */
	protected function LoginFails($user)
	{
		$key = $this->loginLockPrefix . $user->id;
		if (!Cache::has($key)) {
			Cache::put($key, 1, env('AUTH_LOCKED'));
		} else {
			Cache::increment($key);
		}
	}

	/**
	 * Check Login fails
	 *
	 * @param $user
	 * @return bool
	 */
	public function LoginLocked($user)
	{
		if (!Cache::has($this->loginLockPrefix . $user->id)) {
			return false;
		}

		if (Cache::get($this->loginLockPrefix . $user->id) <= env('AUTH_RETRY')) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * @param Request $request
	 * @param $user
	 */
	protected function LoginSuccess(Request $request, $user)
	{
		event(new UserLogin($user, $request->ip()));
	}


}
