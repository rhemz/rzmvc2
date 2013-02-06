<?php


/**
* Base MVC class that all Controllers inherit from.  Responsible for making Config accessible to controllers
* and loading views
*/
class Rz_MVC
{
	private static $instance;
	public $config;
	public $session;

	const Session_Driver_Prefix = 'Session';


	/**
	* Create instance of Rz_MVC, set singleton
	*/
	public function __construct()
	{
		self::$instance =& $this;

		$this->config =& Config::get_instance();

		// setup session
		$session_type = sprintf("%s_%s", self::Session_Driver_Prefix, $this->config->get('session.type'));
		$this->session = new $session_type();
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

		// check application views, then fallback to framework views
		foreach(array(APPLICATION_PATH, FRAMEWORK_PATH) as $source)
		{
			if(file_exists($v = $source . $this->config->get('paths.views') . DIRECTORY_SEPARATOR . $view . PHP_EXT))
			{
				include($v);
				return;
			}
		}
		Logger::log(sprintf("%s view cannot be found", $view), Log_Level::Error);
	}


	/**
	* If enabled in global configuration, rzMVC catches fatal PHP errors and displays them in an alternate view
	* in addition to ensuring that the custom session handler is properly closed.  Registered in bootstrap.
	*/
	public static function hook_shutdown()
	{
		// if something broke real bad
		if($e = error_get_last())
		{
			if(isset($e['type']) && 
				$e['type'] == E_PARSE || 
				$e['type'] == E_ERROR || 
				$e['type'] == E_COMPILE_ERROR)
			{
				self::get_instance()->load_view('php_error', array('error' => $e));
			}
		}

		session_write_close(); // make sure to close custom session handler
	}


	/**
	* Get the Rz_MVC singleton instance
	*/
	public static function &get_instance()
	{
		if(is_null(self::$instance))
		{
			new Rz_MVC();
		}
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
