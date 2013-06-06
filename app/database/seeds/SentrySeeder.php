<?php

use App\Models\User;

class SentrySeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();
		DB::table('groups')->delete();
		DB::table('users_groups')->delete();

		// ! Groups
		$adminGroup = Sentry::getGroupProvider()->create(array(
			'name'        => 'Admin',
			'permissions' => array('admin' => 1),
		));

		// ! Users
		Sentry::getUserProvider()->create(array(
			'email'       => 'admin@admin.com',
			'password'    => "admin",
			'first_name'  => 'Chuck',
			'last_name'   => 'Norris',
			'activated'   => 1,
		))->addGroup($adminGroup);

	}

}
