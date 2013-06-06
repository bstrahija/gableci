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
Route::get('login',   array('as' => 'login',       'uses' => 'App\Controllers\AuthController@getLogin'));
Route::post('login',  array('as' => 'login.post',  'uses' => 'App\Controllers\AuthController@postLogin'));

// ! App routes
Route::group(array('before' => 'auth'), function()
{
	Route::get('/', array('as' => 'reservations', 'uses' => 'App\Controllers\ReservationsController@getIndex'));
	Route::post('/', array('as' => 'reservations.post', 'uses' => 'App\Controllers\ReservationsController@postIndex'));
	Route::controller('reservations', 'App\Controllers\ReservationsController');
	Route::resource('dishes', 'App\Controllers\DishesController');
});
