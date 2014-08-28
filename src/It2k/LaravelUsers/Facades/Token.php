<?php namespace It2k\LaravelUsers\Facades;

/**
* Фасад для класса Token пакета LaravelUsers
*/

use Illuminate\Support\Facades\Facade as Facade;

/**
 * @see \It2k\LatavelUsers\TokenManager
 */
class Token extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'token'; }

}