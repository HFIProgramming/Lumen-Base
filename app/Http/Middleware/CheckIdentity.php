<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Cache;

class CheckIdentity
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// Pre-Middleware Action
		$token = $request->header('Auth-Token');
		$storedToken = 'UserToken-' . $token;
		$userId = Cache::get($storedToken);

		// Missing Login Cred
		if (empty($token) && empty($userId)) {
			return JsonStatus('Login Required', 401);
		}

		// User id must be numeric
		if (!is_numeric($userId)) {
			Cache::forget($storedToken);
			abort(500, "Error While Verifying Token, please re-login");
		}

		if (empty($user = User::query()->find($userId))) {
			Cache::forget($storedToken);
			abort(500, "Error While Retrieving User, please re-login");
		}

		$request->merge(['userModel' => $user, 'storedToken' => $storedToken]);

		$response = $next($request);

		// Post-Middleware Action

		return $response;
	}
}
