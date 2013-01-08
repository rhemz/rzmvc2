<?php

/*
	Ideas:
	http://www.php.net/manual/en/function.session-set-save-handler.php
	https://github.com/fuel/core/blob/1.5/develop/classes/session/driver.php
	http://www.php.net/session_regenerate_id
	http://stackoverflow.com/questions/11596082/php-session-class-similar-to-codeigniter-session-class
*/

abstract class Base_Session
{
	protected $data = array();
	protected $config = array();
	protected $timestamp = null;
	protected $started = false;


	abstract public function _open();

	abstract public function _close();

	abstract public function _read($id);

	abstract public function _write($id, $data);

	abstract public function _destroy($id);

	abstract public function _gc();

	abstract public function set($key, $data);

	abstract public function get($key, $default = null);


	protected function start()
	{
		$this->_set_session_path();
		$this->_set_session_handler();

		session_name(Config::get_instance()->get('session.name'));

		session_start();
	}


	protected function _set_session_handler()
	{
		session_set_save_handler(
			array($this, '_open'), 
			array($this, '_close'),
			array($this, '_read'),
			array($this, '_write'),
			array($this, '_destroy'),
			array($this, '_gc')
		);
	}

	protected function _set_session_path()
	{
		if(!is_dir(SESSION_PATH))
		{
			mkdir(SESSION_PATH);
		}

		session_save_path(SESSION_PATH);
	}

	protected function _generate_key()
	{
		$key = Config::get_instance()->get('session.hash');

		return sha1(microtime(true) . $key);
	}



}