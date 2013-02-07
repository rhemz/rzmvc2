<?php

/*
CREATE TABLE `mvcsession` ( 
	`id` varchar(32) NOT NULL, 
	`contents` blob, 
	`modify_date` datetime NOT NULL, 
PRIMARY KEY (`id`),
KEY `modify_date` (`modify_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

class Session_Database extends Session_Base
{
	private $model;


	public function __construct()
	{
		$this->config = $this->config = Config::get_instance()->get('session.*');
		$this->_set_session_handler();
		$this->start();
	}


	public function _open()
	{
		$this->model = new Rz_MVC_Session_Model($this->config['table_name']);

		// garbage collect old sessions
		$this->_gc($this->config['timeout']);

		return true;
	}


	public function _close()
	{
		return true;
	}


	public function _read($id)
	{
		return $this->model->get_session_data($id);
	}


	public function _write($id, $data)
	{
		return $this->model->save_session_data($id, $data);
	}


	public function _destroy($id)
	{
		return $this->model->destroy_session($id);
	}


	public function _gc($age)
	{
		return $this->model->garbage_collect($age);
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



/**
* The base model for database sessions.  Should work database-agnostically, however I probably need
* to write individual drivers for each database type to handle race conditions (transactions, row locking).
* Perhaps require database drivers to implement functions for reading & writing the data.  GC and the like
* can be handled in a generic fashion.
*/
class Rz_MVC_Session_Model extends Model
{
	private $table;

	public function __construct($table)
	{
		$this->table = $table;

		parent::__construct();
	}


	public function get_session_data($id)
	{
		$sql = "SELECT contents FROM {$this->table} WHERE id = ?"; // FOR UPDATE on mysql
		if($this->query($sql, array($id)))
		{
			$result = $this->result();
			return $result->num_rows() == 1 ? $result->rows[0]->contents : false;
		}
		return false;
	}


	public function save_session_data($id, $data)
	{
		if($this->get_session_data($id) !== false)
		{
			return $this->query("UPDATE {$this->table} SET contents = ?, modify_date=now() WHERE id = ?", array($data, $id));
		}
		else
		{
			return $this->query("INSERT INTO {$this->table} (id, contents, modify_date) VALUES (?, ?, now())", array($id, $data));
		}
	}


	public function destroy_session($id)
	{
		return $this->query("DELETE FROM {$this->table} WHERE id = ?", array($id));
	}


	public function garbage_collect($age)
	{
		// return $this->query("DELETE FROM {$this->table} WHERE (now() - INTERVAL ? SECOND)", array($age));
	}

}