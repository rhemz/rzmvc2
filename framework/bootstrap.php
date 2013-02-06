<?php

require_once(FRAMEWORK_PATH . 'autoload' . PHP_EXT);

$config =& Config::get_instance();
$config->load(array('global', 'environment', 'logging', 'session'));

if($config->get('global.framework_handle_fatal_errors'))
{
	// register custom shutdown and error handler hooks
	register_shutdown_function('Rz_MVC::hook_shutdown');
	ini_set('display_errors', 0);
}

if(!$config->user_config_exists('environment'))
{
	Logger::log('No application environment setting present, falling back to framework default', Log_Level::Warning);
}

define('ENVIRONMENT', $config->get('environment.environment'));

$uri = rtrim(preg_replace('/\?(.*)/', '', $_SERVER['REQUEST_URI']), '/');

$router = new Router($uri);

$router->check_route()
	? $router->execute_route()
	: $router->show_404();
