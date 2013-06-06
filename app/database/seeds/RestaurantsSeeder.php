<?php

use App\Models\Restaurant;

class RestaurantsSeeder extends Seeder {

	public function run()
	{
		DB::table('restaurants')->delete();

		Restaurant::create(array(
			'title'   => 'Garestin',
			'slug'    => 'garestin',
			'default' => 1,
		));
		Restaurant::create(array(
			'title'   => 'Santa Maria',
			'slug'    => 'santa-maria',
		));
		Restaurant::create(array(
			'title'   => 'El',
			'slug'    => 'el',
		));
	}

}
