<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LunchFacade extends Facade {

	protected static function getFacadeAccessor()
	{
		return new \App\Services\Lunch;
	}

}
