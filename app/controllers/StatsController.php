<?php namespace App\Controllers;

use Input, Lunch, Stats, View;

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
		$spent = Stats::spent(Input::get('range'));

		return View::make('stats.index')->with('flyer', $flyer)->with('spent', $spent);
	}

}
