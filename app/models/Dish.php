<?php namespace App\Models;

class Dish extends \Eloquent {

	public $table = 'dishes';

	public function reservation()
	{
		return $this->belongsTo('reservation');
	}

	public function reservations()
	{
		return $this->hasMany('reservation');
	}

	/**
	 * Get todays dish by code
	 * @return Dish
	 */
	public static function getByCode($code)
	{
		$dish = self::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
		            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
		            ->where('code', $code)
		            ->first();

		return $dish;
	}

	/**
	 * Returns the title of the dish
	 * @param  string $code
	 * @return Dish
	 */
	public static function getTitleByCode($code)
	{
		$dish = self::getByCode($code);

		if ($dish)
		{
			$title  = "[{$dish->code}]";
			$title .= $dish->title ? " {$dish->title} " : ' ---';
			$title .= $dish->price ? " / {$dish->price} kn" : null;

			return strip_tags($title);
		}

		return $code;
	}

	/**
	 * Returns todays dishes if any, creates if missing
	 * @return Dish
	 */
	public static function getForToday()
	{
		$codes = array(1,2,3,4,5,6,7);

		// Check if they exist
		foreach ($codes as $code)
		{
			$dish = self::getByCode($code);

			// Create if missing
			if ( ! $dish)
			{
				$dish = new Dish;
				$dish->code = $code;
				$dish->save();
			}
		}

		// Now get all dishes for today
		$dishes = self::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
		              ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
		              ->orderBy('code')
		              ->get();

		return $dishes;
	}

}
