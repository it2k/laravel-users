<?php

Route::group(['namespace' => 'It2k\Laravel-Users\Controllers'], function()
{
	// Авторизация
	Route::get('login', function() { return View::make('laravelUsers::login'); });
	Route::post('login', 'Auth@makeLogin');
	Route::get('logout', function()	{ Auth::logout(); return Redirect::to('/'); });

});