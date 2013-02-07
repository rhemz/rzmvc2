<?php

/*
	Ideas:
	http://www.php.net/manual/en/function.session-set-save-handler.php
	https://github.com/fuel/core/blob/1.5/develop/classes/session/driver.php
	http://www.php.net/session_regenerate_id
	http://stackoverflow.com/questions/11596082/php-session-class-similar-to-codeigniter-session-class
*/

abstract class Session_Base
{
	protected $data = array();
	protected $config = array();
	protected $timestamp = null;

	abstract public function _open();

	abstract public function _close();

	abstract public function _read($id);

	abstract public function _write($id, $data);

	abstract public function _destroy($id);

	abstract public function _gc($age);

	abstract public function set($key, $data);

	abstract public function get($key, $default = null);


	protected function start()
	{
		// sanity check, just in case user tries to manually start up extra controllers
		$active = function_exists('session_status')
			? (session_status() == PHP_SESSION_ACTIVE)
			: (strlen(session_id()) ? true : false);
		
		if(!$active)
		{
			session_name(Config::get_instance()->get('session.name'));

			@session_start();
		}
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


	protected function _generate_key()
	{
		$key = Config::get_instance()->get('session.hash');

		return sha1(microtime(true) . $key);
	}



}