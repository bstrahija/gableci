<?php

use App\Models\Reservation;

class ReservationsSeeder extends Seeder {

	public function run()
	{
		DB::table('reservations')->delete();

		Reservation::create(array(
			'restaurant_id' => 1,
			'user_id'       => 1,
			'dish'          => '2',
			'notes'         => 'Neki prilog',
		));

	}

}
