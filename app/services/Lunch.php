<?php namespace App\Services;

use Cache, Goutte\Client, Log;

class Lunch {

	/**
	 * URL to garestin web page with flyers
	 * @var string
	 */
	protected $url = 'http://www.gastrocom-ugostiteljstvo.com/index.php?content=Gableci_Garestin';

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
		if ( ! $this->flyers) $this->scrape();
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
	 * Return all flyers
	 * @return array
	 */
	public function flyers()
	{
		return $this->flyers;
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
		if ( ! Cache::has('flyers'))
		{
			try {
				// Fetch page content
				$this->client  = new Client();
				$this->crawler = $this->client->request('GET', $this->url);
				$container     = $this->crawler->filter(".tekst");
				$html          = $container->html();
				$dom           = new \DomDocument();
				Log::debug("Fetching from url: " . $this->url, array('LUNCH SERVICE'));

				try
				{
					$dom->loadHTML($html);
					$urls = $dom->getElementsByTagName('a');
					$imgs = $dom->getElementsByTagName('img');

					// Find URL's first
					foreach ($urls as $url)
					{
						$href         = $url->getAttribute('href');
						$dateStartPos = strrpos($href, 'DNEVNI%20MENU%20') + strlen('DNEVNI%20MENU%20');
						$date         = substr($href, $dateStartPos, 6);

						// Add to list
						$this->flyers[$date] = array('href' => $href);
					}

					foreach ($imgs as $img)
					{
						$src          = $img->getAttribute('src');
						$dateStartPos = strrpos($src, 'DNEVNI%20MENU%20') + strlen('DNEVNI%20MENU%20');
						$date         = substr($src, $dateStartPos, 6);

						// Add to list
						$this->flyers[$date]['src'] = $src;
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
			catch(\Exception $e)
			{
				Log::error("Failed to scrape url: " . $this->url, array('LUNCH SERVICE'));
			}
		}
		else
		{
			$this->flyers = Cache::get('flyers');
		}

		return $this->flyers;
	}

}
