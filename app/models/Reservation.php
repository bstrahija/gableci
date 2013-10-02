<?php namespace App\Models;

use Dish;

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
		                            ->with('user')
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
		                           ->with('user')
		                           ->get();

		if ($reservations)
		{
			foreach ($reservations as &$reservation)
			{
				$reservation->dish_data  = Dish::getByCode($reservation->dish)->toArray();
				$reservation->dish_title = Dish::getTitleByCode($reservation->dish);
			}
		}

		return $reservations;
	}

	/**
	 * Get complete overview of grouped orders
	 * @return array
	 */
	public static function getOverviewForToday()
	{
		// Get all reservations for today
		$reservations = self::getForToday();

		// Overview container
		$overview = array();

		if ($reservations)
		{
			foreach ($reservations as $reservation)
			{
				// Get reservation dish
				$dish = Dish::getByCode($reservation->dish);

				// Check if dish is in list
				$existing = array_has($overview, 'dish', $reservation->dish);

				// Take action
				if ($existing !== false and isset($overview[$existing]))
				{
					$overview[$existing]['count']++;
					$overview[$existing]['price']      += $dish->price;
					$overview[$existing]['usernames'][] = $reservation->user->full_name;
					$overview[$existing]['users'][]     = $reservation->user->toArray();

					// Trim it
					$reservation->notes = strip_tags(trim($reservation->notes));

					// Count the notes (dont't duplicate)
					if ($reservation->notes)
					{
						if ($overview[$existing]['notes'])
						{
							foreach ($overview[$existing]['notes'] as &$note)
							{
								if (isset($note['text']) and strtolower($note['text']) == strtolower($reservation->notes))
								{
									$note['count']++;
									$note['names'][] = $reservation->user->full_name;
								}
								else
								{
									$overview[$existing]['notes'][] = array('text' => $reservation->notes, 'count' => 1, 'names' => array($reservation->user->full_name));
								}
							}
						}
						else
						{
							$overview[$existing]['notes'] = array(array('text' => $reservation->notes, 'count' => 1, 'names' => array($reservation->user->full_name)));
						}
					}
				}
				else
				{
					$overview[] = array(
						'dish'      => $reservation->dish,
						'title'     => $dish->title,
						'count'     => 1,
						'price'     => $dish->price,
						'usernames' => array($reservation->user->full_name),
						'notes'     => $reservation->notes ? array(array('text' => $reservation->notes, 'count' => 1, 'names' => array($reservation->user->full_name))) : array(),
						'users'     => array($reservation->user->toArray()),
					);
				}
			}
		}

		return $overview;
	}

	/**
	 * Calculate total prce of reservations
	 * @return float
	 */
	public static function getTotalPrice()
	{
		$total        = 0;
		$reservations = self::getForToday();

		if ($reservations)
		{
			foreach ($reservations as $reservation)
			{
				$dish   = Dish::getByCode($reservation->dish);

				if ($dish) $total += $dish->price;
			}
		}

		return $total;
	}

}
