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
					requests";

		$this->query($sql);
	}
}