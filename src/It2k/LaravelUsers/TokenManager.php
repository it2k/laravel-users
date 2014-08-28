<?php namespace It2k\LaravelUsers;

/**
 * Класс для работы с ключами от имени пользователя
 */

use App, Auth, DB, Config, Carbon\Carbon;

class TokenManager {
	
	/**
	 * Ид токена в базе данных
	 * 
	 * @var integer
	 */
	protected $_id = null;

	/**
	 * Объект пользователя
	 * 
	 * @var User
	 */
	protected $_user = null;

	/**
	 * Ключ
	 * 
	 * @var string
	 */
	protected $_key = null;

	/**
	 * Функуия создания ключа и сохранения его в базу данных
	 * 
	 * @param mixed $options Для каких действий пригоден token
	 * @param timestamp $expire До кокого времени действует token
	 * @param object $user Для кокого пользователя действуетс token. Если null то текущий
	 * @param bool $unique Признак уникальный ключ или нет. Если уникальный то удаляются все ключи с этими же действиями
	 * @return TokenManager $this;
	 */
	public function create($options, $expire, $user = null, $unique = true)
	{
		if (!$user && Auth::guest())
			App::abort(500, 'Cannot create token for guest!');

		if (!$user)
			$user = Auth::user();

		$_user = $user;

		if (is_string($options))
			$options = [$options];

		$options_json = json_encode($options);

		if ($unique)
			$sql = DB::table('tokens')->whereRaw('user_id = "'.$user->id.'" and options = \''.$options_json.'\'')->delete();

		while (!$this->_key)
		{
			$this->_key = $this->genToken($user);
			if (DB::table('tokens')->where('key', '=', $this->_key)->count() > 0)
				$this->_key = null;
		}

		//DB::insert('insert into tokens (user_id, expire, key, options, created_at) values (?, ?, ?, ?, ?)', [$user->id, $expire, $this->_key, json_decode($options_json), Carbon::now()]);
		DB::table('tokens')->insert(array('user_id' => $user->id, 'expire' => $expire, 'key' => $this->_key, 'options' => $options_json, 'created_at' => Carbon::now()));

		//die($last_query);

		return $this;
	}

	/**
	 * Загружает token по ключу из базы данных
	 * 
	 * @param string $key
	 * @return Token $this
	 */
	public function load($key)
	{
		//
	}

	/**
	 * Возвращает пользователя которому пренадлежит token
	 * 
	 * @return User
	 */
	public function user()
	{
		if (!$this->_user)
			App::abort(500, 'Token not loaded!');

		return $this->_user;

	}

	/**
	 * Функция генерации токена
	 * 
	 * @param User $user
	 * @return string
	 */
	public function genToken($user)
	{
		return hash_hmac('sha1', str_shuffle(sha1($user->email.spl_object_hash($user).microtime(true))), Config::get('app.key'));
	}
}