<?php namespace It2k\LaravelUsers;

use Illuminate\Support\ServiceProvider;

class LaravelUsersServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('It2k/LaravelUsers');

		include __DIR__.'/../../routes.php';

		$this->app->bind('it2k::command.auth.add-user', function($app) {
    		return new Commands\AddUserCommand();
		});

		$this->commands(array(
    		'it2k::command.auth.add-user'
		));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
