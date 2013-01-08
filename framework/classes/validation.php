<?php

/*
	Rules:
		-required (value is required)
		-required_if (value is required if other value is present)
		-equals (value ==)
		-match_regex (value matches a perl regular expression)
		-min_length (value is at least N characters
		-max_length (value is less than or equal to N characters)
		-exact_length (value is exactly this long)
		-min_val (numeric value is at least this)
		-max_val (numeric value is less than or equal to this)
		-is_numeric (value is a valid number)
		-is_int (value is a valud integer)
		-is_float (value is a valid float)
		-valid_ip (value is a valid IP address)
		-valid_uri (value is a valid URI)
		-valid_email (value is a valid email address)
		-valid_emails (value is a newline or comma delimited set of valid email addresses)
		-custom (allows user to use define a custom validation callback)

	Syntax:
		$validation->register('fieldname', 'Readable Name')
			->rule('required')
			->rule('min_length', 5)
			->rule('max_length', 20)
			->rule('custom', 'Some_Class::some_custom_callback')
			...
*/


class Validation
{
	const Static_Callback_Delimeter = '::';
	const HTTP_Method = 'POST';

	private $reflection;
	private $rules = array();
	private $readable = array();
	private $last;

	public $values = array();
	public $messages = array();

	private $rule_phrases = array(
		'required'		=> '%s is required',
		'required_if'	=> '%s is required if %s is present',
		'equals'		=> '%s must equal %s',
		'match_regex'	=> '%s must match pattern %s',
		'min_length'	=> '%s must be at least %s characters long',
		'max_length'	=> '%s must be less than %s characters',
		'exact_length'	=> '%s must be exactly %s characters',
		'min_val'		=> '%s must be at least %s',
		'max_val'		=> '%s must be less than %s',
		'is_numeric'	=> '%s must be a valid number',
		'is_int'		=> '%s must be a valid integer',
		'is_float'		=> '%s must be a valid decimal',
		'valid_ip'		=> '%s must be a valid IP address',
		'valid_uri'		=> '%s must be a valid URL',
		'valid_email'	=> '%s is an invalid email address',
		'valid_emails'	=> '%s must contain a valid list of email addresses'
	);
	
	

	/**
	* Create a new Validation instance
	*/
	public function __construct()
	{
		$this->reflection = new ReflectionClass($this);
	}


	/**
	* Register a user input for validation.  Generally followed by chained rule()s
	* @param string $key The input (GET, POST, etc) key
	* @param boolean $readable The human readable version of said key
	* @return $this
	*/
	public function register($key, $readable = null)
	{
		if(!array_key_exists($key, $this->rules))
		{
			$this->last = $key;
			$this->rules[$this->last] = array();
			$this->readable[$this->last] = $readable;
		}
		else
		{
			Logger::Log(sprintf("%s has already been registered for validation", $key), Log_Level::Warning);
		}

		return $this;
	}


	/**
	* Add a rule to the last registered input key.  Refer to the comment at the top for all available rules.
	* @param string $rule The rule to use
	* @param mixed $param The optional value to be used (i.e. maximum length, value to match, etc)
	* @return $this
	*/
	public function rule($rule, $param = null)
	{
		if($this->reflection->hasMethod($rule) && !is_null($this->last))
		{
			$this->rules[$this->last][$rule] = $param;
		}
		else
		{
			Logger::Log(sprintf("Rule '%s' does not exist, ignoring", $rule));
		}
		
		return $this;
	}


	/**
	* Run the currently registered validation rules.
	* @return boolean
	*/
	public function validate()
	{
		if(strtoupper(Input::request_method()) != self::HTTP_Method)
		{
			return false;
		}

		$valid = true;

		foreach($this->rules as $key => $rules)
		{
			$this->values[$key] = Input::post($key);
			
			foreach($rules as $rule => $param)
			{
				if(!$this->$rule($key, $param))
				{
					$valid = false;
					if($rule != 'custom')
					{
						$this->messages[$key] = sprintf($this->rule_phrases[$rule], $this->readable[$key], $param);
					}
					break;
				}
			}
		}
		return $valid;
	}


	/**
	* Get the error message (if set) for a given key.
	* @param string $key The input key to retrieve
	* @param string $prefix String to prefix the message with, e.g. <span class="error">
	* @param string $suffix String to suffix the message with
	* @return string|null
	*/
	public function message($key, $prefix = null, $suffix = null)
	{
		return isset($this->messages[$key]) ? sprintf("%s%s%s", $prefix, $this->messages[$key], $suffix) : null;
	}


	/**
	* Get the user-submitted value (usually used while settings persisting submitted values in forms on error)
	* @param string $key The input key
	*/
	public function value($key)
	{
		return isset($this->values[$key]) ? $this->values[$key] : null;
	}


	/**
	* @param string $key The input key
	* @return boolean
	*/
	public function error($key)
	{
		return isset($this->messages[$key]);
	}


	/**
	* Quick check to see if a form has been submitted
	* @return boolean
	*/
	private function form_submitted()
	{
		return isset($_POST) && sizeof($_POST) > 0;
	}


