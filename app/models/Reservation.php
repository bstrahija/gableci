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
				if ($reservation->dish)
				{
					// Get reservation dish
					$dish = Dish::getByCode($reservation->dish);

					// Check if dish is in list
					$existing = array_has($overview, 'dish', $reservation->dish);

					if ($existing !== false and isset($overview[$existing]))
					{
						$existing = (int) $existing;
						$overview[$existing]['count']++;
						$overview[$existing]['price']      += ($dish) ? $dish->price : 0;
						$overview[$existing]['usernames'][] = $reservation->user->full_name;
						$overview[$existing]['users'][]     = $reservation->user->toArray();

						// Trim it
						$reservation->notes = strip_tags(trim($reservation->notes));

						// Count the notes (dont't duplicate)
						if ($reservation->notes)
						{
							if ($overview[$existing]['notes'])
							{
								$existingNote = false;

								foreach ($overview[$existing]['notes'] as &$note)
								{
									if (isset($note['text']) and strtolower($note['text']) == strtolower($reservation->notes))
									{
										$note['count']++;
										$note['names'][] = $reservation->user->full_name;
										$existingNote = true;
									}
								}

								// Add new one if not existing
								if ( ! $existingNote)
								{
									$overview[$existing]['notes'][] = array('text' => $reservation->notes, 'count' => 1, 'names' => array($reservation->user->full_name));
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
							'title'     => ($dish) ? $dish->title : null,
							'count'     => 1,
							'price'     => ($dish) ? $dish->price : null,
							'usernames' => array($reservation->user->full_name),
							'notes'     => $reservation->notes ? array(array('text' => $reservation->notes, 'count' => 1, 'names' => array($reservation->user->full_name))) : array(),
							'users'     => array($reservation->user->toArray()),
						);
					}
				}
			}
		}

		return $overview;
	}

	/**
	 * Calculate total price of reservations
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
