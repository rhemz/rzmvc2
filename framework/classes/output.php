<?php


class Output
{
	const File_Mode = 'rb'; // read, binary no translate
	const File_Chunk_Size = 4096;


	public static function redirect($location, $perm = false)
	{
		$perm
			? header(HTTP_Status_Code::Moved_Permanently)
			: header(HTTP_Status_Code::Temporary_Redirect);

		header(sprintf('Location: %s', $location));
		exit();
	}


	public static function file($path, $name = null, $mime_type = null)
	{
		@apache_setenv('no-gzip', 1);
		@ini_set('zlib.output_compression', 'Off');

		if(!is_file($path))
		{
			Logger::log(sprintf('Cannot access path: %s', $path), Log_Level::Error);
			return false;
		}

		$size = filesize($path);
		$name = !is_null($name) ? $name : basename($path);

		if($file = fopen($path, self::File_Mode))
		{
			header('Pragma: public');
			header('Expires: -1');
			header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
			header(sprintf('Content-Type: %s', ($is_null($mime_type) ? 'application/octet-stream' : $mime_type)));
			header('Content-Length: ' . sprintf('%u', $size));
			header(sprintf('Content-Disposition: attachment; filename="%s"', $name));

			while(($chunk = fread($file, self::File_Chunk_Size)) !== false)
			{
				echo $chunk;
			}
			return true;
		}
		return false;
	}

	public static function return_json($data)
	{
		echo json_encode($data);
		exit();
	} 
}