<?php namespace App\Controllers;

use Auth, Input, Redirect, View;

class AuthController extends BaseController {

	/**
	 * Display login page
	 * @return View
	 */
	public function getLogin()
	{
		return View::make('auth.login', array('login' => true));
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

		if (Auth::attempt($credentials, true))
		{
			return Redirect::route('reservations');
		}
		else
		{
			return Redirect::route('login')->withErrors(array('login' => 'Login failed'));
		}
	}

	/**
	 * Logout action
	 * @return Redirect
	 */
	public function getLogout()
	{
		Auth::logout();

		return Redirect::route('login');
	}

}
