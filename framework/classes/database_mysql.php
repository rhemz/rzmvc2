<?php


class Database_MySQL implements Database_Interface
{
	const Default_Port = 3306;

	private $conn;
	
	private $host;
	private $port;
	private $user;
	private $password;
	private $database;

	public function __construct($config)
	{
		$this->port = is_null($config['port']) ? self::Default_Port : $config['port'];
		$this->host = sprintf("%s:%s", $config['hostname'], $this->port);
		$this->user = $config['username'];
		$this->password = $config['password'];
		$this->database = $config['database'];
	}


	public function connect()
	{
		$this->conn = mysql_connect($this->host, $this->user, $this->password);
		if(!$this->conn)
		{
			throw new Database_Connection_Exception('MySQL', $this->host, $this->port, $this->user, $this->password);
		}

		if(!mysql_select_db($this->database, $this->conn))
		{
			throw new Database_Selection_Exception(sprintf("Could not use the specified MySQL database (%s) on %s", $this->database, $this->host));
		}
	}


	public function query($sql)
	{
		echo $sql;
	}


	public function result()
	{

	}


	public function close()
	{

	}


	public function escape()
	{

	}
}