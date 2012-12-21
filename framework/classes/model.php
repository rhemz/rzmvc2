<?php


class Model
{
	protected $db_object;
	private $db_reflection;

	public function __construct()
	{
		$config =& Config::get_instance();
		$config->load('database');

		$this->db_object = new Database_MySQL($config->get('database.*'));

		try
		{
			$this->db_object->connect();
		}
		catch(Database_Connection_Exception $dce)
		{
			Logger::log($dce->getMessage(), Log_Level::Error);
		}

		$this->db_reflection = new ReflectionClass($this->db_object);		
	}


	public function __call($method, $args)
	{
		// call the $db_object->$name
		if($this->db_reflection->hasMethod($method))
		{
			call_user_func_array(array($this->db_object, $method), $args);
		}
		else
		{
			Logger::log(sprintf('Model does not contain method: %s()', $method), Log_Level::Error);
		}
	}
}