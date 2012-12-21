<?php

class Log_Level
{
	const Notice = 1;
	const Warning = 10;
	const Error = 20;
}



class Logger
{

	public static function log($message, $level = Log_Level::Notice)
	{
		// check level and call corresponding function.  for now, just echo
		echo $message;
	}


	public static function print_r($object)
	{
		echo '<pre>';
		print_r($object);
		echo '</pre>';
	}

}