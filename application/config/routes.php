<?php

/*
	Wildcard:
		%var

	Argument access:
		$n   i.e. $1, $2, $3, etc
*/

$config['mappings'] = array(
	"/xx/%var/%var/%var"					=> '/test/index/$1/$2/$3'
);
