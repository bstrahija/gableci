<?php namespace App\Controllers;

use App\Models\Dish, Input, Redirect, Sentry, View;

class DishesController extends BaseController {

	public function index()
	{
		$dishes = Dish::getForToday();

		// Render view with data
		return View::make('dishes.index')->with('dishes', $dishes);
	}

	public function update($id)
	{
		$dish = Dish::find($id);
		$dish->title = Input::get('title');
		$dish->price = Input::get('price');
		$dish->save();

		return Redirect::route('dishes.index');
	}

}

