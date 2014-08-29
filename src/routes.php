<?php
/**
 * Файл маршрутизации запросов для модуля LaravelUsers
 * @author Егор Зюскин
 * @version 1.0
 */

Route::group(['namespace' => 'It2k\LaravelUsers\Controllers'], function()
{
    Route::get('login', function(){return View::make('LaravelUsers::loginForm'); });
    Route::get('registration', function(){return View::make('LaravelUsers::registrationForm'); });
    Route::get('lost_password', function(){return View::make('LaravelUsers::lostPasswordForm'); });
    Route::get('change_password', function(){return View::make('LaravelUsers::changePasswordForm'); });
    Route::get('change_email', function(){return View::make('LaravelUsers::changeEmailForm'); });
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