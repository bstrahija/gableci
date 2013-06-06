<?php

use App\Models\Dish;

class DishesSeeder extends Seeder {

	public function run()
	{
		DB::table('dishes')->delete();

		Dish::create(array(
			'code'  => 1,
			'title' => 'Mahune varivo, kosani odrezak',
			'price' => 24,
		));
		Dish::create(array(
			'code'  => 2,
			'title' => 'Pohani pileći batak, kašica sa šampinjonima, zelena salata',
			'price' => 28,
		));
		Dish::create(array(
			'code'  => 3,
			'title' => 'Nabodeni sv. but, šiškrli, složena salata',
			'price' => 32,
		));
		Dish::create(array(
			'code'  => 4,
			'title' => 'Punjena paprika, pire krumpir',
			'price' => 29,
		));
		Dish::create(array(
			'code'  => 5,
			'title' => 'Rolano teleće pečenje, okruglice od riže, zelje salata',
			'price' => 43,
		));
		Dish::create(array(
			'code'  => 6,
			'title' => 'File oslić na orly, dalmatinska garnitura',
			'price' => 27,
		));
		Dish::create(array(
			'code'  => 7,
			'title' => 'Pečena janjetina, pole krumpir',
			'price' => 55,
		));

	}

}
