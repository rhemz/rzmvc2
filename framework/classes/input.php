<?php


class Input
{
	
	public static function get($key, $default = null)
	{
		return isset($_GET[$key])
			? $_GET[$key]
			: $default;
	}


	public static function post($key, $default = null)
	{
		return isset($_POST[$key])
			? $_POST[$key]
			: $default;
	}


	public static function cookie($key, $default = null)
	{
		return isset($_COOKIE[$key])
			? $_COOKIE[$key]
			: $default;
	}


	public static function ip($default = '0.0.0.0')
	{
		return isset($_SERVER['REMOTE_ADDR'])
			? $_SERVER['REMOTE_ADDR']
			: $default;
	}


	public static function referer($default = '')
	{
		return isset($_SERVER['HTTP_REFERER']) && !is_null($_SERVER['HTTP_REFERER'])
			? $_SERVER['HTTP_REFERER']
			: $default;
	}


	public static function is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] == 'xmlhttprequest');
	}


	public static function request_method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}


	public static function user_agent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}



}