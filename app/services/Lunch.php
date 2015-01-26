<?php namespace App\Services;

use Cache, Carbon\Carbon, Goutte\Client, Log;
use App\Models\Dish;

class Lunch {

	/**
	 * URL to garestin web page with flyers
	 * @var string
	 */
	protected $url = 'http://www.gastrocom-ugostiteljstvo.com/gableci.html';

	/**
	 * Goutte client
	 * @var Goutte\Client
	 */
	protected $client;

	/**
	 * Goutte web crawler
	 * @var Goutte\Crawler
	 */
	protected $crawler;

	/**
	 * List of all available flyers
	 * @var array
	 */
	protected $flyers = array();

	/**
	 * Initialize scraper
	 */
	public function __construct()
	{
		if ( ! $this->flyers)
		{
			// Scrape web page
			$this->scrape();

			// Also try to update dishes
			$this->updateDishesFromPdf();
		}
	}

	/**
	 * Get for certain date
	 * @return string
	 */
	public function flyer($date = null)
	{
		// Default to today
		if ( ! $date) $date = date('d.m.');

		// Get the flyer
		if (isset($this->flyers[$date]))
		{
			return $this->flyers[$date];
		}
		else
		{
			// If no flyer is found , bust the cache and try again
			Cache::forget('flyers');
			$this->scrape();

			if (isset($this->flyers[$date])) return $this->flyers[$date];
		}
	}

	/**
	 * Get flyer href
	 * @param  string $date
	 * @return string
	 */
	public function flyerHref($date = null)
	{
		$flyer = $this->flyer($date);

		if ($flyer and isset($flyer['href']))
		{
			return $flyer['href'];
		}
	}

	/**
	 * Get flyer thumb src
	 * @param  string $date
	 * @return string
	 */
	public function flyerThumb($date = null)
	{
		$flyer = $this->flyer($date);

		if ($flyer and isset($flyer['src'])) return $flyer['src'];
	}

	/**
	 * Scrapes the page and gets all URLs for flyers
	 * @return array
	 */
	public function scrape()
	{
		Cache::forget('flyers');
		if ( ! Cache::has('flyers'))
		{
			try
			{
				$this->client  = new Client();
				$this->crawler = $this->client->request('GET', $this->url);
				$html          = $this->crawler->html();

				preg_match_all('/\<a .*download=[^\>]+\>/', $html, $anchors);
				foreach ($anchors[0] as $anchor)
				{
					preg_match('/ href="([^"]+)"/', $anchor, $href);
					if (isset($href[1]))
					{
						preg_match('/DNEVNI_MENU_([0-9]+).([0-9]+)._Garestin.pdf/', $href[1], $date);
						if (isset($date[2]))
						{
							$date = $date[1] . '.' . $date[2] . '.';
							$href = dirname($this->url) . $href[1];
							// to do: relative/absolute path check

							$this->flyers[$date] = array('href' => $href);
						}
					}
				}
			}
			catch(\Exception $e)
			{
				Log::error("Failed to scrape url: " . $this->url, array('LUNCH SERVICE'));
			}

			// Cache the results
			if ($this->flyers)
			{
				Cache::put('flyers', $this->flyers, 60*3);
			}
		}
		else
		{
			$this->flyers = Cache::get('flyers');
		}

		return $this->flyers;
	}

	/**
	 * Parse PDF and update dishes
	 *
	 * @param  Collection $dishes
	 * @return void
	 */
	public function updateDishesFromPdf($dishes = null)
	{
		if ( ! $dishes) $dishes = Dish::getForToday();

		// Get todays flyer
		$flyer = $this->flyer();

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
		if ($parse and isset($flyer['href']))
		{
			try
			{
				$command = 'python ' . app_path() . '/python/gastrocom/parser.py --url=' . $flyer['href'];
				exec($command, $output);

				if (isset($output[0]))
				{
					$json = json_decode($output[0]);

					foreach ($json->menu as $key => $menu)
					{
						$code = $key + 1;
						$dish = Dish::getByCode($code);

						// Update dish info
						if ($dish and $menu and is_object($menu))
						{
							$dish->title = str_replace("\n", ", ", object_get($menu, 'desc'));
							$dish->price = (int) object_get($menu, 'price');
							$dish->save();
						}
					}
				}
			}
			catch(\Exception $e)
			{
				Log::error("Failed to execute python script.", array('DISH MODEL'));
				echo '<pre>'; print_r(var_dump($e)); echo '</pre>';
			}
		}
	}

}
