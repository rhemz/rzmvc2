<?php

define('APPLICATION_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '../application') . DIRECTORY_SEPARATOR);

define('FRAMEWORK_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '../framework') . DIRECTORY_SEPARATOR);

define('PHP_EXT', '.php');


require_once(FRAMEWORK_PATH . 'bootstrap' . PHP_EXT);