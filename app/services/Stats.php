<?php namespace App\Services;

use DB, User;

class Stats {

	/**
	 * Return listof user and how much they spent
	 * @return array
	 */
	public function spent($month = null)
	{
		if ($month)
		{
			$from = substr($month, 0, 4) . '-' . substr($month, 4, 2) . '-01 00:00:00';
			$to   = substr($month, 0, 4) . '-' . substr($month, 4, 2) . '-31 23:59:59';
		}
		else
		{
			$from = null;
			$to   = null;
		}

		// Container
		$stats = array();

		// Get all users
		$users = User::all();

		// Fill the stats
		foreach ($users as $user)
		{
			$spent = DB::table('reservations')
			           ->join('dishes', 'dishes.id', '=', 'reservations.dish')
			           ->select('reservations.user_id', DB::raw('SUM(dishes.price) AS total'))
			           ->where('reservations.user_id', $user->id);

			// Date range
			if ($from and $to)
			{
				$spent->where('reservations.created_at', '>=', $from);
				$spent->where('reservations.created_at', '<=', $to);
			}

			// Get result
			$spent = $spent->first();

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
