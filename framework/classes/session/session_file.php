<?php

class Session_File extends Base_Session
{
	private $file_path;

	public function __construct()
	{
		$this->config = Config::get_instance()->get('session.*');
		$this->file_path = SESSION_PATH . DIRECTORY_SEPARATOR . $this->config['file_prefix'];
		$this->start();
		Logger::log('session id: ' . session_id());
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
		return file_exists($this->file_path . $id)
			? file_get_contents($this->file_path . $id)
			: false;
	}

	public function _write($id, $data)
	{
		return file_put_contents($this->file_path . $id, $data) === false
			? false
			: true;
	}

	public function _destroy($id)
	{
		session_unset();
		if(file_exists($this->file_path . $id))
		{
			unlink($this->file_path . $id);
		}
		return true;
	}

	public function _gc()
	{
		foreach(glob(sprintf("%s*", $this->file_path)) as $session_file)
		{
			if((filemtime($session_file) + $this->config['timeout'] < $time) && file_exists($session_file))
			{
				unlink($session_file);
			}
		}
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