<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserResetPassword;
use App\PasswordResetToken;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{

	public function __construct()
	{
	}

	public function passwordReset(Request $request)
	{
		DB::beginTransaction();
		$token = $request->input('passwordResetToken');
		$password = $request->input('password');

		$val = $this->validator($request->all());
		if ($val->fails()) {
			return JsonData($val->getMessageBag(), 'fails, please retry', 422);
		}

		$modelToken = PasswordResetToken::query()->where('token', $token)->firstOrFail();
		$user = User::query()->findOrFail($modelToken->user_id);
		if ($user->CreatedInt() + 86400 <= time()) {
			$modelToken->delete();
			JsonStatus('Password reset Token Expired', 403);
		}

		$user->password = getHash()->make($password);
		$user->save();

		// Reset Limitation
		$modelToken->delete();
		Cache::forget($this->UserResetLockPrefix . $user->id);

		event(new UserResetPassword($user, $request->ip()));

		DB::commit();

		return JsonStatus();
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'passwordResetToken' => 'required|string|max:255',
			'password'           => 'required|string|min:6',
		]);
	}
}
