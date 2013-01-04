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

	public function __construct()
	{

	}


	public function register($key, $readable = null)
	{

	}


	public function rule($rule, $param = null)
	{
		
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


	private function valid_ip($key)
	{

	}


	private function valid_uri($key)
	{

	}


	private function valid_email($key)
	{

	}


	private function valid_emails($key)
	{

	}
}