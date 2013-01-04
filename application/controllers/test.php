<?php

class Test_Controller extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}


	public function index($var1 = null, $var2 = null, $var3 = null, $var4 = null, $var5 = null)
	{
		Logger::print_r(sprintf("var1: %s, var2: %s, var3: %s, var4: %s, var5: %s", $var1, $var2, $var3, $var4, $var5));

		$ipmodel = new Ip_Model();
		$ipmodel->get_records();
	}


	public function mssql()
	{

	}


	public function input()
	{
		echo Input::ip();
		echo Input::user_agent();
	}

	public function validation()
	{
		$val = new Validation();

		$val->register('testval', 'Test Value 1')
			->rule('required')
			->rule('min_length', 5)
			->rule('max_length', 20);

		$val->register('testval2', 'Test Value 2')
			->rule('required')
			->rule('is_numeric')
			->rule('min_val', 1)
			->rule('max_val', 100);

		Logger::print_r($val);
	}
}