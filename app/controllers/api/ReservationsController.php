<?php namespace App\Controllers\Api;

use App\Models\Reservation;
use Input, Lunch, Redirect, Request, Response, Sentry, View;

class ReservationsController extends BaseController {

	/**
	 * Return todays reservations
	 * @return Response
	 */
	public function index()
	{
		return Reservation::getForToday();
	}

	/**
	 * Returns various data for home screen to reduce requests
	 * @return Response
	 */
	public function home()
	{
		return Response::json(array(
			'flyer'          => Lunch::flyer(),
			// 'my_reservation' => Reservation::getMine(),
			'overview'       => (array) Reservation::getOverviewForToday(),
			'reservations'   => Reservation::getForToday()->toArray(),
			'total_price'    => Reservation::getTotalPrice(),
		));
	}

	/**
	 * Return single reservation, default for current user
	 * @param  integer $id
	 * @return Response
	 */
	public function show($id = null)
	{
		Reservation::find($id);
	}

	/**
	 * Return todays flyer
	 * @return Response
	 */
	public function flyer()
	{
		return Lunch::flyer();
	}

	/**
	 * Return all flyers
	 * @return Response
	 */
	public function flyers()
	{
		return Lunch::flyers();
	}

	/**
	 * Return reservation for current user
	 * @return Response
	 */
	public function mine()
	{
		return Reservation::getMine();
	}

	/**
	 * Reservation overview
	 * @return Response
	 */
	public function overview()
	{
		return Reservation::getOverviewForToday();
	}

	/**
	 * Return total price for todays reservations
	 * @return Response
	 */
	public function totalPrice()
	{
		return Response::json(array('price' => Reservation::getTotalPrice()));
	}

	/**
	 * Create new reservation
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Update existing reservation
	 * @param  integer $id
	 * @return Response
	 */
	public function update($id)
	{

	}

	/**
	 * Delete existing reservation
	 * @param  integer $id
	 * @return Response
	 */
	public function delete($id)
	{

	}

}
