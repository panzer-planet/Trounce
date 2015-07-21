<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */

 # Error_reporting
if($system_config['debug']){
    ini_set('display_errors',1);
    error_reporting(E_ALL);
}
require_once ROOT . DS . 'lib' . DS . 'config' . DS . 'filemap.config.php';
$load_libs = function() use($lib_files){
    foreach($lib_files as $lib_file){
        require_once $lib_file;
    }
};$load_libs();unset($load_libs);unset($lib_files);
 
# Autoloader
/* The Autoloader checks for models, helpers
 * and other app files not loaded explicitly
 */
function __autoload($class_name){
    #For loading models
    $found = false;
    $model_loc = ROOT . DS . 'app' . DS . 'models';
    #models
    Logger::write('system',"Loading {$model_loc}/{$class_name}.php");
    if(file_exists($model_loc . DS . $class_name . '.php')){
        require_once $model_loc . DS . $class_name . '.php';
        $found = true;
    }
    
    if(!$found){
        if($system_config['debug']){
            die("Class could not be loaded: ".$class_name);
        }else{
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        }
       
    } 
}

Logger::write('system','###        Welcome to ' . FRAMEWORK_NAME . '        ###');
Logger::write('system','###  ' . date("l jS F Y H:m:s") . '  ###');

    /* IMPORTANT: Anything not in the system config or overwritten by
    # the app config is used as the setting from this point on.
    # It is important to remember that the system config is always
    # used before this point */

$app_config = array_merge($system_config,$app_config);
App::set_config($app_config);
 # Clean up the global namespace
unset($app_config);
unset($system_config);
 # Run the app
App::run();

 # Microtime end
if(App::$_config['debug']){
    $system_time_total = microtime() - $system_time_start;
    echo "<pre>";print_r($GLOBALS);
    echo 'Page generated in '. round($system_time_total,4) . ' seconds.';
}

