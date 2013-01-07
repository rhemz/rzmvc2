<?php


/**
* Base MVC class that all Controllers inherit from.  Responsible for making Config accessible to controllers
* and loading views
*/
class Rz_MVC
{
	private static $instance;
	public $config;
	

	/**
	* Create instance of Rz_MVC, set singleton
	*/
	public function __construct()
	{
		self::$instance =& $this;

		$this->config =& Config::get_instance();
	}


	/**
	* Load a view template, and make the data passed accessible within a local context
	* @param string $view The path to the view
	* @param array|null $data Data to make accessible to view
	*/
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


	/**
	* Get the Rz_MVC singleton instance
	*/
	public static function &get_instance()
	{
		return self::$instance;
	}


}


/**
*	Shortcut for &Rz_MVC::get_instance()
*/
function &get_mvc()
{
	return Rz_MVC::get_instance();
}
