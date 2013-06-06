<?php namespace App\Models;

class Reservation extends \Eloquent {

	public $table = 'reservations';

	public function user()
	{
		return $this->belongsTo('\User');
	}

	/**
	 * Get my reservation for today
	 * @return Reservation
	 */
	public static function getMine()
	{
		$myReservation = Reservation::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
		                            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
		                            ->where('user_id', \Sentry::getUser()->id)
		                            ->first();

		return $myReservation;
	}

	/**
	 * Get all reservations for today
	 * @return Reservation
	 */
	public static function getForToday()
	{
		$reservations = Reservation::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
		                           ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
		                           ->orderBy('dish')
		                           ->get();

		return $reservations;
	}

}
