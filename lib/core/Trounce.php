<?php
/**
 * Trounce - Rapid development PHP framework 
 * @copyrite Copyright (c) 2015,  Werner Roets
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Werner Roets <cobolt.exe@gmail.com>
 */


class Trounce {
    
    public static function Run($system_config){
        
		Logger::log_no_timestamp('system','##########################################');
		Logger::log_no_timestamp('system','###       Welcome to ' . FRAMEWORK_NAME . '        ###');
		Logger::log_no_timestamp('system','###    ' . date("l jS F Y H:m:s") . '   ###');
		Logger::log_no_timestamp('system','##########################################');
		
        require_once ROOT . DS . 'app'. DS . $system_config['app_name']. DS . 'app.config.php';
        
        $GLOBALS['system_config'] = array_merge($GLOBALS['system_config'],$app_config);
        
        /* IMPORTANT: Anything not in the system config or overwritten by
        # the app config is used as the setting from this point on.
        # It is important to remember that the system config is always
        # used before this point */
        Config::$app = array_merge($system_config,$app_config);
        
        #Include the app's entry point file
        require_once ROOT . DS . 'app'. DS . $system_config['app_name']. DS . 'index.php';
    }
}

