<?php namespace App\Controllers\Api;

use App\Models\Reservation;
use Input, Lunch, Redirect, Request, Sentry, View;

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

}
