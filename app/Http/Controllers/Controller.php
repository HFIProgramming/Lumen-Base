<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //
	public $loginLockPrefix = 'UserLoginLocked-';
	public $loginTokenPrefix = 'UserToken-';
	public $UserResetLockPrefix = 'UserResetTimes-';

}
