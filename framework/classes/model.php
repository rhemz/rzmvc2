<?php


class Model
{
	const Driver_Prefix = 'Database';

	protected $db_object;
	private $db_reflection;

	public function __construct()
	{
		$config =& Config::get_instance();
		$config->load('database');

		$type = sprintf("%s_%s", self::Driver_Prefix, $config->get('database.type'));
		$this->db_object = new $type($config->get('database.*'));

		try
		{
			$this->db_object->connect();
		}
		catch(Database_Connection_Exception $dce)
		{
			Logger::log($dce->getMessage(), Log_Level::Error);
		}
		catch(Database_Selection_Exception $dse)
		{
			Logger::log($dse->getMessage(), Log_Level::Error);
		}

		$this->db_reflection = new ReflectionClass($this->db_object);		
	}


	public function __call($method, $args)
	{
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