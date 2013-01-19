<?php


abstract class Database_Base
{
	protected static $instance;

	protected $host;
	protected $port;
	protected $user;
	protected $password;
	protected $database;

	protected $conn;
	protected $result;


	abstract public function connect();

	abstract public function query($sql, $bindings = null);

	abstract public function result();

	abstract public function close();

	abstract public function escape($str);

	abstract public function last_insert_id();

	abstract protected function translate_binding_datatype($val);



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


	protected function parse_bindings($sql, $bindings)
	{
		// todo: use actual mysqli lib binding
		
		$qbits = explode('?', $sql);
		$i = 0;

		if(!is_null($bindings) && (sizeof($bindings) != substr_count($sql, '?')))
		{
			Logger::log(
				sprintf('The number of query bindings(%d) passed does not match the SQL statement (%d)', 
					sizeof($bindings), 
					sizeof(array_filter($qbits))
				), 
				Log_Level::Error);
		}

		// start building bound query
		$sql = $qbits[0];
		foreach($bindings as $val)
		{
			$sql .= $this->translate_binding_datatype($val) . $qbits[++$i];
		}
		
		return $sql;
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
