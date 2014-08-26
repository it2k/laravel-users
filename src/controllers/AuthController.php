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
use Carbon\Carbon;
use DB;
use User;
use App;
use Hash;

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
			return Redirect::intended(route(Config::get('auth::successful-login-route')));
		else
			return Redirect::back()->withErrors(Lang::get('LaravelUsers::auth.InvalidLoginOrPassword'));
	}

	public function registration()
	{
		if(!Config::get('LaravelUsers::auth.registration-allow'))
			App::abort(404);

		$sql = 'select count(id) as count from users where registering_from_ip = "'.Request::getClientIp().'" AND registering >= "'.Carbon::now()->subMinutes(Config::get('LaravelUsers::auth.registration-check-last-minutes')).'"';
		$resutl = DB::select($sql);

		if ($resutl[0]->count > intval(Config::get('LaravelUsers::auth.registration-check-last-count')))
			App::abort(500);

		$credentials = Input::only(['username', 'email', 'password', 'password_confirm']);
		$credentials['email'] = trim(strtolower($credentials['email']));

		if ($credentials['password'] != $credentials['password_confirm'])
			return Redirect::to('auth#registration')->withInput()->withErrors(Lang::get('LaravelUsers::auth.PasswordsNotMatch'));

		unset($credentials['password_confirm']);

		$validation = Validator::make($credentials, User::$rules);

        if ($validation->fails())
			return Redirect::to('auth#registration')->withInput()->withErrors($validation->messages());

		$credentials['password'] = Hash::make($credentials['password']);
		$credentials['registering'] = Carbon::now();
		$credentials['registering_from_ip'] = Request::getClientIp();

		if(!Config::get('LaravelUsers::auth.registration-check-email'))
		{
			$credentials['enable'] = 1;
			$user = User::create($credentials);
			Auth::login($user);
			return Redirect::to(Config::get('LaravelUsers::auth.successful-login-route'));
		}

		$credentials['email_confirm_token'] = hash_hmac('sha1', str_shuffle(sha1($credentials['email'].microtime(true))), Config::get('app.key'));

		User::create($credentials);

		return Redirect::to('auth?email='.$credentials['email'].'#confirm_email');
	}

	public function confirm_email()
	{
		$email = Input::get('email');
		$token = Input::get('token');

		if (Auth::guest())
		{
			$user = User::where('email', '=', strtolower($email))->firstOrFail();
			if ($user->email_confirm_token == $token)
			{
				$user->email_confirm_token = '';
				$user->enable = 1;
				$user->save();
				return Redirect::to('auth')->with('message', Lang::get('LaravelUsers::auth.EmailConfirmed'));
			}
			else
			{
				return Redirect::to('auth?email='.$email.'#confirm_email')->withErrors(Lang::get('LaravelUsers::auth.WrongToken'));
			}
		}
	}

}