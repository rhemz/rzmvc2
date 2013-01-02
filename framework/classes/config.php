<?php


class Config
{
	
	const Config_Dir = 'config';
	const Config_Delimiter = '.';
	const Config_Wildcard = '*';

	private $config = array();
	private $framework_config;
	private $application_config;

	private static $instance;



	public function __construct()
	{
		$this->framework_config = FRAMEWORK_PATH . self::Config_Dir . DIRECTORY_SEPARATOR . '%s' . PHP_EXT;
		$this->application_config = APPLICATION_PATH . self::Config_Dir . DIRECTORY_SEPARATOR . '%s' . PHP_EXT;
	}


	public static function &get_instance()
	{
		if(is_null(self::$instance))
		{
			self::$instance = new Config();
		}
		return self::$instance;
	}


	public function load($files)
	{
		/*
			1. Determine paths to file
			2. If application config exists
				-attempt to load corresponding framework config
				-any values present in framework config should be overridden with application values
				-any values present in application config not existing in framework config should persist
			3. If application config does not exist, load framework config
			4. If framework config does not exist, throw error
		*/

		if(!is_array($files))
		{
			$files = array("{$files}");
		}

		foreach($files as $file)
		{
			if(isset($this->config[$file])) continue;

			$ac = sprintf($this->application_config, $file);
			$fc = sprintf($this->framework_config, $file);
			
			try
			{
				if(file_exists($ac))
				{
					$ac = $this->get_contents($ac);

					if(file_exists($fc))
					{
						$fc = $this->get_contents($fc);
						$this->config[$file] = array_merge($fc, $ac);
					}
					else
					{
						$this->config[$file] = $ac;
					}
				}
				else
				{
					$this->config[$file] = $this->get_contents($fc);
				}
			}
			catch(Config_Not_Found_Exception $cnfe)
			{
				Logger::log($cnfe->getMessage(), Log_Level::Error);
			}
			catch(Config_Malformed_Exception $cme)
			{
				Logger::log($cme->getMessage(), Log_Level::Error);
			}
		}
	}


	public function get($key)
	{
		$parts = explode(self::Config_Delimiter, $key);
		if(sizeof($parts) != 2)
		{
			// was throwing an exception, but it would be super obnoxious to wrap try around every config get.
			Logger::log(sprintf('The supplied selector (%s) is invalid.  Expected format: "section%skey"', $key, self::Config_Delimiter));
			return null;
		}

		if(isset($this->config[$parts[0]]) && $parts[1] == self::Config_Wildcard)
		{
			return $this->config[$parts[0]];
		}
		else if(isset($this->config[$parts[0]][$parts[1]]))
		{
			return $this->config[$parts[0]][$parts[1]];
		}
		return null;

	}


	private function get_contents($path)
	{
		if(!file_exists($path))
		{
			throw new Config_Not_Found_Exception($path);
		}

		require_once($path);

		if(!isset($config) || !is_array($config))
		{
			throw new Config_Malformed_Exception($path);
		}

		return $config;
	}


	public function user_config_exists($file)
	{
		return file_exists(sprintf($this->application_config, $file));
	}

}






/*
	Exceptions
*/

class Config_Not_Found_Exception extends Rz_MVC_Exception
{
	public function __construct($config_file)
	{
		$msg = sprintf('The following configuration file was not found: %s', $config_file);
		parent::__construct($msg);
	}
}


class Framework_Config_Not_Found_Exception extends Rz_MVC_Exception
{
	public function __construct($config_file)
	{
		$msg = sprintf('The following framework configuration file was not found: %s', $config_file);
		parent::__construct($msg);
	}
}


class Config_Malformed_Exception extends Rz_MVC_Exception
{
	public function __construct($config_file)
	{
		$msg = sprintf('The following framework configuration file is malformed: %s', $config_file);
		parent::__construct($msg);
	}
}


class Config_Selector_Malformed_Exception extends Rz_MVC_Exception
{
	public function __construct($key)
	{
		$msg = sprintf('The supplied config selector (%s) is invalid.  Expected format: "section%skey"', $key, Config::Config_Delimiter);
		parent::__construct($msg);
	}
}