<?php

class Session_File extends Base_Session
{
	private $file_path;

	public function __construct()
	{
		$this->config = Config::get_instance()->get('session.*');

		ini_set('session.gc_maxlifetime', $this->config['timeout']);
		ini_set('session.gc_probability', 1);
		ini_set('session.gc_divisor', 10);


		$this->start();

		// $this->_set_session_handler();  don't need this for file-based sessions
	}

	public function _open()
	{
		return true;
	}

	public function _close()
	{
		return true;
	}

	public function _read($id)
	{
		return true;
	}

	public function _write($id, $data)
	{
		return true;
	}

	public function _destroy($id)
	{
		return true;
	}

	public function _gc()
	{
		return true;
	}


	public function set($key, $data)
	{
		$_SESSION[$key] = $data;
		return true;
	}


	public function get($key, $default = null)
	{
		return isset($_SESSION[$key])
			? $_SESSION[$key]
			: $default;
	}
}