<?php

/*
	Rules:
		-required (value is required)
		-required_if (value is required if other value is present)
		-equals (value ==)
		-match_regex
		-min_length
		-max_length
		-exact_length
		-min_val
		-max_val
		-is_numeric
		-is_int
		-is_float
		-valid_ip
		-valid_uri
		-valid_email
		-valid_emails

	Syntax:
		$validation->register('fieldname', 'Readable Name')
			->rule('required')
			->rule('min_length', 5)
			->rule('max_length', 20)
			...
*/


class Validation
{
	private $reflection;
	private $keys = array();
	private $last;


	public function __construct()
	{
		$this->reflection = new ReflectionClass($this);
	}


	public function register($key, $readable = null)
	{
		if(!array_key_exists($key, $this->keys))
		{
			$this->last = $key;
			$this->keys[$this->last] = array();
		}
		else
		{
			Logger::Log(sprintf("%s has already been registered for validation", $key), Log_Level::Warning);
		}

		return $this;
	}


	public function rule($rule, $param = null)
	{
		if($this->reflection->hasMethod($rule) && !is_null($this->last))
		{
			$this->keys[$this->last][$rule] = $param;
		}
		else
		{
			Logger::Log(sprintf("Rule '%s' does not exist, ignoring", $rule));
		}
		
		return $this;
	}


	public function validate()
	{
		$this->last = null;

	}


	private function is_present($key)
	{
		isset($_REQUEST[$key]) && !is_null($_REQUEST[$key]) && strlen($_REQUEST[$key]);
	}


	private function required($key)
	{
		return $this->is_present($key);
	}


	private function required_if($key, $dependent)
	{
		return $this->is_present($dependent) 
			? $this->is_present($key) 
			: true;
	}


	private function equals($key, $value)
	{
		return $_REQUEST[$key] == $value;
	}


	private function match_regex($key, $pattern)
	{
		return (preg_match($pattern, $_REQUEST[$key]) == 1)
			? true
			: false;
	}


	private function min_length($key, $length = 0)
	{
		return strlen($_REQUEST[$key]) >= $length;
	}


	private function max_length($key, $length = PHP_INT_MAX)
	{
		return strlen($_REQUEST[$key]) <= $length;
	}


	private function exact_length($key, $length)
	{
		return strlen($_REQUEST[$key]) == $length;
	}


	private function min_val($key, $val)
	{
		return is_numeric($_REQUEST[$key]) && (int)$_REQUEST[$key] >= (int)$val;
	}


	private function max_val($key, $val)
	{
		return is_numeric($_REQUEST[$key]) && (int)$_REQUEST[$key] <= (int)$val;
	}


	private function is_numeric($key)
	{
		return is_numeric($_REQUEST[$key]);
	}


	private function is_int($key)
	{
		return is_int($_REQUEST[$key]);
	}


	private function is_float($key)
	{
		return is_float($_REQUEST[$key]);
	}


	private function valid_ip($key, $version = 'v4')
	{
		return $version == 'v6'
			? filter_var($_REQUEST[$key], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
			: filter_var($_REQUEST[$key], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}


	private function valid_uri($key)
	{
		return filter_var($_REQUEST[$key], FILTER_VALIDATE_URL);
	}


	private function valid_email($key)
	{
		return filter_var($_REQUEST[$key], FILTER_VALIDATE_EMAIL);
	}


	private function valid_emails($key)
	{
		// if newline exists in value, explode on newline, otherwise assume comma delimited
		$emails = strpos($_REQUEST[$key], "\n") !== fals
			? explode("\n", $_REQUEST[$key])
			: explode(',', $_REQUEST[$key]);

		if(!is_array($emails) || !sizeof($emails))
		{
			return false;
		}

		foreach($emails as $email)
		{
			if(!$this->valid_email($email))
			{
				return false;
			}
		}

		return true;
	}
}