<?php


class Database_MSSQL implements Database_Interface
{
	const PDO_Connection_String = "sqlsrv:Server=%s;Database=%s;";

	private $conn;
	private $config;

	public function __construct($config)
	{
		$this->config = $config;
	}

	public function connect()
	{
		try
		{
			$this->conn = new PDO(
				sprintf(self::PDO_Connection_String, $this->config['hostname'], $this->config['database']),
				$this->config['username'],
				$this->config['password']);
		}
		catch(PDOException $e)
		{
			Logger::log($e->getMessage(), Log_Level::Error);
		}
	}
	
	public function query($sql, $bindings = null)
	{
		
	}

	public function result()
	{
		
	}

	public function close()
	{
		
	}

	public function escape($str)
	{
		
	}
}