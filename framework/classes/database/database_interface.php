<?php


interface Database_Interface
{
	public function __construct($config);

	public function connect();
	
	public function query($sql, $bindings = null);

	public function result();

	public function close();

	public function escape($str);

	public function last_insert_id();
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
