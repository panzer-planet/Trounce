<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

/*
 * Welcome to the Kobolt general purpose PHP MVC framework.
 * This is the entry point of the application
 */

define('FRAMEWORK_NAME',"Kobolt MVC");
define('DS',DIRECTORY_SEPARATOR);
define('ROOT',dirname(dirname(__FILE__))); # The root is this file's directory's parent

require_once ROOT . DS . 'config.php';

# Configuration file settings can be overridden before running here.
$system_config['system_debug'] = true;
$system_config['system_logging'] = true;


function __autoload($class_name){
	
	if(file_exists(ROOT . DS . 'lib' . DS . 'core' . DS . $class_name . '.php')){
		require_once ROOT . DS . 'lib' . DS .'core'. DS . $class_name . '.php';
		
	}elseif(file_exists(ROOT . DS . 'lib' . DS . 'mvc' . DS . $class_name . '.php')){
		require_once ROOT . DS . 'lib' . DS .'mvc'. DS . $class_name . '.php';
	}else{
		#class not found
	}

}

# Begin
Trounce::Run($system_config);