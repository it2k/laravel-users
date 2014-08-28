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
use Mail;
use Token;

class AuthController extends Controller {

	/**
	 * Авторизация нового пользователя
	 * @return Response
	 */
	public function login()
	{		
		$credentials = Input::only(['username', 'password']);
		$remember = (intval(Input::get('remember'))) ? true : false;

		if (!Validator::make($credentials, ['username' => 'required|email'])->fails())
		{
			$validation = Validator::make($credentials, ['password' => 'required|min:3|max:40']);
			$credentials['email'] = strtolower($credentials['username']);
			unset($credentials['username']);
			$user = User::where('email', '=', $credentials['email'])->first();
		}
		else
		{	
			$validation = Validator::make($credentials, ['username' => 'required|min:3', 'password' => 'required|min:3|max:40']);
			$user = User::where('username', '=', $credentials['username'])->first();
		}

		if (!$user)
			return Redirect::back()->withInput()->withErrors(Lang::get('LaravelUsers::auth.InvalidLoginOrPassword'));

		if ($user->last_bad_login >= Carbon::now()->subMinutes(Config::get('LaravelUsers::auth.auth-check-last-minutes')) && $user->bad_login_count >= Config::get('LaravelUsers::auth.auth-check-last-count'))
			App::abort(500);

		if ($validation->fails())
			return Redirect::back()->withInput()->withErrors($validation->messages());		

		$credentials['enable'] = 1;

		if (Auth::attempt($credentials, $remember))
		{
			$user->bad_login_count    = 0;
			$user->last_bad_login     = '';
			$user->last_login         = Carbon::now();
			$user->last_login_from_ip = Request::getClientIp();
			$user->save();

			return Redirect::intended(Config::get('LaravelUsers::auth.successful-login-url'));
		}
		else
		{
			$user->bad_login_count        = $user->bad_login_count + 1;
			$user->last_bad_login         = Carbon::now();
			$user->last_bad_login_from_ip = Request::getClientIp();
			$user->save();

			return Redirect::back()->withInput()->withErrors(Lang::get('LaravelUsers::auth.InvalidLoginOrPassword'));
		}
	}
	/**
	 * Регистрация нового пользователя
	 * @return Response
	 */
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

		$user = User::create($credentials);

		Mail::send('LaravelUsers::emailConfirm', array('token' => $credentials['email_confirm_token'], 'email' => $credentials['email']), function ($message) use ($user)
		{
			$message->to($user->email, $user->username)->subject('registration');
		});

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

	/**
	 * Функция восстановления пароля.
	 * С проверкой на перебор email адресов.
	 * 
	 * @return Response
	 */
	public function lost_password()
	{
		$email = trim(strtolower(Input::get('email')));

		if (Validator::make(['email' => $email], ['email' => 'required|email'])->fails())
			return Redirect::to('auth#lost_password')->withInput()->withErrors(Lang::get('LaravelUsers::auth.WrongEmail'));

		$user = User::where('email', '=', $email)->first();

		if(!$user)
			return Redirect::to('auth#lost_password')->withInput()->withErrors(Lang::get('LaravelUsers::auth.WrongEmail'));

		$token = Token::create('change_password', Carbon::now()->addDays(7), $user);

		/*
		Mail::send('LaravelUsers::emailConfirm', array('token' => $credentials['email_confirm_token'], 'email' => $credentials['email']), function ($message) use ($user)
		{
			$message->to($user->email, $user->username)->subject('registration');
		});*/
		return Redirect::to('auth?email='.$email.'#lost_password');

	}

	/**
	 * Функция смены паролья.
	 * Для авторизованного пользователя спрашивает текущий пароль.
	 * Для не авторизованного email адрес и token на смену пароля
	 */
	public function change_password()
	{
		//
	}

}