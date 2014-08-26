<?php namespace It2k\LaravelUsers\Controllers;

use Controller;
use Redirect;

class AuthController extends Controller {

	public function login()
	{
		return Redirect::back()->withErrors('hren');
	}

}