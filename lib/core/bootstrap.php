<?php
    # Error_reporting
if($system_config['debug']){
    ini_set('display_errors',1);
    error_reporting(E_ALL);
}

    # Autoloader
function __autoload($class_name){

    $lib_loc = ROOT . DS . 'lib'. DS;
    $libs = $GLOBALS['system_config']['load_libraries'];
    foreach($libs as $lib){
        if(file_exists($lib_loc.$lib.DS.$class_name. '.php')){       
            require_once($lib_loc.$lib.DS.$class_name. '.php');
        }
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
Config::$system = $system_config;
Config::$app = array_merge($system_config,$app_config);

    # Include the app's entry point file
require_once ROOT . DS . 'app'. DS . 'index.php';

    
if($system_config['debug']){
    $total_time = round(microtime() - $system_time_start, 4);
    echo "<pre>";print_r($GLOBALS);
    echo 'Page generated in '. $total_time . ' seconds.';

}

