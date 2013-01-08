<?php

// http://www.php.net/manual/en/function.session-set-save-handler.php
// https://github.com/fuel/core/blob/1.5/develop/classes/session/driver.php
// http://www.php.net/session_regenerate_id
// http://stackoverflow.com/questions/11596082/php-session-class-similar-to-codeigniter-session-class

abstract class Base_Session
{
	protected $data = array();
	protected $config = array();
	protected $timestamp = null;
	protected $started = false;


	abstract public function start();

	abstract public function open();

	abstract public function close();

	abstract public function read();

	abstract public function write();

	abstract public function destroy();

	abstract public function gc();

}