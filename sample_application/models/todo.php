<?php


class Todo_Model extends Model
{

	public function __construct()
	{
		parent::__construct();
	}


	public function get_lists($user_id)
	{
		$sql = "SELECT id, name FROM todo_list WHERE user_id = ?";
		if($this->query($sql, array($user_id)))
		{
			$result = $this->result();
			return $result->num_rows() > 0 ? $result->rows : null;
		}
		return null;
	}


	public function user_owns_list($user_id, $list_id)
	{
		$sql = "SELECT id FROM todo_list WHERE user_id = ? AND id = ?";
		if($this->query($sql, array($user_id, $list_id)))
		{
			$result = $this->result();
			return $result->num_rows() > 0;
		}
		return false;
	}


	public function user_owns_task($user_id, $task_id)
	{
		$sql = "SELECT todo_item.id FROM todo_item INNER JOIN todo_list ON todo_item.list_id = todo_list.id WHERE todo_list.user_id = ? AND todo_item.id = ?";
		if($this->query($sql, array($user_id, $task_id)))
		{
			$result = $this->result();
			return $result->num_rows() > 0;
		}
		return false;
	}


	public function get_list($list_id)
	{
		$sql = "SELECT id, text FROM todo_item WHERE list_id = ?";
		if($this->query($sql, array($list_id)))
		{
			$result = $this->result();
			return $result->num_rows() > 0 ? $result : false;
		}
		return false;
	}


	public function create_list($user_id, $name)
	{
		$sql = "INSERT INTO todo_list (user_id, name) VALUES (?, ?)";
		if($this->query($sql, array($user_id, $name)))
		{
			return $this->last_insert_id();
		}
		return false;
	}


	public function add_item($list_id, $item)
	{
		$sql = "INSERT INTO todo_item (list_id, text) VALUES (?, ?)";
		if($this->query($sql, array($list_id, $item)))
		{
			return $this->last_insert_id();
		}
		return false;
	}


	public function item_completed($task_id)
	{
		$sql = "DELETE FROM todo_item WHERE id = ?";
		return $this->query($sql, array($task_id));
	}

	
}