	/**
	* Check to see if a key is present in POST
	* @param string $key The input key
	* @return boolean
	*/
	private function is_present($key)
	{
		return Input::is_present($key, self::HTTP_Method);
	}


	/**
	* Specify an input as required
	* @param string $key The key
	* @return boolean
	*/
	private function required($key)
	{
		return $this->is_present($key);
	}


	/**
	* Input value A is required if B is present
	* @param string $key Value A
	* @param string $dependent Value B
	* @return boolean
	*/
	private function required_if($key, $dependent)
	{
		return $this->is_present($dependent) 
			? $this->is_present($key) 
			: true;
	}


	/**
	* Test to see if a given user input equals the specified value
	* @param string $key The input key
	* @param mixed value The value to match
	* @return boolean
	*/
	private function equals($key, $value)
	{
		return Input::post($key) == $value;
	}


	/**
	* Test a given user input against a Perl Regular Expression 
	* @param string $key The input key
	* @param string $pattern The regular expression pattern
	* @return boolean
	*/
	private function match_regex($key, $pattern)
	{
		return (preg_match($pattern, Input::post($key)) == 1)
			? true
			: false;
	}


	/**
	* Ensure a given user input is at least N characters long
	* @param string $key The input key
	* @param int $length The minimum length
	* @return boolean
	*/
	private function min_length($key, $length = 0)
	{
		return strlen(Input::post($key)) >= $length;
	}


	/**
	* Ensure a given user input is less than or equal to N characters
	* @param string $key The input key
	* @param int $length The maximum length
	* @return boolean
	*/
	private function max_length($key, $length = PHP_INT_MAX)
	{
		return strlen(Input::post($key)) <= $length;
	}


	/**
	* Ensure a given user input is exactly N characters long
	* @param string $key The input key
	* @param int $length The length $key must equal
	* @return boolean
	*/
	private function exact_length($key, $length)
	{
		return strlen(Input::post($key)) == $length;
	}


	/**
	* Ensure a given numerical user input is at least N
	* @param string $key The input key
	* @param int $val The minimum value
	* @return boolean
	*/
	private function min_val($key, $val)
	{
		return is_numeric(Input::post($key)) && (int)Input::post($key) >= (int)$val;
	}


	/**
	* Ensure a given numerical user input is less than or equal to N
	* @param string $key The input key
	* @param int $val The maximum value
	* @return boolean
	*/
	private function max_val($key, $val)
	{
		return is_numeric(Input::post($key)) && (int)Input::post($key) <= (int)$val;
	}


	/**
	* Ensure a given user input is a valid numeric value
	* @param string $key The input key
	* @return boolean
	*/
	private function is_numeric($key)
	{
		return is_numeric(Input::post($key));
	}


	/**
	* Ensure a given user input is a valid integer
	* @param string $key The input key
	* @return boolean
	*/
	private function is_int($key)
	{
		return is_int(Input::post($key));
	}


	/**
	* Ensure a given user input is a valid float
	* @param string $key The input key
	* @return boolean
	*/
	private function is_float($key)
	{
		return is_float(Input::post($key));
	}


	/**
	* Ensure a given user input is a valid IPv4 or IPv6 address
	* @param string $key The input key
	* @param string $version 'v4' or 'v6'
	* @return boolean
	*/
	private function valid_ip($key, $version = 'v4')
	{
		return $version == 'v6'
			? filter_var(Input::post($key), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
			: filter_var(Input::post($key), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}


	/**
	* Ensure a given user input is a valid URI
	* @param string $key The input key
	* @return boolean
	*/
	private function valid_uri($key)
	{
		return filter_var(Input::post($key), FILTER_VALIDATE_URL);
	}


	/**
	* Ensure a given user input is a valid email address according to RFC 5321
	* @param string $key The input key
	* @param return boolean
	*/
	private function valid_email($key)
	{
		return filter_var(Input::post($key), FILTER_VALIDATE_EMAIL);
	}


	/**
	* Ensure a given user input is a valid list of comma/newline delimited email addresses
	* @param string $key The input key
	* @return boolean
	*/
	private function valid_emails($key)
	{
		// if newline exists in value, explode on newline, otherwise assume comma delimited
		$emails = explode((strpos(Input::post($key), "\n") !== false ? "\n" : ","), Input::post($key));

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


	/**
	* Test a given user input against a user-defined custom callback function.  The callback must take 2 parameters,
	* a value, and a message reference ($val, &$message), and return a boolean.  The user is responsible for setting 
	* the custom error message for this function, by setting $message within the method context.
	* @param string $key The input key
	* @param string $callback The static callback method, e.g. 'SomeValidationClass:validate_me'
	* @return boolean
	*/ 
	private function custom($key, $callback)
	{
		if(sizeof($call = explode(self::Static_Callback_Delimeter, $callback)) == 2 && method_exists($call[0], $call[1]))
		{
			$message = '';

			if(!call_user_func_array($callback, array(Input::post($key), &$message)))
			{
				$this->messages[$key] = $message;
				return false;
			}
			return true;
		}

		Logger::log(sprintf("Validation callback function %s was not found", $callback), Log_Level::Error);
		return false;
	}
}