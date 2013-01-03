<?php


class IP_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_records()
	{
		$sql = "SELECT
					request_id,
					ip_address,
					time
				FROM
					requests
				WHERE
					ip_address = ?
				AND
					user_agent = ?";

		if($this->query($sql, array('10.49.136.176', 'UnitTest Useragent')))
		{
			Logger::print_r($this->result());
		}
	}
}