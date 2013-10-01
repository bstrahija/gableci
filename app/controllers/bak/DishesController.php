<?php namespace App\Controllers;

use App\Models\Dish, App\Models\Reservation;
use Input, Lunch, Redirect, Sentry, View;

class DishesController extends BaseController {

	public function index()
	{
		$dishes = Dish::getForToday();

		// Get the flyer
		$flyer = Lunch::flyer();

		// Render view with data
		return View::make('dishes.index')->with('dishes', $dishes)->with('flyer', $flyer);
	}

	public function update($id)
	{
		$dish = Dish::find($id);
		$dish->title = strip_tags(Input::get('title'));
		$dish->price = Input::get('price');
		$dish->save();

		return Redirect::route('dishes.index');
	}

}

