<?php namespace App\Controllers;

use Lunch, Stats, View;

class StatsController extends BaseController {

	/**
	 * Overviee of stats
	 * @return View
	 */
	public function getIndex()
	{
		// Get the flyer
		$flyer = Lunch::flyer();

		// Get list of spendings
		$spent = Stats::spent();

		return View::make('stats.index')->with('flyer', $flyer)->with('spent', $spent);
	}

}
