<?php

class Test_Controller extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}


	public function index($var1 = null, $var2 = null)
	{
		Logger::print_r(sprintf("var1: %s, var2: %s", $var1, $var2));

		$ipmodel = new Ip_Model();
		$ipmodel->get_records();
	}
}