<?php namespace App\Controllers;

use App\Models\Reservation;
use Input, Lunch, Redirect, Request, Sentry, View;

class ReservationsController extends BaseController {

	/**
	 * Container for view data
	 * @var array
	 */
	protected $data = array();

	/**
	 * Initialize view data
	 */
	public function __construct()
	{
		$this->data = array(
			'flyer'         => Lunch::flyer(),
			'myReservation' => Reservation::getMine(),
			'reservations'  => Reservation::getForToday(),
			'overview'      => Reservation::getOverviewForToday(),
			'totalPrice'    => Reservation::getTotalPrice(),
		);
	}

	/**
	 * Index page for lunch reservation
	 * @return View
	 */
	public function getIndex()
	{
		return View::make('reservations.index', $this->data);
	}

	/**
	 * Only render the overview partial
	 * @return View
	 */
	public function getOverview()
	{
		return View::make('reservations.overview', $this->data);
	}

	/**
	 * Create a reservation
	 * @return Redirect
	 */
	public function postIndex()
	{
		$myReservation = Reservation::getMine();

		// Create new one if not found
		if ( ! $myReservation) $myReservation = new Reservation;

		// Save data
		$myReservation->dish    = Input::get('dish');
		$myReservation->notes   = strip_tags(Input::get('notes'));
		$myReservation->user_id = Auth::user()->id;
		$myReservation->save();

		return Redirect::route('reservations');
	}

	/**
	 * Rescrape todays menus
	 *
	 * @return Response
	 */
	public function scrape()
	{
		$data = Lunch::flyer();

		return $data;
	}

}
