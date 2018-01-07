<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
	return $router->app->version();
});

$router->post('/login', 'Auth\LoginController@tryLogin');
$router->post('/forgetPassword', 'Auth\ForgetPasswordController@forgetPassword');
$router->post('/passwordReset', 'Auth\ResetPasswordController@passwordReset');
$router->post('/register', 'Auth\RegisterController@tryRegister');

$router->group(['middleware' => 'checkIdentity', 'prefix' => 'user'], function () use ($router) {
	$router->get('/', 'UserController@me');
	$router->get('/log', 'UserController@actionLog');
});