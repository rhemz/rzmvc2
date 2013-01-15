<?php


class Output
{

	public static function redirect($location, $perm = false)
	{
		$perm
			? header(HTTP_Status_Code::Moved_Permanently)
			: header(HTTP_Status_Code::Temporary_Redirect);

		header(sprintf("Location: %s", $location));
		exit();
	}


	public static function return_json($data)
	{
		echo json_encode($data);
		exit();
	} 
}