<?php namespace App\Services;

use DB, User;

class Stats {

	/**
	 * Return listof user and how much they spent
	 * @return array
	 */
	public function spent()
	{
		$stats = array();

		// Get all users
		$users = User::all();

		// Fill the stats
		foreach ($users as $user)
		{
			$spent = DB::table('reservations')
			           ->join('dishes', 'dishes.id', '=', 'reservations.dish')
			           ->select('reservations.user_id', DB::raw('SUM(dishes.price) AS total'))
			           ->where('reservations.user_id', $user->id)
			           ->first();

			// Fill it
			$stats[] = (object) array(
				'spent' => $spent->total,
				'user'  => $user,
			);
		}

		// Order it
		usort($stats, function($a, $b) { return $a->spent < $b->spent; });

		return $stats;
	}

}
