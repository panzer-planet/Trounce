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

# File loading
/* Autoloading is not slow but it's defintely not faster
 * https://mwop.net/blog/245-Autoloading-Benchmarks.html
 * I'm avoiding autoloading where it's not a hassle. the
 * fastest solution is a class map but this will be a hybrid
 * as I don't expect the system to be very big.
 */
 
 # Load libraries
 $file_loader = function() use($system_config){
    if($system_config['dev_mode']){
        /* This can be used in development mode
        * so that enabled library files don't have to be added
        * to the class map manually
        */
        foreach($system_config['load_libs'] as $lib){
            foreach(glob( ROOT . DS . 'lib' . DS . $lib . DS . '*.php') as $filename){
                require_once $filename;
            }
        }
    }else{
        /* In production, a class map is the fastest
        * method of including files.
        */
        require_once ROOT . DS . 'lib'. DS . 'lib_map.php';
        foreach($lib_map as $lib => $classes){
            foreach($classes as $class){
                require_once ROOT . DS . 'lib' . $lib . DS . $class . '.php';
            }
        }
    }
};$file_loader();unset($file_loader);

# Autoloader
/* The Autoloader checks for models, helpers
 * and other app files
 */
function __autoload($class_name){
    #For loading models
    $found = false;
    $model_loc = ROOT . DS . 'app' . DS . 'models' . DS;
    #models
    if(file_exists($model_loc . DS . $class_name . '.php')){
        require_once $model_loc . DS . $class_name . '.php';
        $found = true;
    }
    
    if(!$found){
        throw new Exception("Class could not be loaded: ".$class_name);
    }
}
Logger::log_no_timestamp('system','##########################################');
Logger::log_no_timestamp('system','###       Welcome to ' . FRAMEWORK_NAME . '        ###');
Logger::log_no_timestamp('system','###    ' . date("l jS F Y H:m:s") . '   ###');
Logger::log_no_timestamp('system','##########################################');

    # Include app config
require_once ROOT . DS . 'app'. DS . 'app.config.php';
    


    /* IMPORTANT: Anything not in the system config or overwritten by
    # the app config is used as the setting from this point on.
    # It is important to remember that the system config is always
    # used before this point */
   
   #System configuration is available with Config::$system
Config::$system = $system_config;
    #App configuration is available with Config::$app
Config::$app = array_merge($system_config,$app_config);

    # Include the app's entry point file
require_once ROOT . DS . 'app'. DS . 'index.php';

    
if($system_config['debug']){
    $total_time = round(microtime() - $system_time_start, 4);
    echo "<pre>";print_r($GLOBALS);
    echo 'Page generated in '. $total_time . ' seconds.';

}

