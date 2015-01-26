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
	public static function getTitleByCode($code, $tags = true)
	{
		$dish = self::getByCode($code);

		if ($dish)
		{
			$title  = "<em class=\"code\">{$dish->code}</em>";
			$title .= "<strong class=\"title\">" . ($dish->title ? " " . strip_tags($dish->title) : ' ---') . "</strong>";
			$title .= "<span class=\"price\">" . ($dish->price ? " / " . strip_tags($dish->price) . " <i>kn</i>" : null) . "</span>";

			if ($tags) return $title;
			else       return strip_tags($title);
		}

		return $code;
	}

	/**
	 * Returns todays dishes if any, creates if missing
	 * @return Dish
	 */
	public static function getForToday()
	{
		$codes = array(1,2,3,4,5,6,7,8,9,10);

		// Check if they exist
		foreach ($codes as $code)
		{
			$dish = self::getByCode($code);

			// Create if missing
			if ( ! $dish)
			{
				// default is empty
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

		// Parse from pdf?
		$parse = true;
		foreach ($dishes as $value)
		{
			if ($value->created_at->format('U') != $value->updated_at->format('U'))
			{
				$parse = false;
			}
		}

		// Parse from pdf!
		if ($parse)
		{
			try
			{
				exec('python ' . app_path() . '/python/gastrocom/parser.py', $output);

				if (isset($output[0]))
				{
					$json = json_decode($output[0]);
					foreach ($json->menu as $menu)
					{
						self::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
						    ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
						    ->where('code', '=', $menu->id + 1)
						    ->update(array('title' => str_replace("\n", '; ', $menu->desc), 'price' => $menu->price));
					}

					$dishes = self::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
					              ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
					              ->orderBy('code')
					              ->get();
				}
			}
			catch(\Exception $e)
			{
				Log::error("Failed to execute python script.", array('DISH MODEL'));
			}
		}

		return $dishes;
	}

}
