<?php namespace App\Controllers;

use App\Models\Reservation, Input, Lunch, Redirect, Request, Sentry, View;

class ReservationsController extends BaseController {

	/**
	 * Index page for lunch reservation
	 * @return View
	 */
	public function getIndex()
	{
		// Get the flyer
		$flyer = Lunch::flyer();

		// Get my reservation for today
		$myReservation = Reservation::getMine();

		// Get today's reservations for everybody
		$reservations = Reservation::getForToday();

		// Check for ajax request
		if (Request::ajax()) $view = 'reservations.overview';
		else                 $view = 'reservations.index';

		// Render view with data
		return View::make($view)->with(compact('flyer', 'myReservation', 'reservations'));
	}

	public function postIndex()
	{
		$myReservation = Reservation::getMine();

		// Create new one if not found
		if ( ! $myReservation) $myReservation = new Reservation;

		// Save data
		$myReservation->dish    = Input::get('dish');
		$myReservation->notes   = strip_tags(Input::get('notes'));
		$myReservation->user_id = Sentry::getUser()->id;
		$myReservation->save();

		return Redirect::route('reservations');
	}

}
