<?php


interface Database_Interface
{
	public function __construct($config);

	public function connect();
	
	public function query($sql);

	public function result();

	public function close();

	public function escape();

}


class Database_Connection_Exception extends Rz_MVC_Exception
{
	public function __construct($msg) { parent::__construct($msg); }
}