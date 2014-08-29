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
	 * Для каких действий пригоден ключ
	 * 
	 * @var array
	 */
	protected $_options = array();

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

		$this->_options = $options;

		if ($unique)
			DB::table('tokens')->whereRaw('user_id = "'.$user->id.'" and options = \''.json_encode($this->_options).'\'')->delete();

		while (!$this->_key)
		{
			$this->_key = $this->genToken($user);
			if (DB::table('tokens')->where('key', '=', $this->_key)->count() > 0)
				$this->_key = null;
		}

		$this->_id = DB::table('tokens')->insertGetId(array('user_id' => $user->id, 'expire' => $expire, 'key' => $this->_key, 'options' => json_encode($this->_options), 'created_at' => Carbon::now()));

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
		$token_row = DB::table('tokens')->where('key', '=', $key)->first();
		
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
	 * Возвращает ключ текущего tokenа
	 */
	public function key()
	{
		if (!$this->_key)
			App::abort(500, 'Token not loaded!');		
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