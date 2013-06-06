<?php namespace App\Controllers;

use Input, Redirect, Sentry, View;

class AuthController extends BaseController {

	/**
	 * Display login page
	 * @return View
	 */
	public function getLogin()
	{
		return View::make('auth.login');
	}

	/**
	 * Login action, authenticate user
	 * @return Redirect
	 */
	public function postLogin()
	{
		$credentials = array(
			'email'    => Input::get('email'),
			'password' => Input::get('password')
		);

		try
		{
			$user = Sentry::authenticateAndRemember($credentials, false);

			if ($user)
			{
				return Redirect::route('reservations');
			}
		}
		catch(\Exception $e)
		{
			return Redirect::route('login')->withErrors(array('login' => $e->getMessage()));
		}
	}

	/**
	 * Logout action
	 * @return Redirect
	 */
	public function getLogout()
	{
		Sentry::logout();

		return Redirect::route('login');
	}

}
