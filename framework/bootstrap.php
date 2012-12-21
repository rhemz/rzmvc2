<?php

require_once(FRAMEWORK_PATH . 'autoload.php');

$config =& Config::get_instance();
$config->load(array('paths', 'routes'));

$base = APPLICATION_PATH . $config->get('paths.controllers') . DIRECTORY_SEPARATOR;

$uri = preg_replace('/\?(.*)/', '', $_SERVER['REQUEST_URI']);
$uri = substr($uri, -1) == '/' ? substr($uri, 0, -1) : $uri; // figure out how to incorporate this into regex

// check for predefined route first.  todo: revise for arguments in URI segments
if(array_key_exists($uri, ($mapping = $config->get('routes.mappings'))))
{
	$uri = $mapping[$uri];
}

// map URI to controller
$uri = explode('/', trim(substr($uri, 1), '/'));
$index = 0;

foreach($uri as $segment)
{
	$index++;
	if(!strlen($segment))
	{
		$segment = $config->get('routes.default_controller');
	}
	else if(is_link($base . $segment) || is_dir($base . $segment))
	{
		$base .= $segment . DIRECTORY_SEPARATOR;
	}

	if(is_file($base . $segment . PHP_EXT))
	{
		$controller = sprintf("%s_%s", $segment, $config->get('routes.controller_suffix'));
		$controller = new $controller();
		break;
	}
}

$func = $index == sizeof($uri) ? $config->get('routes.default_function') : $uri[$index];
if(isset($controller) && is_object($controller) && $controller->_has_method($func))
{
	sizeof($args = array_slice($uri, ++$index))
		? call_user_func_array(array($controller, $func), $args)
		: $controller->$func();
}
else
{
	echo 'not found';
}