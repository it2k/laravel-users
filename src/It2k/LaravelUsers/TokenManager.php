<?php namespace It2k\LaravelUsers;

use App, Auth, DB, Config, Carbon\Carbon;

/**
 * Класс для работы с ключами от имени пользователя
 */
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
	 * @var \User
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
	 * @param $options mixed Для каких действий пригоден token
	 * @param $expire \DateTime До кокого времени действует token
	 * @param $user \User Для кокого пользователя действуетс token. Если null то текущий
	 * @param $unique bool Признак уникальный ключ или нет. Если уникальный то удаляются все ключи с этими же действиями
	 * @return \Token $this;
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
	 * @return \Token $this
	 */
	public function load($key)
	{
		$token_row = DB::table('tokens')->where('key', '=', $key)->first();

        if (!$token_row)
            return $this;

        $this->_id      = $token_row->id;
        $this->_key     = $token_row->key;
        $this->_options = json_decode($token_row->options);
        $this->_user    = User::find($token_row->user_id);

        return $this;
	}

	/**
	 * Возвращает пользователя которому пренадлежит token
	 * 
	 * @return \User
	 */
	public function user()
	{
		if (!$this->_user)
			App::abort(500, 'Token not loaded!');

		return $this->_user;

	}

	/**
	 * Возвращает ключ текущего tokenа
     *
     * @return string
	 */
	public function key()
	{
		if (!$this->_key)
			App::abort(500, 'Token not loaded!');

        return $this->_key;
	}

    /**
     * Проверяем валидный ли token
     *
     * @param $option string
     * @return bool
     */
    public function valid($option = null)
    {
        if (!$this->_id)
            return false;

        if ($option)
            return in_array($option, $this->_options);

        return true;
    }

    /**
     * Удаляет токен из базы данных
     *
     * @return void
     */
    public function destroy()
    {
        if (!$this->_id)
            App::abort(500, 'Token not loaded!');

        DB::table('tokens')->delete($this->_id);
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