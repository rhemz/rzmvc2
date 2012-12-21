<?php

class Rz_MVC_Exception extends Exception
{
	public function __construct($message, $code = null)
	{
		parent::__construct($message, $code);
	}

	public function __toString()
	{
		return sprintf('%s: [%s]', __CLASS__, $this->message);
	}
	
}