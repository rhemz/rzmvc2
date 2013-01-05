<?php

class Test_Controller extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}


	public function index($var1 = null, $var2 = null, $var3 = null)
	{
		Logger::print_r(sprintf("var1: %s, var2: %s, var3: %s", $var1, $var2, $var3));

		$ipmodel = new Ip_Model();
		$ipmodel->get_records();
	}


	public function mssql()
	{
		$config =& Config::get_instance();
		$config->load('database');

		$db = new Database_MSSQL($config->get('database.*'));
		$db->connect();
	}


	public function input()
	{
		echo Input::ip();
		echo Input::user_agent();
	}


	public function validation()
	{
		$val = new Validation();

		$val->register('username', 'Username')
			->rule('required')
			->rule('min_length', 5)
			->rule('max_length', 20);

		$val->register('username', 'Password')
			->rule('required')
			->rule('is_numeric')
			->rule('min_val', 1)
			->rule('max_val', 100)
			->rule('custom', 'Test_Controller::testvalidation_custom_callback');

		if($val->validate())
		{
			echo 'valid';
		}
		else
		{
			$this->load_view('testform', array('validation' => $val));
		}
		// Logger::print_r($val);
	}





	public static function testvalidation_custom_callback($value, &$message)
	{
		$message = sprintf("%s is not divisible by 2", $value);

		return $value%2 == 0;
	}
}