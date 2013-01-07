<?php


/**
* The class all user defined Models inherit from.  Responsible for instantiating the appropriate
* database driver from the application configuration and passing method calls onto said driver.
*/
class Model
{
	const Driver_Prefix = 'Database';

	protected $db_object;
	private $db_reflection;

	/**
	* Create an instance of the Model class.  Never called directly.  Load the database configuration and create
	* a new/use existing connection to said database.
	*/
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


	/**
	* Overloaded PHP magic method __call(), whenever $this->methodname() is called by a child class, this attempts
	* to pass said call onto the database driver file.
	*/
	public function __call($method, $args)
	{
		if($this->db_reflection->hasMethod($method))
		{
			return call_user_func_array(array($this->db_object, $method), $args);
		}
		else
		{
			Logger::log(sprintf('Model does not contain method: %s()', $method), Log_Level::Error);
		}
	}
}