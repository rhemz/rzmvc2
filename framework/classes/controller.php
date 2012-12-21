<?php


class Controller extends Rz_MVC
{
	public function __construct()
	{
		parent::__construct();
	}


	public function _has_method($method)
	{
		$rc = new ReflectionClass($this);
		return $rc->hasMethod($method);
	}
}