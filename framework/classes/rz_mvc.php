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


	public function load_view($view, $data)
	{
		if(!is_null($data) && sizeof($data))
		{
			foreach($data as $key => $val)
			{
				$$key = $val;
			}
		}

		include(APPLICATION_PATH . $this->config->get('paths.views') . DIRECTORY_SEPARATOR . $view . PHP_EXT);
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
