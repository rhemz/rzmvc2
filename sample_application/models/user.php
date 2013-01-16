<?php

class User_Model extends Model
{

	public function __construct()
	{
		parent::__construct();
	}


	public function create_user($username, $email, $password)
	{
		$sql = "INSERT INTO users (`name`, `email`, `password`) VALUES (?, ?, ?)";
		if($this->query($sql, array($username, $email, sha1($password))))
		{
			return $this->last_insert_id();
		}
		return false;
	}



	public function get_password($email)
	{
		$sql = "SELECT password FROM users WHERE email = ?";
		if($this->query($sql, array($email)))
		{
			$result = $this->result();
			if($result->num_rows() == 1)
			{
				return $result->rows[0]->password;
			}
		}

		return false;
	}


	public function get_user_by_email($email)
	{
		$sql = "SELECT id, name FROM users WHERE email = ?";
		if($this->query($sql, array($email)))
		{
			$result = $this->result();
			if($result->num_rows() == 1)
			{
				return new User($result->rows[0]->id, $result->rows[0]->name, $email);
			}
		}

		return false;
	}

}