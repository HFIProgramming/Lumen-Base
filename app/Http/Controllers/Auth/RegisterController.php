<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
	//
	public function __construct()
	{
	}

	/**
	 * Verify before Register
	 *
	 * @return bool
	 */
	protected function verify()
	{
		return true;
	}

	public function tryRegister(Request $request)
	{
		DB::beginTransaction();
		$val = $this->validator($request->all());
		if ($val->fails()) {
			return JsonData($val->getMessageBag(), 'fails, please retry', 422);
		}

		if (!$this->verify()) {

		}
		event(new UserRegister($user = $this->create($request->all()), $request->ip()));

		DB::commit();

		return JsonStatus('success, you can now login to your account');
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		return User::query()->create([
			'username' => $data['username'],
			'email'    => $data['email'],
			'password' => getHash()->make($data['password']),
		]);
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
			'username' => 'required|string|max:255|unique:users',
			'email'    => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6',
		]);
	}
}
