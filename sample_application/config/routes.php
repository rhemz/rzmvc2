<?php

/*
	Wildcard:
		%var

	Argument access:
		$n   i.e. $1, $2, $3, etc

	Ex: 
		"/xx/%var/%var/%var"	=> '/test/index/$1/$2/$3',
*/

$config['mappings'] = array(
	
	"/login"								=> '/account/login',
	"/logout"								=> '/account/logout'
	
);

$config['404_view'] = 'common/404';