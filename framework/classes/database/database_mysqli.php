<?php


class Database_MySQLi extends Database_Base
{
	const Default_Port = 3306;

	private $conn;
	
	private $host;
	private $port;
	private $user;
	private $password;
	private $database;

	private $result;


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
		// mysqli persistent connections only became available in 5.3.0
		/*
		$this->conn = strnatcmp(phpversion(), '5.3.0') >= 0
			? new mysqli('p:' . $this->host, $this->user, $this->password, $this->database)
			: new mysqli($this->host, $this->user, $this->password, $this->database);
		*/
			
		// for now just use regular mysqli
		$this->conn	= new mysqli($this->host, $this->user, $this->password, $this->database);

		if($this->conn->connect_error)
		{
			throw new Database_Connection_Exception('MySQLi', $this->host, $this->port, $this->user, $this->password);
		}
	}


	public function query($sql, $bindings = null)
	{
		// figure out how to handle bools and other datatypes that don't map to mysql datatypes.  for now
		// just use homegrown bindings.
		/*
		if(is_null($bindings))
		{
			$this->result = $this->conn->query($sql);
		}
		else
		{
			if(!$stmt = $this->conn->prepare($sql))
			{
				Logger::log(sprintf("Error preparing statement: %s", $this->conn->error), Log_Level::Error);
			}

			$bind_datatypes = '';
			foreach($bindings as $binding)
			{
				$bind_datatypes .= $this->get_binding_typechar($binding);
			}
		}
		*/
		if(!$this->result = $this->conn->query(is_null($bindings) ? $sql : $this->parse_bindings($sql, $bindings)))
		{
			Logger::log($this->conn->error, Log_Level::Warning);
			// throw error or return false
		}
		return true;
	}





	public function result()
	{
		if($this->result->num_rows > 0)
		{
			$result = array();
			while($row = $this->result->fetch_assoc())
			{
				$result[] = $row;
			}
			$this->result->free();

			return new Result_Set($result);
		}
		return new Result_Set();
	}


	public function close()
	{
		$this->conn->close();
	}


	public function escape($str)
	{
		return $this->conn->real_escape_string($str);
	}


	public function last_insert_id()
	{
		return $this->conn->insert_id;
	}


	private function parse_bindings($sql, $bindings)
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


	private function translate_binding_datatype($val)
	{
		if(is_string($val))
		{
			return sprintf("'%s'", $this->escape($val));
		}
		else if(is_bool($val))
		{
			return ($val === true) ? 1 : 0;
		}
		else if(is_null($val))
		{
			return 'NULL';
		}

		return $val;
	}


	private function get_binding_typechar($binding)
	{
		if(is_int($binding)) return 'i';

		if(is_float($binding)) return 'd';

		if(is_string($binding)) return 's';

		return 'b';
	}


}