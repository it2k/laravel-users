<?php namespace It2k\LaravelUsers\Controllers;

use Controller;
use Redirect;
use Validator;
use Input;
use Auth;
use Lang;
use Log;
use Config;
use Request;

class AuthController extends Controller {

	public function login()
	{
		$credentials = Input::only(['username', 'password']);
		$remember = (intval(Input::get('remember'))) ? true : false;

		if (!Validator::make($credentials, ['username' => 'required|email'])->fails())
		{
			$validation = Validator::make($credentials, ['password' => 'required']);
			$credentials['email'] = $credentials['username'];
			unset($credentials['username']);
		}
		else
		{	
			$validation = Validator::make($credentials, ['username' => 'required', 'password' => 'required']);
		}

		if ($validation->fails())
			return Redirect::back()->withErrors($validation->messages());

		$credentials['enable'] = 1;

		if (Auth::attempt($credentials, $remember))
		{
			return Redirect::intended(route(Config::get('auth::successful-login-route')));
		}
		else
		{
			return Redirect::back()->withErrors(Lang::get('LaravelUsers::auth.InvalidLoginOrPassword'));
		}
	}

	public function registration()
	{
		$sql = 'select count(id) from users where registred_from = '.Request::getClientIp().'';
		print $sql;
/*
		$credentials = Input::only(['username', 'email', 'password', 'password_confirm']);

		$validation = Validator::make(array('username' => $username, 'email' => $email, 'password' => $password), User::$rules);
        if ($validation->passes())
        {

        }*/
	}

}