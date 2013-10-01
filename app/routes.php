<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// ! Auth routes
Route::get('logout',  array('as' => 'logout',      'uses' => 'App\Controllers\AuthController@getLogout'));
Route::get('login',   array('as' => 'login',       'uses' => 'App\Controllers\AuthController@getLogin', 'before' => 'guest'));
Route::post('login',  array('as' => 'login.post',  'uses' => 'App\Controllers\AuthController@postLogin'));

// App route
Route::get('/', function() { return View::make('application'); });

// ! API routes
Route::group(array('prefix' => 'api'), function()
{
	Route::get('reservations/mine',     'App\Controllers\Api\ReservationsController@mine');
	Route::get('reservations/overview', 'App\Controllers\Api\ReservationsController@overview');
	Route::get('reservations/flyer',    'App\Controllers\Api\ReservationsController@flyer');
	Route::get('reservations/flyers',   'App\Controllers\Api\ReservationsController@flyers');
	Route::resource('reservations',     'App\Controllers\Api\ReservationsController');

	/*Route::get('/', array('as' => 'reservations', 'uses' => 'App\Controllers\ReservationsController@getIndex'));
	Route::post('/', array('as' => 'reservations.post', 'uses' => 'App\Controllers\ReservationsController@postIndex'));
	Route::controller('reservations', 'App\Controllers\ReservationsController');
	Route::controller('stats', 'App\Controllers\StatsController');
	Route::resource('dishes', 'App\Controllers\DishesController');*/
});
