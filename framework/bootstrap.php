<?php

require_once(FRAMEWORK_PATH . 'autoload' . PHP_EXT);

$config =& Config::get_instance();
$config->load(array('environment', 'logging'));

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
