<?php
/**
 * Файл маршрутизации запросов для модуля LaravelUsers
 * @author Егор Зюскин
 * @version 1.0
 */

Route::group(['namespace' => 'It2k\LaravelUsers\Controllers'], function()
{
	Route::get('auth', function() { return View::make('LaravelUsers::authForm'); });
	Route::get('logout', function()	{ Auth::logout(); return Redirect::to('/'); });
	Route::get('confirm_email', array('uses' => 'AuthController@confirm_email'));
	
	/**
	 * Необходима проверка "Cross-site request forgery" перед обработкой запросов
	 */
	Route::group(array('before' => 'csrf'), function()
	{
		Route::post('login', array('uses' => 'AuthController@login'));
		Route::post('registration',  array('uses' => 'AuthController@registration'));
		Route::post('lost_password',  array('uses' => 'AuthController@lost_password'));
		Route::post('change_password',  array('uses' => 'AuthController@change_password'));
	});
});