<?php

function __autoload($class)
{
	// todo: use config class
	
	$paths = array(APPLICATION_PATH, FRAMEWORK_PATH);
	$nolook = array('config', 'views');
	$suffixes = array('_controller', '_model');

	foreach($suffixes as $suffix)
	{
		if(stripos($class, $suffix) !== false)
		{
			$class = str_ireplace($suffix, '', $class);
		}
	}

	// look in application directory first, then framework
	foreach($paths as $path)
	{
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $item)
		{
			if(	$item->isDir() 
				&& !in_array($item, $nolook)
				&& file_exists($p = $item->getPathname() . DIRECTORY_SEPARATOR . strtolower($class) . PHP_EXT))
			{
				require_once($p);
				return;
			}
		}
	}
}