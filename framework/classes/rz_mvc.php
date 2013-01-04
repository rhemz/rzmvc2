<?php


class Rz_MVC
{
	private static $instance;
	public $config;
	

	public function __construct()
	{
		self::$instance =& $this;

		$this->config =& Config::get_instance();
	}


	public function load_view($view, $data = null)
	{
		if(!is_null($data) && sizeof($data))
		{
			foreach($data as $key => $val)
			{
				$$key = $val;
			}
		}

		if(file_exists($v = APPLICATION_PATH . $this->config->get('paths.views') . DIRECTORY_SEPARATOR . $view . PHP_EXT))
		{
			include($v);
		}
		else
		{
			Logger::log(sprintf("%s view cannot be found", $view), Log_Level::Error);
		}
	}


	public static function &get_instance()
	{
		return self::$instance;
	}


}


/*
	Shortcut for &Rz_MVC::get_instance()
*/
function &get_mvc()
{
	return Rz_MVC::get_instance();
}
