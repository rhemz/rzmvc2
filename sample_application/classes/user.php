<?php

class User
{
	public $id;
	public $name;
	public $email;
	

	public function __construct($id = null, $name = null, $email = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
	}


	public function __toString()
	{
		return $this->name;
	}

}