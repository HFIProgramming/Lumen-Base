<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Cache;
use Illuminate\Http\Request;

class UserController extends Controller
{
	//
	public function __construct()
	{
	}

	/**
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me(Request $request)
	{
		return JsonData($request['userModel']);
	}

	/**
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout(Request $request)
	{
		Cache::forget($request['storedToken']);

		return JsonStatus();
	}

	public function actionLog(Request $request)
	{
		return JsonData();
	}
}
