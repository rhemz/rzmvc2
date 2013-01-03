<?php


class Logger
{
	const Notice = 'notices.log';
	const Warning = 'warnings.log';
	const Error = 'errors.log';

	const Write_Mode = 'a+';


	public static function log($message, $level = Log_Level::Notice)
	{
		$config =& Config::get_instance();
		echo $config->get('logging.log_level');

		if($config->get('logging.log_level') & Log_Level::Warning)
		{
			echo 'logging warnings!';
		}

		self::write_logfile(self::All, $message);

		switch($level)
		{
			case Log_Level::Notice:
				self::notice($message);
				break;

			case Log_Level::Warning:
				self::warning($message);
				break;

			case Log_Level::Error:
				self::error($message);
				break;
		}
	}


	// these methods will do something more than just echo

	private static function notice($message)
	{
		echo $message;
		self::write_logfile(self::Notice, $message);
	}

	private static function warning($message)
	{
		echo $message;
		self::write_logfile(self::Warning, $message);
	}

	private static function error($message)
	{
		echo $message;
		self::write_logfile(self::Error, $message);
		exit();
	}

	private static function write_logfile($file, $message)
	{
		$handle = fopen(LOG_PATH . DIRECTORY_SEPARATOR . $file, self::Write_Mode);
		fwrite($handle, sprintf("%s: %s%s", date(DATE_RFC822), $message, PHP_EOL));
		fclose($handle);
	}


	public static function print_r($object)
	{
		echo '<pre>';
		print_r($object);
		echo '</pre>';
	}

}