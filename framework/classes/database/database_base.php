<?php


abstract class Database_Base
{
	protected static $instance;


	abstract public function connect();

	abstract public function query($sql, $bindings = null);

	abstract public function result();

	abstract public function close();

	abstract public function escape($str);

	abstract public function last_insert_id();



	public static function &get_instance($type = null, $config = null)
	{
		if(is_null(self::$instance) && !is_null($type) && !is_null($config))
		{
			self::$instance = new $type($config);

			try
			{
				self::$instance->connect();
			}
			catch(Database_Connection_Exception $dce)
			{
				Logger::log($dce->getMessage(), Log_Level::Error);
			}
			catch(Database_Selection_Exception $dse)
			{
				Logger::log($dse->getMessage(), Log_Level::Error);
			}
		}
		return self::$instance;
	}


	/*
	public function __destruct()
	{
		if(!is_null(self::$instance))
		{
			$this->close();
		}
	}
	*/


}





class Database_Connection_Exception extends Rz_MVC_Exception
{
	public function __construct($type, $host, $port, $user, $pass)
	{ 
		$msg = sprintf("Unable to connect to %s server ", $type);

		$msg .= ENVIRONMENT == Environment::Development
			? sprintf("(%s) on port %d with credentials %s/%s", $host, $port, $user, $pass)
			: "using the supplied settings";

		parent::__construct($msg);
	}
}


class Database_Selection_Exception extends Rz_MVC_Exception
{
	public function __construct($msg) { parent::__construct($msg); }
}
