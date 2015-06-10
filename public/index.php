<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

/*
 * Welcome to Trounce - Rapid development PHP framework.
 * This is the entry point of the application
 */

$system_time_start = microtime();

define('FRAMEWORK_NAME',"Trounce");
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
        throw new Exception('Autoloader could not find file');
	}
}

# Begin
Trounce::Run($system_config);

$total_time = round(microtime() - $system_time_start, 4);
if($system_config['system_debug']){ 
    echo 'Page generated in '. $total_time . ' seconds.';
}
