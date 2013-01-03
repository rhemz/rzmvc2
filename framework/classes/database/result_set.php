<?php


class Result_Set
{
	public $rows = array();


	public function __construct($data)
	{
		// make selected column names and their values accessible as instance variables
		foreach($data as $row)
		{
			$row_obj = new stdClass();
			
			foreach($row as $key => $value)
			{
				$row_obj->$key = $value;
			}

			$this->rows[] = $row_obj;
		}
	}
}